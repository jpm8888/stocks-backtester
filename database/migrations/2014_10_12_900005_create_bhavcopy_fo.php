<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBhavCopyFO extends Migration
{
    public function up()
    {
        Schema::create("bhavcopy_fo", function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('instrument')->nullable();
            $table->string('symbol')->nullable();
            $table->date('expiry')->nullable();
            $table->double('strike_price')->nullable();
            $table->string('option_type')->nullable();
            $table->double('open')->nullable();
            $table->double('high')->nullable();
            $table->double('low')->nullable();
            $table->double('close')->nullable();
            $table->bigInteger('contracts')->nullable();
            $table->double('total_trade_val')->nullable()->comment('Total Traded Value in Lacs');
            $table->double('oi')->nullable()->comment('Open Interest');
            $table->double('change_in_oi')->nullable();
            $table->date('date')->nullable()->comment('bhavcopy date');
        });
    }

    public function down(){
        Schema::dropIfExists("bhavcopy_fo");
    }
}
