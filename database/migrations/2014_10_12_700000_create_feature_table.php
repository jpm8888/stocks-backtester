<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeatureTable extends Migration
{
    public function up()
    {
        Schema::create('feature_request', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('msg');
            $table->bigInteger('added_by');
            $table->tinyInteger('status')->default(0);
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    public function down(){
        Schema::dropIfExists('feature_request');
    }
}
