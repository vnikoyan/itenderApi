<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBankNameFieldToParticipantGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('participant_groups', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('address');
            $table->dropColumn('type');
            $table->string('bank');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('participant_groups', function (Blueprint $table) {
            $table->dropColumn('bank');
        });
    }
}
