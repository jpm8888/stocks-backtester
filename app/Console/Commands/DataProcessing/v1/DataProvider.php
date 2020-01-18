<?php
/**
 * User: jpm
 * Date: 18/01/20
 * Time: 7:30 pm
 */

namespace App\Console\Commands\DataProcessing\v1;


use App\ModelBhavCopyCM;
use App\ModelMasterStocksFO;
use Carbon\Carbon;

class DataProvider
{
    //return fno stocks from predefined table.
    public function get_fo_stocks(){
        return ModelMasterStocksFO::where('symbol', 'AXISBANK')->get();
    }

    public function get_cm_for_date(string $symbol, Carbon $date){
        return ModelBhavCopyCM::whereDate('date', $date)
            ->where('symbol', $symbol)
            ->where('series', 'EQ')
            ->where('v1_processed', 0)
            ->first();
    }



}
