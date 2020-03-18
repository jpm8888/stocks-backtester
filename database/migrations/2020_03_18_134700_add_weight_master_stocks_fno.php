<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddWeightMasterStocksFno extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table_name = "master_stocks_fo";
        Schema::table($table_name, function (Blueprint $table) {
            $table->integer('weight')->default(3);
        });

        DB::table('master_stocks_fo')
            ->where('symbol', 'NIFTY')
            ->update(['weight' => 1]);

        DB::table('master_stocks_fo')
            ->where('symbol', 'BANKNIFTY')
            ->update(['weight' => 2]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
