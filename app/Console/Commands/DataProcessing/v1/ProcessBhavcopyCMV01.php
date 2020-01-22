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

    private $MAX_DAYS = 4;
    protected $signature = 'process:bhavcopy_v1 {from_date?}';
    protected $description = 'Process version 1 of bhavcopy';

    private $provider;
    public function __construct(){
        parent::__construct();
        $this->provider = new DataProvider();
    }

    public function handle(){
        $from_date = $this->argument('from_date');

        if (trim($from_date) == '') {
            $from_date = Carbon::now()->subDays($this->MAX_DAYS);
        }else {
            $from_date = Carbon::createFromFormat('d-m-Y', $from_date);
        }

        $fo_stocks = $this->provider->get_future_traded_stocks();
        $provider = new DataProvider();
        while(Carbon::now()->gte($from_date)){
            if ($from_date->isWeekend()) $from_date->addDay();
            if ($from_date->isWeekend()) $from_date->addDay();
            $this->info($from_date);
            $fd = $from_date->format('d-m-Y');
            foreach ($fo_stocks as $f){
                $is_index = false;
                $symbol = $f->symbol;
                $verified = $provider->verify_all_data_sources($symbol, $from_date, $is_index);
                if ($verified){
                    $this->save_data($provider, $symbol, $from_date);
                    $this->info("successfully processed $symbol for $fd");
                }else{
                    $this->error("not verified $symbol for $fd");
                }
            }
            $from_date = $from_date->addDay();
        }
    }

    private function save_data(DataProvider $provider, $symbol, Carbon $date){
        DB::beginTransaction();
        try {
            $m = new ModelBhavcopyProcessed();
            $cm = $provider->get_cm_for_date($symbol, $date);
            $is_index = false;
            if (!$cm) return;

            $delv = $provider->get_delv_for_date($symbol, $date);
            $options_data = $provider->get_calculated_option_data($symbol, $date, $is_index);

            $m->symbol = $symbol;
            $m->series = $cm->series;
            $m->open = $cm->open;
            $m->high = $cm->high;
            $m->low = $cm->low;
            $m->close = $cm->close;
            $m->prevclose = $cm->prevclose;
            $m->price_change = $provider->get_price_change($cm);
            $m->volume = $cm->volume;
            $m->dlv_qty = $delv->dlv_qty;
            $m->pct_dlv_traded = $delv->pct_dlv_traded;
            $m->cum_fut_oi = $provider->get_cum_fut_oi($symbol, $date, $is_index);
            $m->change_cum_fut_oi = $provider->change_cum_fut_oi($symbol, $date, $is_index);

            $m->cum_pe_oi = $options_data['cum_pe_oi'];
            $m->cum_ce_oi = $options_data['cum_ce_oi'];

            $m->change_cum_pe_oi = $options_data['change_cum_pe_oi'];
            $m->change_cum_ce_oi = $options_data['change_cum_ce_oi'];

            $m->pcr = $options_data['pcr'];

            $m->max_pe_oi_strike = $options_data['max_pe_oi_strike'];
            $m->max_ce_oi_strike = $options_data['max_ce_oi_strike'];

            $m->avg_volume_five = $provider->avg_volume_cm_5($symbol, $date);
            $m->avg_volume_ten = $provider->avg_volume_cm_10($symbol, $date);
            $m->avg_volume_fifteen = $provider->avg_volume_cm_15($symbol, $date);
            $m->avg_volume_fiftytwo = $provider->avg_volume_cm_52($symbol, $date);

            $m->low_five = $provider->low_day_cm_5($symbol, $date);
            $m->low_ten = $provider->low_day_cm_10($symbol, $date);
            $m->low_fifteen = $provider->low_day_cm_15($symbol, $date);
            $m->low_fiftytwo = $provider->low_day_cm_52($symbol, $date);

            $m->high_five = $provider->high_day_cm_5($symbol, $date);
            $m->high_ten = $provider->high_day_cm_10($symbol, $date);
            $m->high_fifteen = $provider->high_day_cm_15($symbol, $date);
            $m->high_fiftytwo = $provider->high_day_cm_52($symbol, $date);

            $m->date = $date;
            $m->save();

            $cm->v1_processed = 1;
            $cm->save();

            DB::commit();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            DB::rollback();
        }
    }
}
