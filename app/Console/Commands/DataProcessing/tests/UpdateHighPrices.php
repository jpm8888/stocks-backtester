<?php
/**
 * User: jpm
 * Date: 30/01/20
 * Time: 11:20 pm
 */

namespace App\Console\Commands\DataProcessing\tests;



use App\ModelBhavcopyProcessed;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateLowPrices extends Command
{

    protected $signature = 'process:high_prices';
    protected $description = 'Process 5, 10, 15, 52 high prices';

    // high_five
    // high_ten
    // high_fifteen
    // high_fiftytwo

    public function __construct(){
        parent::__construct();
    }

    public function handle(){
        $this->info('working...');

        $total_records = ModelBhavcopyProcessed::where('v1_processed', 0)->count();
        $batch_size = 1000;
        $min = ModelBhavcopyProcessed::where('v1_processed', 0)->first();
        $min = ($min) ? $min->id : 1;
        $max = $min + $batch_size;

        $compiled_records = 0;
        while($compiled_records < $total_records){
            $start_time = microtime(true);
            DB::statement("update bhavcopy_processed as bp set bp.high_five = IFNULL((select max(high) as max_high from (select high from bhavcopy_cm where symbol= bp.symbol and date < bp.date order by date desc limit 5) as highs), 0) where id between $min and $max");
            DB::statement("update bhavcopy_processed as bp set bp.high_ten = IFNULL((select max(high) as max_high from (select high from bhavcopy_cm where symbol= bp.symbol and date < bp.date order by date desc limit 10) as highs), 0) where id between $min and $max");
            DB::statement("update bhavcopy_processed as bp set bp.high_fifteen = IFNULL((select max(high) as max_high from (select high from bhavcopy_cm where symbol= bp.symbol and date < bp.date order by date desc limit 15) as highs), 0) where id between $min and $max");
            DB::statement("update bhavcopy_processed as bp set bp.high_fiftytwo = IFNULL((select max(high) as max_high from (select high from bhavcopy_cm where symbol= bp.symbol and date < bp.date order by date desc limit 52) as highs), 0) where id between $min and $max");

            DB::statement("update bhavcopy_processed set v1_processed = 1 where id between $min and $max");

            $end_time = microtime(true);
            $execution_time = round(($end_time - $start_time), 2);

            $this->info(Carbon::now() . ' : last record computed : ' . $max . " time : $execution_time seconds");
            $min = $max;
            $max += $batch_size;

//            if ($max > 1) $compiled_records = 100000000; // for debugging
            $compiled_records += $batch_size;
        }

        $this->info(Carbon::now() . ' : all done');
    }
}


