<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTempFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('remarks')->nullable(true);
            $table->text('path');
            $table->bigInteger('added_by')->default(0);
            $table->boolean('is_used')->default(0);
            $table->boolean('is_deleted')->default(0);
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temp_files');
    }
}
