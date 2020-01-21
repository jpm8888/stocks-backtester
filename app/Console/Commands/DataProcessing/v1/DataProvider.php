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

class DataProvider
{
    //return fno stocks from predefined table.
    public function get_fo_stocks(){
        return ModelMasterStocksFO::where('symbol', 'AXISBANK')->get();
    }

    public function get_cm_for_date(string $symbol, Carbon $date){
        return ModelBhavCopyCM::symbolAndDate($symbol, $date, 'EQ')
            ->isVersion1Processed()
            ->first();
    }

    public function get_fo_for_date(string $symbol, Carbon $date, $is_index = false){
        return ModelBhavCopyFO::symbolAndDate($symbol, $date, ($is_index) ? 'FUTIDX' : 'FUTSTK')->limit(3)->get();
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

}
