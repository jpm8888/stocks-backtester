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

    //get option data for date
    public function get_option_chain_for_date(string $symbol, $option_type, Carbon $date, $is_index = false){
        return ModelBhavCopyFO::symbolAndDate($symbol, $date, ($is_index) ? 'OPTIDX' : 'OPTSTK')
            ->ofOptionType($option_type)
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
    public function get_cum_fut_oi($symbol, $date, $is_index){
        $data = ModelBhavCopyFO::symbolAndDate($symbol, $date, ($is_index) ? 'FUTIDX' : 'FUTSTK')
            ->select(DB::raw('sum(oi) as coi'))
            ->first();
        return $data->coi;
    }

    //change in percentage future oi
    public function change_cum_fut_oi($symbol, Carbon $date, $is_index){
        $current_oi = $this->get_cum_fut_oi($symbol, $date, $is_index);
        $previous_trading_day = $this->get_previous_trading_day($date);

        if ($previous_trading_day){
            $yesterday_oi = $this->get_cum_fut_oi($symbol, $previous_trading_day, $is_index);
            $coi_change = $current_oi - $yesterday_oi;
            $coi_pct = ($yesterday_oi == 0) ? 0 : (($coi_change * 100) / $yesterday_oi);
            return round($coi_pct, 2);
        }
        return 0;
    }


    public function get_calculated_option_data(string $symbol, Carbon $date, $is_index){
        $calculated_calls = $this->calculate_option_data($symbol, 'CE', $date, $is_index);
        $calculated_puts = $this->calculate_option_data($symbol, 'PE', $date, $is_index);

        $pcr = $calculated_puts['cum_oi'] / $calculated_calls['cum_oi'];
        $pcr = round($pcr, 2);

        return [
            'cum_ce_oi' => $calculated_calls['cum_oi'],
            'cum_pe_oi' => $calculated_puts['cum_oi'],

            'change_cum_ce_oi' => $calculated_calls['change_cum_coi'],
            'change_cum_pe_oi' => $calculated_puts['change_cum_coi'],

            'pcr' => $pcr,

            'max_ce_oi_strike' => $calculated_calls['max_oi_strike'],
            'max_pe_oi_strike' => $calculated_puts['max_oi_strike'],
        ];
    }

    private function calculate_option_data($symbol, $option_type, $date, $is_index){
        $coi_pct = 0;
        $max_oi_strike = $this->max_strike_price($symbol, $option_type, $date, $is_index);

        $today_coi = $this->coi_options($symbol, $option_type, $date, $is_index);

        $yesterday = $this->get_previous_trading_day($date);
        if ($yesterday){
            $yesterday_coi = $this->coi_options($symbol, $option_type, $yesterday, $is_index);
            $coi_change = $today_coi - $yesterday_coi;
            $coi_pct = ($yesterday_coi == 0) ? 0 : (($coi_change * 100) / $yesterday_coi);
            $coi_pct = round($coi_pct, 2);
        }

        return [
            'max_oi_strike' => $max_oi_strike,
            'cum_oi' => $today_coi,
            'change_cum_coi' => $coi_pct,
        ];
    }

    private function coi_options($symbol, $option_type, Carbon $date, $is_index){
        $data = ModelBhavCopyFO::symbolAndDate($symbol, $date, ($is_index) ? 'OPTIDX' : 'OPTSTK')
            ->select(DB::raw('sum(oi) as coi'))
            ->ofOptionType($option_type)
            ->first();
        return $data->coi;
    }

    private function max_strike_price($symbol, $option_type, Carbon $date, $is_index){
        $data = ModelBhavCopyFO::symbolAndDate($symbol, $date, ($is_index) ? 'OPTIDX' : 'OPTSTK')
            ->select('strike_price')
            ->ofOptionType($option_type)
            ->orderBy('oi', 'desc')
            ->first();
        return $data->strike_price;
    }


    public function avg_volume_cm_5($symbol, Carbon $date){
        return $this->avg_volume_cm($symbol, $date, 5);
    }

    public function avg_volume_cm_10($symbol, Carbon $date){
        return $this->avg_volume_cm($symbol, $date, 10);
    }

    public function avg_volume_cm_15($symbol, Carbon $date){
        return $this->avg_volume_cm($symbol, $date, 15);
    }

    public function avg_volume_cm_52($symbol, Carbon $date){
        return $this->avg_volume_cm($symbol, $date, 52);
    }

    private function avg_volume_cm($symbol, Carbon $date, $days){
        $d = $date->format('Y-m-d');
        $out = DB::select(DB::raw("select avg(volume) as avg_vol from (select volume from bhavcopy_cm where symbol= '$symbol' and date < '$d' order by date desc limit $days) as vols"));
        return intval($out[0]->avg_vol);
    }

    public function all_time_high($symbol, Carbon $date){

    }

    public function all_time_low($symbol, Carbon $date){

    }

    public function high_day_5($symbol, Carbon $date){

    }

    public function high_day_10($symbol, Carbon $date){

    }

    public function high_day_15($symbol, Carbon $date){

    }

    public function high_day_52($symbol, Carbon $date){

    }

}
