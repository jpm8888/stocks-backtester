<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBhavCopyCM extends Migration
{
    public function up()
    {
        Schema::create("bhavcopy_cm", function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('symbol')->nullable();
            $table->string('series')->nullable();
            $table->double('open')->nullable();
            $table->double('high')->nullable();
            $table->double('low')->nullable();
            $table->double('close')->nullable();
            $table->double('prevclose')->nullable();
            $table->bigInteger('volume')->nullable()->comment('Total Traded Quantity');
            $table->double('total_trade_val')->nullable()->comment('Total Traded Value in Lacs');
            $table->double('total_trades')->nullable()->comment('Total Trades');
            $table->string('isin')->nullable();
            $table->date('date')->nullable()->comment('bhavcopy date');
        });
    }

    public function down(){
        Schema::dropIfExists("bhavcopy_cm");
    }
}
