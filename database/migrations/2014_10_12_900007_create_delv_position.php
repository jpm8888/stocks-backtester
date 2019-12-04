<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDelvPosition extends Migration{
    public function up()
    {
        Schema::create("bhavcopy_delv_position", function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('symbol')->index();
            $table->double('traded_qty')->comment('Quantity Traded');
            $table->double('dlv_qty')->comment('Deliverable Quantity(gross across client level)');
            $table->double('pct_dlv_traded')->comment('% of Deliverable Quantity to Traded Quantity');
            $table->date('date');
        });
    }

    public function down(){
        Schema::dropIfExists("bhavcopy_delv_position");
    }
}
