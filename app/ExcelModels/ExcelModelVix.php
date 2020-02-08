<?php
/**
 * User: jpm
 * Date: 08/11/19
 * Time: 6:47 PM
 */

namespace App\ExcelModels;


use App\ModelVix;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;

class ExcelModelVix implements ToModel, WithChunkReading, WithBatchInserts, WithHeadingRow, WithProgressBar {

    use Importable;
    public function model(array $row)
    {

        $date = Carbon::parse($row['date']);

        if (ModelVix::where('date', $date)->count() > 0){
            return null;
        }

        $open = floatval($row['open']);
        $high = floatval($row['high']);
        $low = floatval($row['low']);
        $close = floatval($row['close']);
        $prevclose = floatval($row['prev_close']);

        $pct_change = 0;
        if ($prevclose != 0){
            $pct_change = round((($close - $prevclose) * 100) / $prevclose, 2);
        }

        $model = new ModelVix([
            'date'                   => $date,
            'open'                   => $open,
            'high'                   => $high,
            'low'                    => $low,
            'close'                  => $close,
            'prevclose'              => $prevclose,
            'pct_change'             => $pct_change,
        ]);
        return $model;
    }


    public function chunkSize(): int
    {
        return 2000;
    }


    public function batchSize(): int
    {
        return 2000;
    }


}
