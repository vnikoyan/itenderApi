<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPassportFieldsToUsersStateOrganisationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_state_organisation', function (Blueprint $table) {
            $table->string('passport_serial_number')->nullable();
            $table->string('passport_given_at')->nullable();
            $table->string('passport_from')->nullable();
            $table->string('passport_valid_until')->nullable();
            $table->string('id_card_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_state_organisation', function (Blueprint $table) {
            $table->dropColumn('passport_serial_number');
            $table->dropColumn('passport_given_at');
            $table->dropColumn('passport_from');
            $table->dropColumn('passport_valid_until');
            $table->dropColumn('id_card_number');
        });
    }
}
