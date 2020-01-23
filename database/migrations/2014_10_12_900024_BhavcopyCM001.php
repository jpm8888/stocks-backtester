<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BhavcopyCM001 extends Migration{

    public function up(){
        Schema::create("bhavcopy_cm", function (Blueprint $table) {
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
            $table->date('date')->default('1991-05-27')->index()->comment('bhavcopy date');
            $table->tinyInteger('v1_processed')->index()->default(0)->comment('version_1 data is processed');

            $table->primary(['id', 'date']);
        });

        Schema::create("bhavcopy_delv_position", function (Blueprint $table) {

            $table->unsignedBigInteger('id', false)->index();
            $table->string('symbol', 40)->index();
            $table->string('series', 40)->nullable()->index();
            $table->double('traded_qty')->comment('Quantity Traded');
            $table->double('dlv_qty')->comment('Deliverable Quantity(gross across client level)');
            $table->decimal('pct_dlv_traded', 10, 2)->comment('% of Deliverable Quantity to Traded Quantity');
            $table->date('date')->default('1991-05-27')->index();

            $table->primary(['id', 'date']);
        });

        DB::statement('ALTER TABLE `bhavcopy_cm` CHANGE `id` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;');
        DB::statement('ALTER TABLE `bhavcopy_delv_position` CHANGE `id` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;');
    }

    public function down(){
        Schema::dropIfExists("bhavcopy_cm");
        Schema::dropIfExists("bhavcopy_delv_position");
    }
}
