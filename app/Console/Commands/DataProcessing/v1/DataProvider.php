<?php
/**
 * User: jpm
 * Date: 18/01/20
 * Time: 7:30 pm
 */

namespace App\Console\Commands\DataProcessing\v1;


use App\ModelBhavCopyCM;
use App\ModelBhavCopyDelvPosition;
use App\ModelBhavCopyFO;
use App\ModelMasterStocksFO;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DataProvider
{
    //return fno stocks from predefined table.
    public function get_future_traded_stocks(){
        return ModelMasterStocksFO::where('symbol', 'AXISBANK')->get();
    }

    public function verify_all_data_sources(string $symbol, Carbon $date, $is_index){
        $data = $this->get_cm_for_date($symbol, $date);
        if (!$data) return false;

        $data = $this->get_futures_for_date($symbol, $date, $is_index);
        if (!$data || count($data) > 3 || count($data) < 3) return false;

        $data = $this->get_delv_for_date($symbol, $date);
        if (!$data) return false;
    }

    public function get_cm_for_date(string $symbol, Carbon $date){
        return ModelBhavCopyCM::symbolAndDate($symbol, $date, 'EQ')
            ->isVersion1Processed()
            ->first();
    }

    public function get_previous_trading_day(Carbon $date){
        $data = DB::table('bhavcopy_delv_position')
            ->whereDate('date', '<', $date)
            ->orderBy('date', 'desc')
            ->limit(1)
            ->first();
        if ($data) return Carbon::createFromFormat('Y-m-d', $data->date);
        return null;
    }

    public function get_futures_for_date(string $symbol, Carbon $date, $is_index = false){
        return ModelBhavCopyFO::symbolAndDate($symbol, $date, ($is_index) ? 'FUTIDX' : 'FUTSTK')->get();
    }

    public function get_op_ce_for_date(string $symbol, Carbon $date, $is_index = false){
        return ModelBhavCopyFO::symbolAndDate($symbol, $date, ($is_index) ? 'OPTIDX' : 'OPTSTK')
            ->ofOptionType('CE')
            ->get();
    }

    public function get_op_pe_for_date(string $symbol, Carbon $date, $is_index = false){
        return ModelBhavCopyFO::symbolAndDate($symbol, $date, ($is_index) ? 'OPTIDX' : 'OPTSTK')
            ->ofOptionType('PE')
            ->get();
    }

    public function get_delv_for_date(string $symbol, Carbon $date){
        return ModelBhavCopyDelvPosition::symbolAndDate($symbol, $date, 'EQ')->first();
    }

    public function get_price_change(ModelBhavCopyCM $model){
        $pct_change = (($model->close - $model->prevclose) * 100) / $model->prevclose;
        return round($pct_change, 2);
    }

    public function get_cum_fut_oi($futures){
        $cumulative_oi = 0;
        foreach ($futures as $f) $cumulative_oi += $f->oi;
        return $cumulative_oi;
    }

    public function change_cum_fut_oi($currentDayFutures){
        $current_oi = $this->get_cum_fut_oi($currentDayFutures);

        $future = $currentDayFutures[0];
        $currentDay = Carbon::createFromFormat('Y-m-d', $future->date);
        $symbol = $future->symbol;
        $is_index = ($future->instrument == 'FUTIDX');


        $previous_trading_day = $this->get_previous_trading_day($currentDay);
        if ($previous_trading_day){
            $yesterday_futures = $this->get_futures_for_date($symbol, $previous_trading_day, $is_index);
        }else return 0;

        $yesterday_oi = $this->get_cum_fut_oi($yesterday_futures);

        $coi_change = $current_oi - $yesterday_oi;
        $coi_pct = ($yesterday_oi == 0) ? 0 : (($coi_change * 100) / $yesterday_oi);
        return round($coi_pct, 2);
    }
}
