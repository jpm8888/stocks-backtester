<?php
/**
 * User: jpm
 * Date: 2020-03-21
 * Time: 14:28
 */

namespace App\Http\ExcelModels;

use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;


class DynamicExcelModel implements ToCollection, WithChunkReading, WithBatchInserts, WithStartRow, WithHeadingRow
{
    use Importable;

    /**
     * [
        'name'     => $row[0],
        'email'    => $row[1],
        'password' => Hash::make($row[2])
     * ]
     */

    private $tableName;
    private $map;
    private $fileType;
    private $hasTimestamp;
    public $errors = [];
    public $counter = 0;
    public function __construct($tableName, $map, $hasTimestamp = true, $fileType = 'csv'){
        $this->tableName = $tableName;
        $this->map = $map;
        $this->fileType = $fileType;
        $this->hasTimestamp = $hasTimestamp;
    }


    public function collection(Collection $rows){

        $index = 0;
        foreach ($rows as $row) {
            $values = [];
            $index++;
            try{
                foreach ($this->map as $m){
                    $columnName = $m['column_name'];
                    $mapName = $m['map_name'];
                    $datatype = $m['datatype'];

                    if ($this->fileType !== Excel::CSV && $datatype === 'd-m-Y'){
                        $values[$columnName] = Date::excelToDateTimeObject($row[$mapName]);
                    }else if ($datatype === 'd-m-Y'){
                        $values[$columnName] = Carbon::createFromFormat($datatype, $row[$mapName])->format('Y-m-d');
                    }else if ($datatype === 'date'){
                        $values[$columnName] = Carbon::parse($row[$mapName]);
                    }else{
                        $values[$columnName] = $row[$mapName];
                    }

                    if ($this->hasTimestamp) {
                        $values['created_at'] = Carbon::now();
                        $values['updated_at'] = Carbon::now();
                    }

                }
                $status = DB::table($this->tableName)->insert($values);
                if ($status) $this->counter++;
            }catch (Exception $e){
                $this->errors [] = [
                    'idx' => $index,
                    'error' => $e->getMessage(),
                ];
            }
        }
    }


    public function chunkSize(): int
    {
        return 1000;
    }


    public function batchSize(): int
    {
        return 1000;
    }

    public function startRow(): int
    {
        return 2;
    }

}
