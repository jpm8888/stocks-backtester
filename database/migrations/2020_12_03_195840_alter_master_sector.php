<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMasterSector extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_sector', function (Blueprint $table) {
            $table->decimal('nf_weight')->default(0);
            $table->decimal('bnf_weight')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('master_sector', function (Blueprint $table) {
            $table->dropColumn('nf_weight');
            $table->dropColumn('bnf_weight');
        });
    }
}
