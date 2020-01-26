<?php
/**
 * User: jpm
 * Date: 21/01/20
 * Time: 7:51 pm
 */

namespace Tests\Unit\DataProcessing;


use App\Console\Commands\DataProcessing\v1\DataProvider;
use App\ModelBhavCopyFO;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class V1ProcessingOptionsTest extends TestCase
{

    private $symbol = "AXISBANK";
    private $date = "2020-01-20";

    public function testDataProviderForCallOptionsForIndex(){
        $symbol = "BANKNIFTY";
        $date = $this->date;

        $provider = new DataProvider();
        $formatted_date = Carbon::createFromFormat('Y-m-d', $date);


        $verification = $provider->verify_all_data_sources($symbol, $formatted_date, true);

        $this->assertTrue($verification, 'Verification failed : ' . $provider->error);

        $stocks = $provider->get_option_chain_for_date($symbol, 'CE', $formatted_date, true);

        $this->assertNotNull($stocks);

        foreach ($stocks as $stock){
            $this->assertEquals($symbol, $stock->symbol);
            $this->assertEquals('CE', $stock->option_type);
            $this->assertEquals('OPTIDX', $stock->instrument);
            $this->assertEquals($date, $stock->date);
        }
    }

    public function testDataProviderForCallOptionStock(){
        $symbol = $this->symbol;
        $date = $this->date;

        $provider = new DataProvider();
        $formatted_date = Carbon::createFromFormat('Y-m-d', $date);

        $verification = $provider->verify_all_data_sources($symbol, $formatted_date, false);
        $this->assertTrue($verification, 'Verification failed : ' . $provider->error);

        $stocks = $provider->get_option_chain_for_date($symbol,'CE',  $formatted_date, false);

        $this->assertNotNull($stocks);

        foreach ($stocks as $stock){
            $this->assertEquals($symbol, $stock->symbol);
            $this->assertEquals('CE', $stock->option_type);
            $this->assertEquals('OPTSTK', $stock->instrument);
            $this->assertEquals($date, $stock->date);
        }
    }

    public function testDataProviderForCallOptionCumulativeOpenInterest(){
        $symbol = $this->symbol;
        $date = $this->date;
        $is_index = false;

        $provider = new DataProvider();
        $formatted_date = Carbon::createFromFormat('Y-m-d', $date);

        $verification = $provider->verify_all_data_sources($symbol, $formatted_date, false);
        $this->assertTrue($verification, 'Verification failed : ' . $provider->error);

        $data = $provider->get_calculated_option_data($symbol, $formatted_date, false);

        $coi_ce = $this->coi_options($symbol, $formatted_date, 'CE', $is_index);
        $coi_pe = $this->coi_options($symbol, $formatted_date, 'PE', $is_index);

        $this->assertEquals($coi_ce, $data['cum_ce_oi']);
        $this->assertEquals($coi_pe, $data['cum_pe_oi']);

        $max_strike_ce = $this->max_strike_price($symbol, $formatted_date, 'CE', $is_index);
        $max_strike_pe = $this->max_strike_price($symbol, $formatted_date, 'PE', $is_index);

        $this->assertEquals($max_strike_ce, $data['max_ce_oi_strike']);
        $this->assertEquals($max_strike_pe, $data['max_pe_oi_strike']);

        $pcr = round(($coi_pe / $coi_ce), 2);
        $this->assertEquals($pcr, $data['pcr'], 'Wrong PCR');

        $past_day = $provider->get_previous_trading_day($formatted_date);

        $yesterday_coi_ce = $this->coi_options($symbol, $past_day, 'CE', false);
        $change_coi_ce = $this->change_coi_options($symbol, $formatted_date, 'CE',false);
        $pct_ce = ($yesterday_coi_ce == 0) ? 0 : (($change_coi_ce * 100) / $yesterday_coi_ce);
        $pct_ce = round($pct_ce, 2);

        $this->assertEquals($pct_ce, $data['change_cum_ce_oi'], 'Call options change is wrong');


        $yesterday_coi_pe = $this->coi_options($symbol, $past_day, 'PE', false);
        $change_coi_pe = $this->change_coi_options($symbol, $formatted_date, 'PE',false);
        $pct_pe = ($yesterday_coi_pe == 0) ? 0 : (($change_coi_pe * 100) / $yesterday_coi_pe);
        $pct_pe = round($pct_pe, 2);
        $this->assertEquals($pct_pe, $data['change_cum_pe_oi'], 'Put options change is wrong');
    }

    private function coi_options($symbol, Carbon $date, $option_type, $is_index){
        $data = ModelBhavCopyFO::symbolAndDate($symbol, $date, ($is_index) ? 'OPTIDX' : 'OPTSTK')
            ->select(DB::raw('sum(oi) as coi'))
            ->ofOptionType($option_type)
            ->first();
        return $data->coi;
    }

    private function change_coi_options($symbol, Carbon $date, $option_type, $is_index){
        $data = ModelBhavCopyFO::symbolAndDate($symbol, $date, ($is_index) ? 'OPTIDX' : 'OPTSTK')
            ->select(DB::raw('sum(change_in_oi) as change_in_oi'))
            ->ofOptionType($option_type)
            ->first();
        return $data->change_in_oi;
    }

    private function max_strike_price($symbol, Carbon $date, $option_type, $is_index){
        $data = ModelBhavCopyFO::symbolAndDate($symbol, $date, ($is_index) ? 'OPTIDX' : 'OPTSTK')
            ->select('oi', 'strike_price')
            ->ofOptionType($option_type)
            ->orderBy('oi', 'desc')
            ->first();
        return $data->strike_price;
    }



}
