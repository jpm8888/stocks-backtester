<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBhavcopyProcessed01 extends Migration{

    public function up(){
        $table_name = 'bhavcopy_processed';

        if (Schema::hasColumn($table_name, 'avg_volume')){
            Schema::table($table_name, function (Blueprint $table) {
                $table->dropColumn('avg_volume');
            });
        }

        Schema::table($table_name, function (Blueprint $table) {
            $table->bigInteger('avg_volume_fiftytwo')->after('avg_volume_fifteen')->default(0)->comment('Fifty two avg volume');

            $table->decimal('low_five', 10, 2)->default(0)->after('avg_volume_fiftytwo');
            $table->decimal('low_ten', 10, 2)->default(0)->after('low_five');
            $table->decimal('low_fifteen', 10, 2)->default(0)->after('low_ten');
            $table->decimal('low_fiftytwo', 10, 2)->default(0)->after('low_fifteen');
            $table->decimal('atl', 10, 2)->default(0)->comment('all time low')->after('low_fiftytwo');

        });
    }

    public function down(){
        $table_name = 'bhavcopy_processed';
        Schema::table($table_name, function (Blueprint $table) {
            $table->dropColumn('avg_volume_fiftytwo');
            $table->dropColumn('low_five');
            $table->dropColumn('low_ten');
            $table->dropColumn('low_fifteen');
            $table->dropColumn('low_fiftytwo');
            $table->dropColumn('atl');
        });
    }
}
