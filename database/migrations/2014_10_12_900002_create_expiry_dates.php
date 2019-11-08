<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpiryDates extends Migration
{
    public function up()
    {
        Schema::create('expiry_dates', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->date('expiry_date');
            $table->enum('type', ['weekly', 'monthly'])->comment('weekly/monthly');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    public function down(){
        Schema::dropIfExists('expiry_dates');
    }
}
