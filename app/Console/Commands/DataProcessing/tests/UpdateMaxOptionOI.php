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
            ->where('v1_processed', 0)
            ->chunkById(100, function ($chunks) {
                $last_id = 0;
                foreach ($chunks as $c) {
                    $totals = DB::table('bhavcopy_fo')
                        ->select('symbol', 'date', 'option_type', 'strike_price',
                            DB::raw('max(oi) as max_oi'))
                        ->groupBy('symbol', 'date', 'option_type')
                        ->whereDate('date', $c->date)
                        ->where('symbol', $c->symbol)
                        ->get();

                    foreach ($totals as $t){
                        switch ($t->option_type){
                            case 'CE':
                                $c->max_ce_oi_strike = $t->strike_price;
                                break;
                            case 'PE':
                                $c->max_pe_oi_strike = $t->strike_price;
                                break;
                        }
                    }
                    $c->save();
                    $last_id = $c->id;
                }

                $this->info(Carbon::now() . " : $last_id records done");
            });

        $this->info(Carbon::now() . ' : all done');
    }

}


