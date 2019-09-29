<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogTable extends Migration
{
    public function up()
    {
        Schema::create('log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('log_type');
            $table->text('msg');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    public function down(){
        Schema::dropIfExists('log');
    }
}
