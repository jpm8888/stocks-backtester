<?php
/**
 * User: jpm
 * Date: 18/01/20
 * Time: 7:14 pm
 */

namespace App\Console\Commands\DataProcessing\v1;


use App\ModelBhavcopyProcessed;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessBhavcopyCMV01 extends Command
{
    protected $signature = 'process:bhavcopy_v1';
    protected $description = 'Process version 1 of bhavcopy';

    private $partition_name; //partition name
    public function __construct(){
        parent::__construct();
        $this->partition_name = 'p_' . Carbon::now()->year;
    }

    public function handle(){
        $this->info('starting...');
        $verification = $this->verify_data_integrity();
        if (!$verification) return;
        $this->info('data integrity passed.');
        $this->info('copying from bhavcopy_delv_position');
        $copying = $this->copy_from_bhavcopies();
        if (!$copying){
            $this->error('error in copying..');
            return;
        }
        $this->info('copying done, records copied :' . ModelBhavcopyProcessed::where('v1_processed', 0)->count());

        $this->process();

        $this->info('all data processed');
    }

    public function process(){
        DB::beginTransaction();
        try{
            $pname = $this->partition_name;

            $price_change = $this->calculate_price_change();
            if (!$price_change){
                $this->error('error in price change..');
                return;
            }
            $this->info('1. price change calculated success');
            $coi = $this->calculate_coi();
            $this->info('2. coi calculated success');

            $delta_coi = $this->delta_coi();
            if (!$delta_coi){
                $this->error('error in delta coi..');
                return;
            }
            $this->info('3. delta coi calculated success');

            $max_strike_price_oi = $this->max_strike_price_oi();
            $this->info('4. max strike price oi calculated success');
            $avg_volumes = $this->avg_volumes();
            $this->info('5. avg volumes calculated success');
            $highs = $this->highs();
            $this->info('6. highs calculated success');
            $lows = $this->lows();
            $this->info('7. lows calculated success');

            $this->info(ModelBhavcopyProcessed::where('v1_processed', 0)->count() . ' records processed and ready to commit');

            DB::statement("update bhavcopy_processed partition($pname) set v1_processed = 1 where v1_processed = 0");

            DB::commit();
            $this->info('saved successfully');
        }catch (\Exception $e){
            $this->error($e->getMessage());
            DB::rollBack();
        }
    }

    public function copy_from_bhavcopies(){
        $pname = $this->partition_name;
        $query = "insert into bhavcopy_processed (symbol, series, open, high, low, close, prevclose, volume, date, dlv_qty, pct_dlv_traded) ";
        $query .= "select bc.symbol, bc.series, bc.open, bc.high, bc.low, bc.close, bc.prevclose, bc.volume, bc.date, bdp.dlv_qty, bdp.pct_dlv_traded from bhavcopy_cm partition($pname) as bc left join bhavcopy_delv_position as bdp on bc.symbol = bdp.symbol and bc.date = bdp.date and bc.series = bdp.series and bc.symbol in (select symbol from master_stocks_fo) and bc.series = 'EQ' where bdp.v1_processed = 0";
        $output = DB::statement($query);
        $output = DB::statement("update bhavcopy_delv_position partition($pname) set v1_processed = 1 where v1_processed = 0");
        return $output;
    }

    public function calculate_price_change(){
        $pname = $this->partition_name;
        $query = "update bhavcopy_processed partition($pname) set price_change = IFNULL(ROUND(((close - prevclose) * 100) / NULLIF(prevclose, 0), 2), 0) where v1_processed = 0";
        $output = DB::statement($query);
        return $output;
    }

    public function calculate_coi(){
        $pname = $this->partition_name;
        ModelBhavcopyProcessed::where('v1_processed', 0)
            ->chunkById(100, function ($chunks) use ($pname){
                foreach ($chunks as $c) {
                    $date = $c->date;
                    $symbol = $c->symbol;
                    $query = "select symbol, date, option_type, sum(oi) as total_oi, sum(change_in_oi) as total_change_oi from bhavcopy_fo partition($pname) where date= '$date' and symbol= '$symbol' group by symbol, date, option_type";
                    $totals = DB::select(DB::raw($query));
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
                }
            });
    }

    public function delta_coi(){
        $pname = $this->partition_name;
        $query = "update bhavcopy_processed partition ($pname) set change_cum_fut_oi = IFNULL(ROUND((change_cum_fut_oi_val * 100) / NULLIF((cum_fut_oi - (change_cum_fut_oi_val)), 0), 2), 0) where v1_processed = 0";
        $output = DB::statement($query);
        if (!$output) {
            $this->warn('error in change_cum_fut_oi calculating...');
            return $output;
        }

        $query = "update bhavcopy_processed partition ($pname) set change_cum_pe_oi = IFNULL(ROUND((change_cum_pe_oi_val * 100) / NULLIF((cum_pe_oi - (change_cum_pe_oi_val)), 0), 2), 0) where v1_processed = 0";
        $output = DB::statement($query);
        if (!$output) {
            $this->warn('error in change_cum_pe_oi calculating...');
            return $output;
        }

        $query = "update bhavcopy_processed partition ($pname) set change_cum_ce_oi = IFNULL(ROUND((change_cum_ce_oi_val * 100) / NULLIF((cum_ce_oi - (change_cum_ce_oi_val)), 0), 2), 0) where v1_processed = 0";
        $output = DB::statement($query);
        if (!$output) {
            $this->warn('error in change_cum_ce_oi calculating...');
            return $output;
        }

        $query = "update bhavcopy_processed partition ($pname) set pcr = IFNULL(ROUND(cum_pe_oi / NULLIF(cum_ce_oi, 0), 2), 0) where v1_processed = 0";
        $output = DB::statement($query);
        if (!$output) {
            $this->warn('error in pcr calculating...');
            return $output;
        }

        return $output;
    }

    public function max_strike_price_oi(){
        ModelBhavcopyProcessed::where('v1_processed', 0)
            ->chunkById(100, function ($chunks){
                foreach ($chunks as $c) {

                    $now = Carbon::parse($c->date);
                    $partition_name = "p_" . $now->year;

                    $type = 'PE';
                    $column_name = 'max_pe_oi_strike';

                    $query = "select strike_price from bhavcopy_fo partition($partition_name) where symbol = '$c->symbol' and date = '$c->date' and option_type = '$type' order by oi desc limit 1";
                    $output = DB::select(DB::raw($query));
                    $strike_price = (isset($output[0]->strike_price)) ? $output[0]->strike_price : 0;
                    DB::statement("update bhavcopy_processed partition ($partition_name)  set $column_name = $strike_price where id = $c->id");

                    $type = 'CE';
                    $column_name = 'max_ce_oi_strike';
                    $query = "select strike_price from bhavcopy_fo partition($partition_name) where symbol = '$c->symbol' and date = '$c->date' and option_type = '$type' order by oi desc limit 1";
                    $output = DB::select(DB::raw($query));
                    $strike_price = (isset($output[0]->strike_price)) ? $output[0]->strike_price : 0;
                    DB::statement("update bhavcopy_processed partition ($partition_name) set $column_name = $strike_price where id = $c->id");
                }
            });
    }

    public function avg_volumes(){
        DB::statement("update bhavcopy_processed as bp set bp.avg_volume_five = IFNULL((select avg(volume) as avg_vol from (select volume from bhavcopy_cm where symbol= bp.symbol and date < bp.date order by date desc limit 5) as vols), 0) where bp.v1_processed = 0");
        DB::statement("update bhavcopy_processed as bp set bp.avg_volume_ten = IFNULL((select avg(volume) as avg_vol from (select volume from bhavcopy_cm where symbol= bp.symbol and date < bp.date order by date desc limit 10) as vols), 0) where bp.v1_processed = 0");
        DB::statement("update bhavcopy_processed as bp set bp.avg_volume_fifteen = IFNULL((select avg(volume) as avg_vol from (select volume from bhavcopy_cm where symbol= bp.symbol and date < bp.date order by date desc limit 15) as vols), 0) where bp.v1_processed = 0");
        DB::statement("update bhavcopy_processed as bp set bp.avg_volume_fiftytwo = IFNULL((select avg(volume) as avg_vol from (select volume from bhavcopy_cm where symbol= bp.symbol and date < bp.date order by date desc limit 52) as vols), 0) where bp.v1_processed = 0");
    }

    public function highs(){
        DB::statement("update bhavcopy_processed as bp set bp.high_five = IFNULL((select max(high) as max_high from (select high from bhavcopy_cm as bc where bc.symbol= bp.symbol and bc.date < bp.date order by bc.date desc limit 5) as highs), 0) where bp.v1_processed = 0");
        DB::statement("update bhavcopy_processed as bp set bp.high_ten = IFNULL((select max(high) as max_high from (select high from bhavcopy_cm as bc where bc.symbol= bp.symbol and bc.date < bp.date order by bc.date desc limit 10) as highs), 0) where bp.v1_processed = 0");
        DB::statement("update bhavcopy_processed as bp set bp.high_fifteen = IFNULL((select max(high) as max_high from (select high from bhavcopy_cm as bc where bc.symbol= bp.symbol and bc.date < bp.date order by bc.date desc limit 15) as highs), 0) where bp.v1_processed = 0");
        DB::statement("update bhavcopy_processed as bp set bp.high_fiftytwo = IFNULL((select max(high) as max_high from (select high from bhavcopy_cm as bc where bc.symbol= bp.symbol and bc.date < bp.date order by bc.date desc limit 52) as highs), 0) where bp.v1_processed = 0");
    }

    public function lows(){
        DB::statement("update bhavcopy_processed as bp set bp.low_five = IFNULL((select min(low) as min_low from (select low from bhavcopy_cm as bc where bc.symbol= bp.symbol and bc.date < bp.date order by bc.date desc limit 5) as lows), 0) where bp.v1_processed = 0");
        DB::statement("update bhavcopy_processed as bp set bp.low_ten = IFNULL((select min(low) as min_low from (select low from bhavcopy_cm as bc where bc.symbol= bp.symbol and bc.date < bp.date order by bc.date desc limit 10) as lows), 0) where bp.v1_processed = 0");
        DB::statement("update bhavcopy_processed as bp set bp.low_fifteen = IFNULL((select min(low) as min_low from (select low from bhavcopy_cm as bc where bc.symbol= bp.symbol and bc.date < bp.date order by bc.date desc limit 15) as lows), 0) where bp.v1_processed = 0");
        DB::statement("update bhavcopy_processed as bp set bp.low_fiftytwo = IFNULL((select min(low) as min_low from (select low from bhavcopy_cm as bc where bc.symbol= bp.symbol and bc.date < bp.date order by bc.date desc limit 52) as lows), 0) where bp.v1_processed = 0");
    }

    private function verify_data_integrity()
    {
        $limit = 10;
        $cm = DB::table('bhavcopy_cm')->select('date')->distinct('date')->orderBy('date', 'desc')->limit(10)->get();
        $fo = DB::table('bhavcopy_fo')->select('date')->distinct('date')->orderBy('date', 'desc')->limit(10)->get();
        $delv = DB::table('bhavcopy_delv_position')->select('date')->distinct('date')->orderBy('date', 'desc')->limit(10)->get();


        if (count($cm) != count($fo) || count($fo) != count($delv)){
            $this->error('count does not match up');
            return false;
        }

        for ($i = 0; $i < $limit; $i++){
            $date_cm = $cm[$i]->date;
            $date_fo = $fo[$i]->date;
            $date_delv = $delv[$i]->date;

            if ($date_cm == $date_fo && $date_cm == $date_delv){

            }else{
                $this->error('data dates does not match up.');
                return false;
            }
        }

        return true;
    }

}
