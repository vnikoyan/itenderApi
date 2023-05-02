<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsDocsSatisfactoryToParticipantDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('participant_data', function (Blueprint $table) {
            $table->boolean('is_docs_satisfactory')->default(true);
            $table->boolean('price_offer_exists')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('participant_data', function (Blueprint $table) {
            $table->dropColumn('is_docs_satisfactory');
            $table->dropColumn('price_offer_exists');
        });
    }
}
