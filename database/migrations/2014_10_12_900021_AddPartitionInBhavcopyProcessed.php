<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddPartitionInBhavcopyProcessed extends Migration{

    public function up(){
        $this->create_partition('bhavcopy_processed', 'date');
    }

    private function create_partition($table_name, $column_name){
        $query = "ALTER TABLE $table_name PARTITION BY RANGE(YEAR($column_name))";
        $query .= "(";
        for($i = 2007; $i < 2031; $i++){
            $year = $i + 1;
            $query .= "PARTITION p_$i VALUES LESS THAN ($year), ";
        }
        $query .= "PARTITION p_max_value VALUES LESS THAN(MAXVALUE));";

        DB::statement($query);
    }

    public function down(){

    }
}
