<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerificationLogsTable extends Migration
{

    public function up()
    {
        Schema::create('verification_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('delv_id')->default(0);
            $table->string('symbol')->index();
            $table->date('date');
            $table->longText('msg');
            $table->tinyInteger('resolved')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('verification_logs');
    }
}
