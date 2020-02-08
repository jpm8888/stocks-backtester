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

        $model = new ModelVix([
            'date'                   => $date,
            'open'                   => $row['open'],
            'high'                   => $row['high'],
            'low'                    => $row['low'],
            'close'                  => $row['close'],
            'prevclose'              => floatval($row['prev_close']),
            'pct_change'             => $row['change'],
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
