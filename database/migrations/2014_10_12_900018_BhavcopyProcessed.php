<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BhavcopyProcessed extends Migration{

    public function up(){
        Schema::create("bhavcopy_processed", function (Blueprint $table) {
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
            $table->decimal('change_cum_fut_oi', 10, 2)->default(0)->comment('change fut oi');
            $table->decimal('cum_pe_oi', 10, 2)->default(0)->comment('coi in pe');
            $table->decimal('cum_ce_oi', 10, 2)->default(0)->comment('coi in pe');

            $table->decimal('change_cum_pe_oi', 10, 2)->default(0)->comment('change pe oi');
            $table->decimal('change_cum_ce_oi', 10, 2)->default(0)->comment('change ce oi');

            $table->decimal('pcr', 10, 2)->default(0)->comment('put call ratio');

            $table->decimal('max_ce_oi_strike', 10, 2)->default(0)->comment('max coi in strike');
            $table->decimal('max_pe_oi_strike', 10, 2)->default(0)->comment('max coi in strike');

            $table->bigInteger('avg_volume')->default(0)->comment('All time avg volume');
            $table->bigInteger('avg_volume_five')->default(0)->comment('Five day avg volume');
            $table->bigInteger('avg_volume_ten')->default(0)->comment('Ten day avg volume');
            $table->bigInteger('avg_volume_fifteen')->default(0)->comment('Fifteen day avg volume');

            $table->decimal('high_five', 10, 2)->default(0);
            $table->decimal('high_ten', 10, 2)->default(0);
            $table->decimal('high_fifteen', 10, 2)->default(0);
            $table->decimal('high_fiftytwo', 10, 2)->default(0);
            $table->decimal('ath', 10, 2)->default(0)->comment('all time high');

            $table->date('date')->nullable()->comment('bhavcopy date')->index();
        });
    }

    public function down(){
        Schema::dropIfExists("bhavcopy_processed");
    }
}
