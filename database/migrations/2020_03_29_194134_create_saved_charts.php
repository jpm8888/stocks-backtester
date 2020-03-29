<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavedCharts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saved_charts', function (Blueprint $table) {
            $table->bigIncrements('chart_id');
            $table->string('name');
            $table->string('symbol')->index();
            $table->string('resolution');
            $table->text('content');
            $table->bigInteger('user_id')->index();
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saved_charts');
    }
}
