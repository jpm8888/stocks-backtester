<?php
/**
 * User: jpm
 * Date: 18/01/20
 * Time: 7:14 pm
 */

namespace App\Console\Commands\DataProcessing\v1;


use App\Http\Controllers\Utils\Logger;
use App\ModelBhavcopyProcessed;
use App\ModelIndices;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessBhavcopyIndices extends Command
{
    protected $signature = 'process:bhavcopy_indices_v1 {year?}';
    protected $description = 'Process version 1 of bhavcopy indices';

    private $partition_name; //partition name
    public function __construct(){
        parent::__construct();

    }

    public function handle(){
        //year will be use in determining partition name
        $year = $this->argument('year');
        $this->partition_name = 'p_' . ((trim($year) == '') ? Carbon::now()->year : $year);
        $this->write_log('working on partition : ' . $this->partition_name);


        $this->write_log('starting...');
        $verification = $this->verify_data_integrity();
        if (!$verification) return;
        $this->write_log('data integrity passed.');
        $this->write_log('copying from indices');
        $copying = $this->copy_from_indices();
        if (!$copying){
            $this->write_log('error : in copying indices..');
            return;
        }
        $this->write_log('indices copying done, records copied :' . ModelIndices::where('v1_processed', 0)->count());

        $this->process();

        $this->write_log('---------------------------- Indices all data processed ----------------------------');
    }

    public function process(){
        DB::beginTransaction();
        try{
            $pname = $this->partition_name;

            $price_change = $this->calculate_price_change();
            if (!$price_change){
                $this->write_log('error : in price change..');
                return;
            }
            $this->write_log('1. price change calculated success');
            $coi = $this->calculate_coi();
            $this->write_log('2. coi calculated success');

            $delta_coi = $this->delta_coi();
            if (!$delta_coi){
                $this->write_log('error : in delta coi..');
                return;
            }
            $this->write_log('3. delta coi calculated success');

            $max_strike_price_oi = $this->max_strike_price_oi();
            $this->write_log('4. max strike price oi calculated success');
            $avg_volumes = $this->avg_volumes();
            $this->write_log('5. avg volumes calculated success');
            $highs = $this->highs();
            $this->write_log('6. highs calculated success');
            $lows = $this->lows();
            $this->write_log('7. lows calculated success');

            $this->write_log(ModelBhavcopyProcessed::where('v1_processed', 0)->count() . ' records processed and ready to commit');

            DB::statement("update bhavcopy_processed partition($pname) set v1_processed = 1 where v1_processed = 0");

            DB::commit();
            $this->write_log('saved successfully');
        }catch (\Exception $e){
            $this->write_log('error exception : ' . $e->getMessage());
            DB::rollBack();
        }
    }

    public function copy_from_indices(){
        $pname = $this->partition_name;
        $query = "insert into bhavcopy_processed (symbol, open, high, low, close, prevclose, volume, date) ";
        $query .= "select symbol, open, high, low, close, prevclose, volume, date from indices partition ($pname) where v1_processed = 0";
        $output = DB::statement($query);
        $output = DB::statement("update indices partition($pname) set v1_processed = 1 where v1_processed = 0");
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
            $this->write_log('error in change_cum_fut_oi calculating...');
            return $output;
        }

        $query = "update bhavcopy_processed partition ($pname) set change_cum_pe_oi = IFNULL(ROUND((change_cum_pe_oi_val * 100) / NULLIF((cum_pe_oi - (change_cum_pe_oi_val)), 0), 2), 0) where v1_processed = 0";
        $output = DB::statement($query);
        if (!$output) {
            $this->write_log('error in change_cum_pe_oi calculating...');
            return $output;
        }

        $query = "update bhavcopy_processed partition ($pname) set change_cum_ce_oi = IFNULL(ROUND((change_cum_ce_oi_val * 100) / NULLIF((cum_ce_oi - (change_cum_ce_oi_val)), 0), 2), 0) where v1_processed = 0";
        $output = DB::statement($query);
        if (!$output) {
            $this->write_log('error in change_cum_ce_oi calculating...');
            return $output;
        }

        $query = "update bhavcopy_processed partition ($pname) set pcr = IFNULL(ROUND(cum_pe_oi / NULLIF(cum_ce_oi, 0), 2), 0) where v1_processed = 0";
        $output = DB::statement($query);
        if (!$output) {
            $this->write_log('error in pcr calculating...');
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
        $cm = DB::table('indices')->select('date')->distinct('date')->orderBy('date', 'desc')->limit(10)->get();
        $fo = DB::table('bhavcopy_fo')->select('date')->distinct('date')->orderBy('date', 'desc')->limit(10)->get();
//        $delv = true;


        if (count($cm) != count($fo)){
            $this->write_log('error : count does not match up');
            return false;
        }

        for ($i = 0; $i < $limit; $i++){
            $date_cm = $cm[$i]->date;
            $date_fo = $fo[$i]->date;
//            $date_delv = $delv[$i]->date;

            if ($date_cm == $date_fo){

            }else{
                $this->write_log('error : data dates does not match up.');
                return false;
            }
        }

        return true;
    }

    private function write_log($msg){
        $logger = new Logger();
        $logger->insertLog(Logger::LOG_TYPE_INDICES_V1_PROCESSING, $msg);
        $this->info(Carbon::now() . ' : ' . $msg);
    }

}
