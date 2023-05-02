<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrgToProcurementPlanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procurement_plans_details', function (Blueprint $table) {
            $table->unsignedBigInteger('organisation_id')->nullable()->unsigned();
            $table->foreign('organisation_id')
                  ->references('id')
                  ->on("users_state_organisation")
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('procurement_plan_details', function (Blueprint $table) {
            //
        });
    }
}
