<?php

use App\ModelMasterStocksFO;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ColumnAddMasterStocksFno extends Migration
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
            $table->integer('has_volume')->default(1);
            $table->integer('has_fo')->default(1);
            $table->integer('has_delivery')->default(1);
        });

        DB::table('master_stocks_fo')
            ->where('symbol', 'NIFTY')
            ->update(['has_volume' => 0, 'has_fo' => 1, 'has_delivery' => 0]);

        DB::table('master_stocks_fo')
            ->where('symbol', 'BANKNIFTY')
            ->update(['has_volume' => 0, 'has_fo' => 1, 'has_delivery' => 0]);


        $model = new ModelMasterStocksFO();
        $model->symbol = 'VIX';
        $model->sector_id = '99';
        $model->in_nf = '0';
        $model->in_bnf = '0';
        $model->weight = '2';
        $model->has_volume = '0';
        $model->has_fo = '0';
        $model->has_delivery = '0';
        $model->save();

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
