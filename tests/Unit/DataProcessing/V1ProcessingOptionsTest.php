<?php
/**
 * User: jpm
 * Date: 21/01/20
 * Time: 7:51 pm
 */

namespace Tests\Unit\DataProcessing;


use App\Console\Commands\DataProcessing\v1\DataProvider;
use Carbon\Carbon;
use Tests\TestCase;

class V1ProcessingOptionsTest extends TestCase
{

    private $symbol = "AXISBANK";
    private $date = "2020-01-20";

    public function testDataProviderCEIndex(){
        $symbol = "BANKNIFTY";
        $date = $this->date;

        $provider = new DataProvider();
        $formatted_date = Carbon::createFromFormat('Y-m-d', $date);
        $stocks = $provider->get_op_ce_for_date($symbol, $formatted_date, true);

        $this->assertNotNull($stocks);

        foreach ($stocks as $stock){
            $this->assertEquals($symbol, $stock->symbol);
            $this->assertEquals('CE', $stock->option_type);
            $this->assertEquals('OPTIDX', $stock->instrument);
            $this->assertEquals($date, $stock->date);
        }
    }

    public function testDataProviderCEStock(){
        $symbol = $this->symbol;
        $date = $this->date;

        $provider = new DataProvider();
        $formatted_date = Carbon::createFromFormat('Y-m-d', $date);
        $stocks = $provider->get_op_ce_for_date($symbol, $formatted_date, false);

        $this->assertNotNull($stocks);

        foreach ($stocks as $stock){
            $this->assertEquals($symbol, $stock->symbol);
            $this->assertEquals('CE', $stock->option_type);
            $this->assertEquals('OPTSTK', $stock->instrument);
            $this->assertEquals($date, $stock->date);
        }
    }
}
