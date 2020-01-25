<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class DropUnusedDatabases01 extends Migration{

    public function up()
    {
        $this->try_droping_table('bhavcopy_back_cm');
        $this->try_droping_table('bhavcopy_back_delv');
    }

    private function try_droping_table($table_name){
        Schema::dropIfExists($table_name);
    }

    public function down(){

    }
}
