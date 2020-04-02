<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateBhavcopyNseCm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bhavcopy_nse_cm', function (Blueprint $table) {
            $table->unsignedBigInteger('id', false)->index();
            $table->string('symbol', 40)->index()->nullable();
            $table->string('series', 40)->index()->nullable();
            $table->decimal('open', 10, 2)->nullable();
            $table->decimal('high', 10, 2)->nullable();
            $table->decimal('low', 10, 2)->nullable();
            $table->decimal('close', 10, 2)->nullable();
            $table->decimal('prevclose', 10, 2)->nullable();
            $table->bigInteger('volume')->nullable()->comment('Total Traded Quantity');
            $table->double('total_trade_val')->nullable()->comment('Total Traded Value in Lacs');
            $table->date('date')->index()->comment('bhavcopy date');
        });

        DB::statement('ALTER TABLE `bhavcopy_nse_cm` CHANGE `id` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;');
        $this->create_partition('bhavcopy_nse_cm', 'date');
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
        Schema::dropIfExists('bhavcopy_nse_cm');
    }
}
