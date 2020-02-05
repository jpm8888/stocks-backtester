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

class UpdateMaxOptionOI extends Command
{

    protected $signature = 'process:maxOptionOI';
    protected $description = 'Process max option open interest';

    // max_ce_oi_strike
    // max_pe_oi_strike


    //base query
    //UPDATE bhavcopy_processed bp inner join (select symbol, date, option_type, sum(oi) as total from bhavcopy_fo where option_type = "XX" group by symbol, date, option_type) x on bp.symbol = x.symbol and bp.date = x.date set bp.cum_fut_oi = x.total where bp.id between 1 and 20;

    public function __construct(){
        parent::__construct();
    }

    public function handle(){
        $this->info('working...');

        ModelBhavcopyProcessed::where('v1_processed', 0)
            ->chunkById(100, function ($chunks) {
                $last_id = 0;
                foreach ($chunks as $c) {
                    $now = Carbon::parse($c->date);
                    $partition_name = "p_" . $now->year;
                    $current_id = $c->id;

                    $query = "update bhavcopy_processed as bp set bp.max_pe_oi_strike = ifnull((select bf.strike_price from bhavcopy_fo partition($partition_name) as bf ";
                    $query .= "where bf.symbol = bp.symbol and bf.date = bp.date and bf.option_type = 'PE' order by bf.oi desc limit 1), 0) where bp.id = $current_id;";
                    DB::statement($query);

                    $query = "update bhavcopy_processed as bp set bp.max_ce_oi_strike = ifnull((select bf.strike_price from bhavcopy_fo partition($partition_name) as bf ";
                    $query .= "where bf.symbol = bp.symbol and bf.date = bp.date and bf.option_type = 'CE' order by bf.oi desc limit 1), 0) where bp.id = $current_id;";
                    DB::statement($query);

                    $last_id = $c->id;
                }
                $this->info(Carbon::now() . ' : last record computed : ' . $last_id);
            });

        $this->info(Carbon::now() . ' : all done');
    }

}


