<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserWatchlistTable extends Migration
{
    public function up()
    {
        Schema::create('user_watchlist', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->string('watchlist_name');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_watchlist');
    }
}
