<?php
/**
 * User: jpm
 * Date: 18/01/20
 * Time: 7:14 pm
 */

namespace App\Console\Commands\DataProcessing\v1;


use App\ModelBhavCopyDelvPosition;
use App\ModelBhavcopyProcessed;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessBhavcopyCMV01 extends Command
{

    private $MAX_DAYS = 4;
    protected $signature = 'process:bhavcopy_v1';
    protected $description = 'Process version 1 of bhavcopy';

    public function __construct(){
        parent::__construct();
    }

    public function handle(){
        $provider = new DataProvider($this);

        ModelBhavCopyDelvPosition::where('verified', 0)
            ->where('v1_processed', 0)
            ->chunkById(500, function ($chunks) use ($provider) {
            foreach ($chunks as $c) {
                $f_date = Carbon::createFromFormat('Y-m-d', $c->date);
                $this->info("processing $c->symbol for $c->date");
                $verification_id = $this->save_data($provider, $c->symbol, $f_date);
                $c->update(['verified' => $verification_id]);
            }
        });
    }


    private function save_data(DataProvider $provider, $symbol, Carbon $date){
        DB::beginTransaction();
        $is_index = false;
        try {
            $verified = $provider->verify_all_data_sources($symbol, $date, $is_index);
            if (!$verified) {
                DB::rollback();
                return 2;
            }

            $this->info('verification_code -> ' . $verified);

            $m = new ModelBhavcopyProcessed();
            $cm = $provider->get_cm_for_date($symbol, $date);

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

            $delv->v1_processed = 1;
            $delv->save();

            DB::commit();
            return 1;
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            DB::rollback();
            return 2;
        }
    }
}
