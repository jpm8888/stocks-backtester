<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBhavCopyCombined extends Migration
{
    public function up()
    {
        Schema::create("bhavcopy_combined", function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('segment')->index('eod_fno_segment')->nullable();
            $table->string('instrument_type')->index('eod_instrument_type')->nullable();
            $table->string('symbol')->nullable();
            $table->date('expiry_date')->nullable();
            $table->integer('strike')->nullable();
            $table->string('option_type')->nullable();
            $table->float('previous_close')->nullable();
            $table->float('o')->comment('open')->nullable();
            $table->float('h')->comment('high')->nullable();
            $table->float('l')->comment('low')->nullable();
            $table->float('c')->comment('close')->nullable();
            $table->float('ft_h')->comment('52 week high')->nullable();
            $table->float('ft_l')->comment('52 week low')->nullable();
            $table->bigInteger('volume')->comment('qty contracts traded')->nullable();
            $table->bigInteger('value_in_lacs')->nullable();
            $table->bigInteger('oi')->nullable();
            $table->bigInteger('delta_oi')->nullable();
            $table->float('delta_oi_pct')->nullable();
            $table->date('bhavcopy_date')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    public function down(){
        Schema::dropIfExists("bhavcopy_combined");
    }
}
