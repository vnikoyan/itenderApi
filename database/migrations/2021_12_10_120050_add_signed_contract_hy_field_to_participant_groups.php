<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSignedContractHyFieldToParticipantGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('participant_groups', function (Blueprint $table) {
            $table->longText('signed_contract_hy')->nullable();
            $table->longText('signed_contract_ru')->nullable();
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
            //
        });
    }
}
