<?php

namespace Tests\Unit\DataProcessing;

use App\Console\Commands\DataProcessing\v1\DataProvider;
use Carbon\Carbon;
use Tests\TestCase;

class V1ProcessingTest extends TestCase
{
    public function testDataProviderFOStocks()
    {
        $provider = new DataProvider();
        $stocks = $provider->get_fo_stocks();
        $count = count($stocks);
        $this->assertTrue(($count > 0));
    }

    public function testDataProviderSymbol()
    {
        $symbol = 'AXISBANK';

        $provider = new DataProvider();
        $date = Carbon::createFromFormat('Y-m-d', '2008-01-01');
        $stock = $provider->get_cm_for_date($symbol, $date);

        $this->assertEquals($symbol, $stock->symbol);
        $this->assertEquals('EQ', $stock->series);
        $this->assertEquals('2008-01-01', $stock->date);
        $this->assertEquals(0, $stock->v1_processed);
    }




}
