<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstrumentTable extends Migration
{
    public function up()
    {
        Schema::create('instrument', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('instrument_token');
            $table->string('exchange_token');
            $table->string('tradingsymbol');
            $table->string('name');
            $table->string('last_price');
            $table->string('expiry');
            $table->float('strike');
            $table->float('tick_size');
            $table->integer('lot_size');
            $table->string('instrument_type');
            $table->string('segment');
            $table->string('exchange');
        });
    }

    public function down()
    {
        Schema::dropIfExists('instrument');
    }
}
