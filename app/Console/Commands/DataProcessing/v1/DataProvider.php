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

    //get future data for date
    public function get_futures_for_date(string $symbol, Carbon $date, $is_index = false){
        return ModelBhavCopyFO::symbolAndDate($symbol, $date, ($is_index) ? 'FUTIDX' : 'FUTSTK')->get();
    }

    //get call option data for date
    public function get_op_ce_for_date(string $symbol, Carbon $date, $is_index = false){
        return ModelBhavCopyFO::symbolAndDate($symbol, $date, ($is_index) ? 'OPTIDX' : 'OPTSTK')
            ->ofOptionType('CE')
            ->get();
    }

    //get put option data for date
    public function get_op_pe_for_date(string $symbol, Carbon $date, $is_index = false){
        return ModelBhavCopyFO::symbolAndDate($symbol, $date, ($is_index) ? 'OPTIDX' : 'OPTSTK')
            ->ofOptionType('PE')
            ->get();
    }

    //get  delivery for date.
    public function get_delv_for_date(string $symbol, Carbon $date){
        return ModelBhavCopyDelvPosition::symbolAndDate($symbol, $date, 'EQ')->first();
    }

    //get price change in percentage
    public function get_price_change(ModelBhavCopyCM $model){
        $pct_change = (($model->close - $model->prevclose) * 100) / $model->prevclose;
        return round($pct_change, 2);
    }

    //get cumulative open interest of three expiry
    public function get_cum_fut_oi($futures){
        $cumulative_oi = 0;
        foreach ($futures as $f) $cumulative_oi += $f->oi;
        return $cumulative_oi;
    }

    //change in percentage future oi
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


    public function get_calculated_option_data(string $symbol, Carbon $date, $is_index){
        $call_options = $this->get_op_ce_for_date($symbol, $date, $is_index);
        $put_options = $this->get_op_pe_for_date($symbol, $date, $is_index);

        $calculated_calls = $this->calculate_option_data($call_options);
        $calculated_puts = $this->calculate_option_data($put_options);

        $pcr = $calculated_puts['cum_oi'] / $calculated_calls['cum_oi'];
        $pcr = round($pcr, 2);

        return [
            'cum_ce_oi' => $calculated_calls['cum_oi'],
            'cum_pe_oi' => $calculated_puts['cum_oi'],

            'change_cum_ce_oi' => 0,
            'change_cum_pe_oi' => 0,

            'pcr' => $pcr,

            'max_ce_oi_strike' => $calculated_calls['max_oi_strike'],
            'max_pe_oi_strike' => $calculated_puts['max_oi_strike'],
        ];
    }

    private function calculate_option_data($options){
        $max_oi_strike = 0;
        $cum_oi = 0;
        //        $change_cum_oi = 0;

        $max_oi = 0;
        foreach ($options as $option){
            $cum_oi += $option->oi;

            $new_max = max($max_oi, $option->oi);
            $max_oi_strike = ($new_max > $max_oi) ? $option->strike_price : $max_oi_strike;
            $max_oi = ($new_max > $max_oi) ? $new_max : $max_oi;
        }

        return [
          'max_oi_strike' => $max_oi_strike,
          'cum_oi' => $cum_oi,
        ];
    }

}
