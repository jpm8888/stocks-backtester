<?php
/**
 * User: jpm
 * Date: 08/11/19
 * Time: 6:47 PM
 */

namespace App\ExcelModels;


use App\ModelBhavCopy;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;

class ExcelModelBhavCopy implements ToModel, WithChunkReading, WithBatchInserts, WithHeadingRow, WithProgressBar {

    use Importable;
    public function model(array $row)
    {
        $oi = intval($row['open_interest']);
        $delta_oi = intval($row['change_in_open_interest']);
        $delta_oi_pct = 0;

        try{
            $delta_oi_pct = ($delta_oi * 100) / ($oi - $delta_oi);
        }catch (\Exception $e){
            $delta_oi_pct = 0;
        }


        $expiry_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['expiry_date']);
        $bhavcopy_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['bhavcopy_date']);

        $model = new ModelBhavCopy([
            'segment'               => $row['segment'],
            'instrument_type'       => $row['instrument_type'],
            'symbol'                => $row['symbol'],
            'expiry_date'           => $expiry_date,
            'strike'                => $row['strike_price'],
            'option_type'           => $row['option_type'],
            'previous_close'        => $row['previous_close_price'],
            'o'                     => $row['open_price'],
            'h'                     => $row['high_price'],
            'l'                     => $row['low_price'],
            'c'                     => $row['closing_price'],
            'ft_h'                  => $row['52_week_high_price'],
            'ft_l'                  => $row['52_week_low_price'],
            'volume'                => $row['qty_contracts_traded'],
            'value_in_lacs'         => $row['value_in_lacs'],
            'oi'                    => $oi,
            'delta_oi'              => $delta_oi,
            'delta_oi_pct'          => $delta_oi_pct,
            'bhavcopy_date'         => $bhavcopy_date,
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
