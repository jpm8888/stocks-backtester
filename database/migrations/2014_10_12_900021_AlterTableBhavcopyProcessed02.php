<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableBhavcopyProcessed02 extends Migration{

    public function up(){
        $table_name = "bhavcopy_processed";
        if (Schema::hasColumn($table_name, 'ath')){
            Schema::table($table_name, function (Blueprint $table) {
                $table->dropColumn('ath');
            });
        }

        if (Schema::hasColumn($table_name, 'atl')){
            Schema::table($table_name, function (Blueprint $table) {
                $table->dropColumn('atl');
            });
        }
    }

    public function down(){

    }
}
