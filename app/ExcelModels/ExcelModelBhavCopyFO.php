<?php
/**
 * User: jpm
 * Date: 08/11/19
 * Time: 6:47 PM
 */

namespace App\ExcelModels;


use App\ModelBhavCopyFO;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;

class ExcelModelBhavCopyFO implements ToModel, WithChunkReading, WithBatchInserts, WithHeadingRow, WithProgressBar {

    use Importable;
    public function model(array $row)
    {

        $expiry_date = Carbon::parse($row['expiry_dt']);
        $bhavcopy_date = Carbon::parse($row['timestamp']);

        $model = new ModelBhavCopyFO([
            'instrument'               => $row['instrument'],
            'symbol'                   => $row['symbol'],
            'expiry'                   => $expiry_date,
            'strike_price'             => $row['strike_pr'],
            'option_type'              => $row['option_typ'],
            'open'                     => $row['open'],
            'high'                     => $row['high'],
            'low'                      => $row['low'],
            'close'                    => $row['close'],
            'contracts'                => $row['contracts'],
            'total_trade_val'          => $row['val_inlakh'],
            'oi'                       => $row['open_int'],
            'change_in_oi'             => $row['chg_in_oi'],
            'date'                     => $bhavcopy_date,
        ]);
        return $model;
    }


    public function chunkSize(): int
    {
        return 4000;
    }


    public function batchSize(): int
    {
        return 4000;
    }


}
