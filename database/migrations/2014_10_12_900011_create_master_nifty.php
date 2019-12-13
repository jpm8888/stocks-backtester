<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterNifty extends Migration{
    public function up()
    {
        Schema::create("master_nifty", function (Blueprint $table) {
            $table->increments('id');
            $table->string('symbol')->index();
        });
    }

    public function down(){
        Schema::dropIfExists("master_nifty");
    }
}
