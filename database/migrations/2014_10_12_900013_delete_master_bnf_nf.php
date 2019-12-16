<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class DeleteMasterBnfNf extends Migration{
    public function up()
    {
        Schema::dropIfExists("master_bank_nifty");
        Schema::dropIfExists("master_nifty");
    }

    public function down(){

    }
}
