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
            $table->string('instrument_token')->nullable();
            $table->string('exchange_token')->nullable();
            $table->string('tradingsymbol')->nullable();
            $table->string('name')->nullable();
            $table->string('last_price')->nullable();
            $table->string('expiry')->nullable();
            $table->float('strike')->nullable();
            $table->float('tick_size')->nullable();
            $table->integer('lot_size')->nullable();
            $table->string('instrument_type')->nullable();
            $table->string('segment')->nullable();
            $table->string('exchange')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('instrument');
    }
}
