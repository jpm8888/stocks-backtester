<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTableDataTypes extends Migration{
    public function up()
    {
        Schema::table('bhavcopy_cm', function (Blueprint $table) {
            $table->string('symbol', 40)->change();
            $table->string('series', 40)->change();
            $table->decimal('open', 10, 2)->change();
            $table->decimal('high', 10, 2)->change();
            $table->decimal('low', 10, 2)->change();
            $table->decimal('close', 10, 2)->change();
            $table->decimal('prevclose', 10, 2)->change();
            $table->string('isin', 50)->change();
            $table->index('date');
            $table->index('series');
        });


        Schema::table('bhavcopy_delv_position', function (Blueprint $table) {
            $table->string('symbol', 40)->change();
            $table->string('series', 40)->change();
            $table->decimal('pct_dlv_traded', 10, 2)->change();

            $table->index('date');
            $table->index('series');
        });


        Schema::table('bhavcopy_fo', function (Blueprint $table) {
            $table->string('instrument', 40)->change();
            $table->string('symbol', 40)->change();
            $table->decimal('strike_price', 10, 2)->change();
            $table->string('option_type', 4)->change();

            $table->decimal('open', 10, 2)->change();
            $table->decimal('high', 10, 2)->change();
            $table->decimal('low', 10, 2)->change();
            $table->decimal('close', 10, 2)->change();

            $table->index('date');
        });

    }

    public function down(){
        Schema::table('bhavcopy_cm', function (Blueprint $table) {
            $table->dropIndex(['date']);
            $table->dropIndex(['series']);
        });

        Schema::table('bhavcopy_delv_position', function (Blueprint $table) {
            $table->dropIndex(['date']);
            $table->dropIndex(['series']);
        });

        Schema::table('bhavcopy_fo', function (Blueprint $table) {
            $table->dropIndex(['date']);
        });
    }
}
