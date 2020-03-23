<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('symbol')->index();
            $table->decimal('open', 10,2)->default(0);
            $table->decimal('high', 10,2)->default(0);
            $table->decimal('low', 10,2)->default(0);
            $table->decimal('close', 10,2)->default(0);
            $table->unsignedBigInteger('volume')->default(0);
            $table->decimal('turnover', 10,2)->default(0)->comment('in Cr');
            $table->date('date')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('indices');
    }
}
