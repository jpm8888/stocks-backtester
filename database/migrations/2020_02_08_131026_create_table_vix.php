<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableVix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vix', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date')->index();
            $table->decimal('open', 10,4)->default(0);
            $table->decimal('high', 10,4)->default(0);
            $table->decimal('low', 10,4)->default(0);
            $table->decimal('close', 10,4)->default(0);
            $table->decimal('prevclose', 10,4)->default(0);
            $table->decimal('pct_change', 10,2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vix');
    }
}
