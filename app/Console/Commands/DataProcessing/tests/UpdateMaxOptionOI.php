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

    protected $signature = 'process:max_option_oi';
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
//            ->where('id', '<', 423781)
            ->chunkById(1000, function ($chunks){
                $last_id = 0;
                foreach ($chunks as $c) {

                    $now = Carbon::parse($c->date);
                    $partition_name = "p_" . $now->year;

                    $type = 'PE';
                    $column_name = 'max_pe_oi_strike';

                    $query = "select strike_price from bhavcopy_fo partition($partition_name) where symbol = '$c->symbol' and date = '$c->date' and option_type = '$type' order by oi desc limit 1";
                    $output = DB::select(DB::raw($query));
                    $strike_price = (isset($output[0]->strike_price)) ? $output[0]->strike_price : 0;
                    DB::statement("update bhavcopy_processed set $column_name = $strike_price where id = $c->id");

                    $type = 'CE';
                    $column_name = 'max_ce_oi_strike';
                    $query = "select strike_price from bhavcopy_fo partition($partition_name) where symbol = '$c->symbol' and date = '$c->date' and option_type = '$type' order by oi desc limit 1";
                    $output = DB::select(DB::raw($query));
                    $strike_price = (isset($output[0]->strike_price)) ? $output[0]->strike_price : 0;
                    DB::statement("update bhavcopy_processed set $column_name = $strike_price where id = $c->id");

                    $last_id = $c->id;
                }
                $this->info(Carbon::now() . ' : last record computed : ' . $last_id);
            });

        $this->info(Carbon::now() . ' : all done');
    }


    private function update_strike_price($c, $type, $column_name){

    }

}


