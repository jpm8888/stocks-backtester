<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBhavcopyProcessed03 extends Migration
{

    public function up()
    {
        $this->add_column();
    }


    private function add_column(){
        $table_name = "bhavcopy_processed";
        Schema::table($table_name, function (Blueprint $table) {
            $table->bigInteger('change_cum_fut_oi_val')->default(0)->after('cum_fut_oi')->comment('change in cumulative oi in futures by value');
        });

    }

    public function down()
    {

    }

}
