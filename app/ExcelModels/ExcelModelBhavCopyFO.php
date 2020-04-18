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

    var $fo_stocks = null;
    private $mode;
    public function __construct($mode, $fo_stocks){
        $this->mode = $mode;
        $this->fo_stocks = $fo_stocks;
    }

    use Importable;
    public function model(array $row)
    {

        if ($this->mode == 'fo'){
            $val = $this->is_present(trim($row['symbol']));
            if (!$val) return null;
        }


        if(intval($row['open_int']) == 0 && intval($row['chg_in_oi']) == 0) return null;

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

    function is_present($symbol){
        return (in_array($symbol, $this->fo_stocks)) ? true : false;
    }

}
