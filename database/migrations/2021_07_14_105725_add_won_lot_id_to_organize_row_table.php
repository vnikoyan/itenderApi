<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWonLotIdToOrganizeRowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organize_row', function (Blueprint $table) {
            $table->unsignedBigInteger('won_lot_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organize_row', function (Blueprint $table) {
            $table->dropColumn('won_lot_id');
        });
    }
}
