<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWinnerLotDetailsToOrganizeRowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organize_row', function (Blueprint $table) {
            $table->text('winner_lot_trademark');
            $table->text('winner_lot_brand');
            $table->text('winner_lot_manufacturer');
            $table->text('winner_lot_specification');
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
            $table->dropColumn('winner_lot_name');
            $table->dropColumn('winner_lot_brand');
            $table->dropColumn('winner_lot_manufacturer');
            $table->dropColumn('winner_lot_specification');
        });
    }
}
