<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUser extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('broker')->nullable();
            $table->string('user_id')->nullable();
            $table->string('access_token')->nullable();
            $table->string('public_token')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('broker');
            $table->dropColumn('user_id');
            $table->dropColumn('access_token');
            $table->dropColumn('public_token');
        });
    }
}
