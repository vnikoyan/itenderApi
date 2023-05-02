<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPersonalFieldsToParticipantGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('participant_groups', function (Blueprint $table) {
            $table->string('name');
            $table->string('address');
            $table->string('account_number');
            $table->string('director');
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
            $table->dropColumn('name');
            $table->dropColumn('address');
            $table->dropColumn('account_number');
            $table->dropColumn('director');
        });
    }
}
