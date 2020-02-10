<?php
/**
 * User: jpm
 * Date: 09/02/20
 * Time: 11:54 am
 */

namespace App\Console\Commands\DataProcessing\tests;


use App\ModelMasterStocksFO;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteUselessFOEntries extends Command
{
    protected $signature = 'delete:useless_data';
    protected $description = 'delete fo entries based on master stocks fo table';

    public function __construct(){
        parent::__construct();
    }

    public function handle(){
        $arr = ModelMasterStocksFO::get()->pluck('symbol')->toArray();
        if (count($arr) == 0) return;

        $start_time = microtime(true);

        $this->clean('bhavcopy_cm');
        $this->clean('bhavcopy_fo');
        $this->clean('bhavcopy_delv_position');


        $end_time = microtime(true);
        $execution_time = round(($end_time - $start_time), 2);

        $this->info('all done for now : ' . $execution_time / 60 . ' minutes');
    }


    private function clean($table_name){
        $partition_names = DB::select(DB::raw("SELECT DISTINCT PARTITION_NAME as name FROM INFORMATION_SCHEMA.PARTITIONS WHERE TABLE_NAME='$table_name';"));
        foreach ($partition_names as $p){
            $this->info(Carbon::now() . ": cleaning $table_name partition : $p->name");
            DB::statement("delete from $table_name partition ($p->name) where symbol not in (select symbol from master_stocks_fo)");
        }
    }
}
