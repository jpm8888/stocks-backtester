<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class RenameTables extends Migration{

    public function up(){
        Schema::rename('bhavcopy_cm', 'bhavcopy_back_cm');
        Schema::rename('bhavcopy_delv_position', 'bhavcopy_back_delv');
    }

    public function down(){

    }
}
