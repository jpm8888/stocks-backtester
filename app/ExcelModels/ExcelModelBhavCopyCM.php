<?php
/**
 * User: jpm
 * Date: 08/11/19
 * Time: 6:47 PM
 */

namespace App\ExcelModels;


use App\ModelBhavCopyCM;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;

class ExcelModelBhavCopyCM implements ToModel, WithChunkReading, WithBatchInserts, WithHeadingRow, WithProgressBar {

    use Importable;
    public function model(array $row)
    {

        $bhavcopy_date = Carbon::parse($row['timestamp']);

        $model = new ModelBhavCopyCM([
            'symbol'                 => $row['symbol'],
            'series'                 => $row['series'],
            'open'                   => $row['open'],
            'high'                   => $row['high'],
            'low'                    => $row['low'],
            'close'                  => $row['close'],
            'prevclose'              => $row['prevclose'],
            'volume'                 => $row['tottrdqty'],
            'total_trade_val'        => $row['tottrdval'],
            'date'                   => $bhavcopy_date
        ]);
        return $model;
    }


    public function chunkSize(): int
    {
        return 3500;
    }


    public function batchSize(): int
    {
        return 3500;
    }


}
