<?php

namespace Tests\Unit\DataProcessing;

use App\Console\Commands\DataProcessing\v1\DataProvider;
use Carbon\Carbon;
use Tests\TestCase;

class V1ProcessingTest extends TestCase
{

    private $symbol = "AXISBANK";
    private $date = "2020-01-20";

    public function testDataProviderFOStocks()
    {
        $provider = new DataProvider();
        $stocks = $provider->get_fo_stocks();
        $count = count($stocks);
        $this->assertTrue(($count > 0));
    }

    public function testDataProviderCM()
    {
        $symbol = $this->symbol;
        $date = $this->date;

        $provider = new DataProvider();
        $formatted_date = Carbon::createFromFormat('Y-m-d', $date);
        $stock = $provider->get_cm_for_date($symbol, $formatted_date);

        $this->assertEquals($symbol, $stock->symbol);
        $this->assertEquals('EQ', $stock->series);
        $this->assertEquals($date, $stock->date);
        $this->assertEquals(0, $stock->v1_processed);
    }

    public function testDataProviderDelv(){
        $symbol = $this->symbol;
        $date = $this->date;

        $provider = new DataProvider();
        $formatted_date = Carbon::createFromFormat('Y-m-d', $date);
        $stock = $provider->get_delv_for_date($symbol, $formatted_date);

        $this->assertNotNull($stock);
        $this->assertEquals($symbol, $stock->symbol);
        $this->assertEquals('EQ', $stock->series);
        $this->assertEquals($date, $stock->date);
    }

    public function testDataProviderFO(){
        $symbol = $this->symbol;
        $date = $this->date;

        $provider = new DataProvider();
        $formatted_date = Carbon::createFromFormat('Y-m-d', $date);
        $stocks = $provider->get_fo_for_date($symbol, $formatted_date, false);

        $this->assertNotNull($stocks);
        $this->assertEquals(3, count($stocks));

        foreach ($stocks as $stock){
            $this->assertEquals($symbol, $stock->symbol);
            $this->assertEquals('FUTSTK', $stock->instrument);
            $this->assertEquals($date, $stock->date);
        }
    }

    public function testDataProviderFOIndex(){
        $symbol = "BANKNIFTY";
        $date = $this->date;

        $provider = new DataProvider();
        $formatted_date = Carbon::createFromFormat('Y-m-d', $date);
        $stocks = $provider->get_fo_for_date($symbol, $formatted_date, true);

        $this->assertNotNull($stocks);
        $this->assertEquals(3, count($stocks));

        foreach ($stocks as $stock){
            $this->assertEquals($symbol, $stock->symbol);
            $this->assertEquals('FUTIDX', $stock->instrument);
            $this->assertEquals($date, $stock->date);
        }
    }

    public function testDataProviderPriceChange(){
        $symbol = $this->symbol;
        $date = $this->date;

        $provider = new DataProvider();
        $formatted_date = Carbon::createFromFormat('Y-m-d', $date);
        $stock = $provider->get_cm_for_date($symbol, $formatted_date);
        $price_change = $provider->get_price_change($stock);

        $pct_change = (($stock->close - $stock->prevclose) * 100) / $stock->prevclose;
        $pct_change = round($pct_change, 2);
        $this->assertEquals($pct_change, $price_change);
    }

    public function testDataProviderCumFutureOpenInterest(){
        $symbol = $this->symbol;
        $date = $this->date;

        $provider = new DataProvider();
        $formatted_date = Carbon::createFromFormat('Y-m-d', $date);
        $futures = $provider->get_fo_for_date($symbol, $formatted_date, false);

        $cum_oi = $provider->get_cum_fut_oi($futures);

        $total_oi = 0;
        foreach ($futures as $f) $total_oi = $total_oi + $f->oi;

        $this->assertEquals($total_oi, $cum_oi);
    }

    public function testDataProviderPreviousTradingDay(){
        $provider = new DataProvider();
        $formatted_date = Carbon::createFromFormat('Y-m-d', '2020-01-20');

        $previous_day = $provider->get_previous_trading_day($formatted_date);

        $this->assertEquals('2020-01-17', $previous_day->format('Y-m-d'));

        $previous_day = $provider->get_previous_trading_day(Carbon::createFromFormat('Y-m-d', '2007-01-20'));
        $this->assertNull($previous_day);
    }

    public function testDataProviderChangeInCOI(){
        $provider = new DataProvider();
        $formatted_date = Carbon::createFromFormat('Y-m-d', '2020-01-20');

        $futures = $provider->get_fo_for_date($this->symbol, $formatted_date, false);
        $pct_coi_change = $provider->change_cum_fut_oi($futures);

        $current_day_coi_change = 0;
        foreach ($futures as $f){
            $current_day_coi_change += $f->change_in_oi;
        }

        $formatted_date = Carbon::createFromFormat('Y-m-d', '2020-01-17');
        $previous_day_futures = $provider->get_fo_for_date($this->symbol, $formatted_date, false);

        $previous_day_oi = 0;
        foreach ($previous_day_futures as $f){
            $previous_day_oi += $f->oi;
        }

        $coi_change = $current_day_coi_change;
        $coi_pct = (($coi_change * 100) / $previous_day_oi);
        $coi_pct = round($coi_pct, 2);

        $this->assertEquals($pct_coi_change, $coi_pct);
    }

}
