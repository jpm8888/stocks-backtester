<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProcessedInBhavcopyCM extends Migration{

    public function up(){
        $table_name = 'bhavcopy_cm';
        Schema::table($table_name, function (Blueprint $table) {
            $table->boolean('v1_processed')->default(false)->comment('version_1 data is processed');
        });
    }

    public function down(){
        $table_name = 'bhavcopy_cm';
        Schema::table($table_name, function (Blueprint $table) {
            $table->dropColumn('v1_processed');
        });
    }
}
