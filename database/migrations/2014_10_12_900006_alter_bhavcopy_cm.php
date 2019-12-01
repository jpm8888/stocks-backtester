<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBhavcopyCm extends Migration{
    public function up()
    {
        Schema::table("bhavcopy_cm", function (Blueprint $table) {
            $table->index('symbol');
        });

        Schema::table("bhavcopy_fo", function (Blueprint $table) {
            $table->index('instrument');
            $table->index('symbol');
            $table->index('expiry');
            $table->index('option_type');
        });
    }

    public function down(){
        Schema::table('bhavcopy_cm', function (Blueprint $table) {
            $table->dropIndex(['symbol']);
        });

        Schema::table("bhavcopy_fo", function (Blueprint $table) {
            $table->dropIndex(['instrument']);
            $table->dropIndex(['symbol']);
            $table->dropIndex(['expiry']);
            $table->dropIndex(['option_type']);
        });
    }
}
