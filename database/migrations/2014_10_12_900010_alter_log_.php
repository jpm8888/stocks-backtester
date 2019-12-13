<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLog extends Migration{
    public function up()
    {
        Schema::table('log', function (Blueprint $table) {
            $table->bigInteger('added_by')->after('log_type')->default(1);
        });
    }

    public function down(){
        Schema::table('log', function (Blueprint $table) {
            $table->dropColumn('added_by');
        });
    }
}
