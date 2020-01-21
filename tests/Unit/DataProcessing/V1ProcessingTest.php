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



}
