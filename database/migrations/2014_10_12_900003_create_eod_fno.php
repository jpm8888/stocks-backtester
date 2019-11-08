<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEodFno extends Migration
{
    public function up()
    {
        Schema::create("eod_fno", function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('symbol');
            $table->date('expiry_date');
            $table->integer('strike');
            $table->string('option_type');
            $table->bigInteger('value_in_lacs');
            $table->bigInteger('oi');
            $table->bigInteger('delta_oi');
            $table->float('delta_oi_pct');
            $table->string('eod_date');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    public function down(){
        Schema::dropIfExists("eod_fno");
    }
}
