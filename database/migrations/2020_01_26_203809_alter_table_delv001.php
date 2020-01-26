<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableDelv001 extends Migration
{

    public function up()
    {
        $this->delete_column();
        $this->add_column();
    }

    private function delete_column(){
        $table_name = "bhavcopy_cm";
        if (Schema::hasColumn($table_name, 'v1_processed')){
            Schema::table($table_name, function (Blueprint $table) {
                $table->dropColumn('v1_processed');
            });
        }
    }

    private function add_column(){
        $table_name = "bhavcopy_delv_position";
        if (!Schema::hasColumn($table_name, 'v1_processed')){
            Schema::table($table_name, function (Blueprint $table) {
                $table->unsignedTinyInteger('verified')->default(0);
                $table->unsignedTinyInteger('v1_processed')->default(0);
            });
        }
    }

    public function down()
    {

    }

}
