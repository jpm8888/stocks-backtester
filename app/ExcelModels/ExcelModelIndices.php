<?php
/**
 * User: jpm
 * Date: 08/11/19
 * Time: 6:47 PM
 */

namespace App\ExcelModels;


use App\ModelIndices;
use App\ModelVix;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;

class ExcelModelIndices implements ToModel, WithChunkReading, WithBatchInserts, WithHeadingRow, WithProgressBar {

    use Importable;
    private $symbol;

    public function __construct($symbol)
    {
        $this->symbol = $symbol;
    }

    public function model(array $row)
    {

        $date = Carbon::parse($row['date']);

        if (ModelIndices::where('date', $date)->where('symbol', $this->symbol)->count() > 0){
            return null;
        }

        $open = floatval($row['open']);
        $high = floatval($row['high']);
        $low = floatval($row['low']);
        $close = floatval($row['close']);
        $volume = trim($row['volume']);
        $turnover = floatval($row['turnover']);

        $model = new ModelIndices([
            'date'                   => $date,
            'open'                   => $open,
            'high'                   => $high,
            'low'                    => $low,
            'close'                  => $close,
            'volume'                 => $volume,
            'turnover'               => $turnover,
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
