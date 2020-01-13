<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBhavcopyCM01 extends Migration{
    public function up()
    {
        $table_name = 'bhavcopy_cm';
        if (Schema::hasColumn($table_name, 'total_trades')){
            Schema::table($table_name, function (Blueprint $table) {
                $table->dropColumn('total_trades');
            });
        }

        if (Schema::hasColumn($table_name, 'isin')){
            Schema::table($table_name, function (Blueprint $table) {
                $table->dropColumn('isin');
            });
        }
    }

    public function down(){

    }
}
