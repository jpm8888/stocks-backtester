<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBhavcopyProcessed04 extends Migration
{

    public function up()
    {
        $this->add_column();
    }


    private function add_column(){
        $table_name = "bhavcopy_processed";
        Schema::table($table_name, function (Blueprint $table) {
            $table->bigInteger('change_cum_pe_oi_val')->default(0)->after('cum_pe_oi')->comment('change in cumulative oi in option by value');
            $table->bigInteger('change_cum_ce_oi_val')->default(0)->after('cum_ce_oi')->comment('change in cumulative oi in option by value');
            $table->tinyInteger('v1_processed')->default(0)->after('date');
        });

    }

    public function down()
    {

    }

}
