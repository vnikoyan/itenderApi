<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrganisationIdToOrderStateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_state', function (Blueprint $table) {
            $table->dropForeign(["user_id"]);
            $table->dropColumn('user_id');
            $table->integer("organisation_id");
            $table->integer("quantity");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_state', function (Blueprint $table) {
            //
        });
    }
}
