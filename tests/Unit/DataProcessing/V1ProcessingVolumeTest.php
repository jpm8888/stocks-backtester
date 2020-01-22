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

class V1ProcessingVolumeTest extends TestCase
{

    private $symbol = "AXISBANK";
    private $date = "2020-01-20";

    public function testDataProviderForAvgVolumes(){
        $symbol = $this->symbol;
        $date = $this->date;

        $provider = new DataProvider();
        $formatted_date = Carbon::createFromFormat('Y-m-d', $date);


        $avg_vol_5 = $provider->avg_volume_cm_5($symbol, $formatted_date);
        $avg_vol_10 = $provider->avg_volume_cm_10($symbol, $formatted_date);
        $avg_vol_15 = $provider->avg_volume_cm_15($symbol, $formatted_date);
        $avg_vol_52 = $provider->avg_volume_cm_52($symbol, $formatted_date);


        $this->assertEquals($avg_vol_5, $this->avg_volume($symbol, $formatted_date, 5));
        $this->assertEquals($avg_vol_10, $this->avg_volume($symbol, $formatted_date, 10));
        $this->assertEquals($avg_vol_15, $this->avg_volume($symbol, $formatted_date, 15));
        $this->assertEquals($avg_vol_52, $this->avg_volume($symbol, $formatted_date, 52));
    }

    private function avg_volume($symbol, Carbon $date, $days){
        $sum = 0;
        $avg = ModelBhavCopyCM::ofSymbol($symbol)
            ->whereDate('date', '<', $date)
            ->orderBy('date', 'desc')
            ->limit($days)
            ->get();
        foreach ($avg as $a) $sum += $a->volume;
        return intval($sum / $days);
    }
}
