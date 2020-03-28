<?php
/**
 * User: jpm
 * Date: 08/11/19
 * Time: 6:47 PM
 */

namespace App\ExcelModels;


use App\ModelIndices;
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
        $volume = intval($row['shares_traded']);
        $turnover = floatval($row['turnover_rs_cr']);


        $data = ModelIndices::where('symbol', $this->symbol)->whereDate('date', '<', $date)->orderBy('date', 'desc')->first();

        $prevclose = ($data) ? $data->close : 0;


        $model = new ModelIndices([
            'symbol'                 => $this->symbol,
            'date'                   => $date,
            'open'                   => $open,
            'high'                   => $high,
            'low'                    => $low,
            'close'                  => $close,
            'prevclose'              => $prevclose,
            'volume'                 => $volume,
            'turnover'               => $turnover,
        ]);

        return $model;
    }


    // do not change this, else prevclose will not be calculated.
    public function chunkSize(): int
    {
        return 1;
    }

    // do not change this, else prevclose will not be calculated.
    public function batchSize(): int
    {
        return 1;
    }


}
