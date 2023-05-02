<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWinnerUserIdToOrganizeRowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organize_row', function (Blueprint $table) {
            $table->unsignedBigInteger('winner_user_id');
        });

        Schema::table('organize', function (Blueprint $table) {
            $table->unsignedBigInteger('winner_user_id');
            $table->float('winner_user_price');
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
            $table->dropColumn('winner_user_id');
        });

        Schema::table('organize', function (Blueprint $table) {
            $table->dropColumn('winner_user_id');
            $table->dropColumn('winner_user_price');
        });
    }
}
