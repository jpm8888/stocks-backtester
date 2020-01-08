<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DeleteIds extends Migration{
    public function up()
    {
        $this->delete_primary_key('bhavcopy_cm');
        $this->delete_primary_key('bhavcopy_fo');
        $this->delete_primary_key('bhavcopy_delv_position');
    }

    private function delete_primary_key($table_name){
        if (Schema::hasColumn($table_name, 'id')){
            DB::statement("ALTER TABLE $table_name MODIFY id INT NOT NULL;");
            DB::statement("ALTER TABLE $table_name DROP PRIMARY KEY;");
            DB::statement("ALTER TABLE $table_name DROP COLUMN id;");
        }
    }

    public function down(){

    }
}
