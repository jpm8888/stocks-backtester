<?php
/**
 * User: jpm
 * Date: 21/01/20
 * Time: 7:51 pm
 */

namespace Tests\Unit\DataProcessing;


use App\Console\Commands\DataProcessing\v1\DataProvider;
use App\ModelBhavCopyCM;
use Carbon\Carbon;
use Tests\TestCase;

class V1ProcessingHighLowTest extends TestCase
{

    private $symbol = "AXISBANK";
    private $date = "2020-01-20";

    public function testDataProviderForHigh(){
        $symbol = $this->symbol;
        $date = $this->date;

        $provider = new DataProvider();
        $formatted_date = Carbon::createFromFormat('Y-m-d', $date);


        $high_5 = $provider->high_day_cm_5($symbol, $formatted_date);
        $high_10 = $provider->high_day_cm_10($symbol, $formatted_date);
        $high_15 = $provider->high_day_cm_15($symbol, $formatted_date);
        $high_52 = $provider->high_day_cm_52($symbol, $formatted_date);


        $this->assertEquals($high_5, $this->calc_high($symbol, $formatted_date, 5));
        $this->assertEquals($high_10, $this->calc_high($symbol, $formatted_date, 10));
        $this->assertEquals($high_15, $this->calc_high($symbol, $formatted_date, 15));
        $this->assertEquals($high_52, $this->calc_high($symbol, $formatted_date, 52));

    }

    private function calc_high($symbol, Carbon $date, $days){
        $max = 0;
        $high = ModelBhavCopyCM::ofSymbol($symbol)
            ->whereDate('date', '<', $date)
            ->orderBy('date', 'desc')
            ->limit($days)
            ->get();
        foreach ($high as $a){
            $max = max($max, $a->high);
        }
        return $max;
    }
}
