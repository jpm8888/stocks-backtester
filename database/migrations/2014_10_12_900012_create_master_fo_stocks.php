<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterFoStocks extends Migration{
    public function up()
    {
        Schema::create("master_stocks_fo", function (Blueprint $table) {
            $table->increments('id');
            $table->string('symbol')->index();
            $table->string('sector_id')->index()->default(99);
            $table->boolean('in_nf')->default(false);
            $table->boolean('in_bnf')->default(false);
        });

        Schema::create("master_sector", function (Blueprint $table) {
            $table->increments('id');
            $table->string('sector');
        });
    }

    public function down(){
        Schema::dropIfExists("master_stocks_fo");
        Schema::dropIfExists("master_sector");
    }
}
