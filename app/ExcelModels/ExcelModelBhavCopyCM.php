<?php
/**
 * User: jpm
 * Date: 08/11/19
 * Time: 6:47 PM
 */

namespace App\ExcelModels;


use App\ModelBhavCopyCM;
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

        $bhavcopy_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['timestamp']);


        if ($bhavcopy_date == '1970-01-01') $expiry_date = null;

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
            'total_trades'           => $row['totaltrades'],
            'isin'                   => $row['isin'],
            'date'                   => $bhavcopy_date
        ]);
        return $model;
    }


    public function chunkSize(): int
    {
        return 3000;
    }


    public function batchSize(): int
    {
        return 3000;
    }


}
