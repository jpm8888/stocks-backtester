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

class UpdateCumulativeOpenInterest extends Command
{

    protected $signature = 'process:cumulative_oi';
    protected $description = 'Process cumulative open interest';

    //cum_fut_oi
    //cum_pe_oi
    //cum_ce_oi

    //change_cum_fut_oi_val
    //change_cum_pe_oi_val
    //change_cum_ce_oi_val

    public function __construct(){
        parent::__construct();
    }

    public function handle(){
        $this->info('working...');

//        $provider = new DataProvider($this);



        ModelBhavcopyProcessed::where('v1_processed', 0)
            ->where('v1_processed', 0)
            ->chunkById(100, function ($chunks) {
                $last_id = 0;
                foreach ($chunks as $c) {
                    $totals = DB::table('bhavcopy_fo')
                        ->select('symbol', 'date', 'option_type',
                            DB::raw('sum(change_in_oi) as total_change_oi'),
                            DB::raw('max(change_in_oi) as total_change_oi'),

                            DB::raw('sum(oi) as total_oi'))
                        ->groupBy('symbol', 'date', 'option_type')
                        ->where('symbol', $c->symbol)
                        ->whereDate('date', $c->date)
                        ->get();

                    foreach ($totals as $t){
                        switch ($t->option_type){
                            case 'XX':
                                $c->cum_fut_oi = $t->total_oi;
                                $c->change_cum_fut_oi_val = $t->total_change_oi;
                                break;
                            case 'CE':
                                $c->cum_ce_oi = $t->total_oi;
                                $c->change_cum_ce_oi_val = $t->total_change_oi;
                                break;
                            case 'PE':
                                $c->cum_pe_oi = $t->total_oi;
                                $c->change_cum_pe_oi_val = $t->total_change_oi;
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


