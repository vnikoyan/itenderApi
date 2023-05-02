<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPurchaseScheduleTypeToOrganizeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organize', function (Blueprint $table) {
            $table->boolean('one_time_transactions')->default(true);
            $table->enum('purchase_periodicity', ["custom", 'monthly', 'quarterly', 'semi_annual'])->default("custom");
            $table->integer('purchase_count');
            $table->enum('delivery_type', [
                "without_delivery",
                "by_participant_resources_participant", 
                "by_participant_resources_organizer", 
                "by_organizer_resources_participant", 
            ])->default("by_participant_resources_participant");
            $table->string('delivery_address');
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
            //
        });
    }
}
