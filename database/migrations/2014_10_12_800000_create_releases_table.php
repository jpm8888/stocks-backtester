<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReleasesTable extends Migration
{
    public function up()
    {
        Schema::create('releases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('build');
            $table->text('msg');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    public function down(){
        Schema::dropIfExists('releases');
    }
}
