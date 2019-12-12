<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBhavcopyDelvPosition extends Migration{
    public function up()
    {
        Schema::table('bhavcopy_delv_position', function (Blueprint $table) {
            $table->string('series')->after('symbol')->nullable();
        });
    }

    public function down(){
        Schema::table('bhavcopy_delv_position', function (Blueprint $table) {
            $table->dropColumn('series');
        });
    }
}
