<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateIndices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indices', function (Blueprint $table) {
            $table->unsignedBigInteger('id', false)->index();
            $table->string('symbol')->index();
            $table->decimal('open', 10,2)->default(0);
            $table->decimal('high', 10,2)->default(0);
            $table->decimal('low', 10,2)->default(0);
            $table->decimal('close', 10,2)->default(0);
            $table->decimal('prevclose', 10,2)->default(0);
            $table->unsignedBigInteger('volume')->default(0);
            $table->decimal('turnover', 10,2)->default(0)->comment('in Cr');
            $table->date('date')->index();
            $table->tinyInteger('v1_processed')->default(0)->index();
        });


        DB::statement('ALTER TABLE `indices` CHANGE `id` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;');

        $this->create_partition('indices', 'date');
    }


    private function create_partition($table_name, $column_name){
        $query = "ALTER TABLE $table_name PARTITION BY RANGE(YEAR($column_name))";
        $query .= "(";
        for($i = 2007; $i < 2031; $i++){
            $year = $i + 1;
            $query .= "PARTITION p_$i VALUES LESS THAN ($year), ";
        }
        $query .= "PARTITION p_max_value VALUES LESS THAN(MAXVALUE));";

        DB::statement($query);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('indices');
    }
}
