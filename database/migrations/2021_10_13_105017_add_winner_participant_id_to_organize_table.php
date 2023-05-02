<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWinnerParticipantIdToOrganizeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organize', function (Blueprint $table) {
            $table->unsignedBigInteger('winner_participant_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organize', function (Blueprint $table) {
            $table->dropColumn('winner_participant_id');
        });
    }
}
