<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateBhavcopyProcessed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bhavcopy_processed', function (Blueprint $table) {
            $table->unsignedBigInteger('id', false)->index();

            $table->string('symbol', 40)->nullable()->index();
            $table->string('series', 40)->nullable()->index();

            $table->decimal('open', 10, 2)->nullable();
            $table->decimal('high', 10, 2)->nullable();
            $table->decimal('low', 10, 2)->nullable();
            $table->decimal('close', 10, 2)->nullable();
            $table->decimal('prevclose', 10, 2)->nullable();
            $table->decimal('price_change', 10, 2)->default(0)->comment('price change in percent from previous day close.');

            $table->bigInteger('volume')->nullable()->comment('Total Traded Quantity');
            $table->bigInteger('dlv_qty')->nullable()->comment('Delivered Quantity');
            $table->decimal('pct_dlv_traded', 10, 2)->nullable()->comment('Pct Quantity Delivered');

            $table->bigInteger('cum_fut_oi')->default(0)->comment('coi = expiry of three_months oi');
            $table->bigInteger('change_cum_fut_oi_val')->default(0)->comment('change in cumulative oi in futures by value');
            $table->decimal('change_cum_fut_oi', 10, 2)->default(0)->comment('change fut oi');
            $table->bigInteger('cum_pe_oi')->default(0)->comment('coi in pe');
            $table->bigInteger('change_cum_pe_oi_val')->default(0)->comment('change in cumulative oi in option by value');
            $table->bigInteger('cum_ce_oi')->default(0)->comment('coi in pe');
            $table->bigInteger('change_cum_ce_oi_val')->default(0)->comment('change in cumulative oi in option by value');

            $table->decimal('change_cum_pe_oi', 10, 2)->default(0)->comment('change pe oi');
            $table->decimal('change_cum_ce_oi', 10, 2)->default(0)->comment('change ce oi');

            $table->decimal('pcr', 10, 2)->default(0)->comment('put call ratio');

            $table->decimal('max_ce_oi_strike', 10, 2)->default(0)->comment('max coi in strike');
            $table->decimal('max_pe_oi_strike', 10, 2)->default(0)->comment('max coi in strike');


            $table->bigInteger('avg_volume_five')->default(0)->comment('Five day avg volume');
            $table->bigInteger('avg_volume_ten')->default(0)->comment('Ten day avg volume');
            $table->bigInteger('avg_volume_fifteen')->default(0)->comment('Fifteen day avg volume');

            $table->bigInteger('avg_volume_fiftytwo')->default(0)->comment('Fifty two avg volume');

            $table->decimal('low_five', 10, 2)->default(0);
            $table->decimal('low_ten', 10, 2)->default(0);
            $table->decimal('low_fifteen', 10, 2)->default(0);
            $table->decimal('low_fiftytwo', 10, 2)->default(0);


            $table->decimal('high_five', 10, 2)->default(0);
            $table->decimal('high_ten', 10, 2)->default(0);
            $table->decimal('high_fifteen', 10, 2)->default(0);
            $table->decimal('high_fiftytwo', 10, 2)->default(0);

            $table->date('date')->nullable()->comment('bhavcopy date')->index();
            $table->tinyInteger('v1_processed')->default(0);

            $table->primary(['id', 'date']);
        });

        DB::statement('ALTER TABLE `bhavcopy_processed` CHANGE `id` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;');

        $this->create_partition('bhavcopy_processed', 'date');
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
        Schema::dropIfExists('bhavcopy_processed');
    }
}
