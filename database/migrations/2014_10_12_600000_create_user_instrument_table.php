<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInstrumentTable extends Migration
{
    public function up()
    {
        Schema::create('user_instrument', function (Blueprint $table) {
            $table->bigInteger('user_watchlist_id');
            $table->bigInteger('instrument_token');
        });
    }

    public function down(){
        Schema::dropIfExists('user_instrument');
    }
}
