<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOnePersonFieldsToOrganizeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organize', function (Blueprint $table) {
            $table->string('shipping_address');
            $table->string('other_requirements');
            $table->integer('purchase_schedule')->default(2);
            $table->dateTime('opening_date_time');

            $table->boolean('winner_by_lots')->default(false);
            $table->boolean('send_to_all_participants')->default(true);
            $table->boolean('publicize')->default(true);

            $table->integer('protocols_copy_number')->nullable();
            $table->integer('protocol_presentation_deadline')->nullable();
            $table->integer('work_type')->nullable();
            $table->string('calendar_schedule')->nullable();
            $table->integer('least_work_percent')->nullable();
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
            $table->dropColumn('shipping_address');
            $table->dropColumn('other_requirements');
            $table->dropColumn('purchase_schedule');
            $table->dropColumn('opening_date_time');
            $table->dropColumn('winner_by_lots');
            $table->dropColumn('send_to_all_participants');
            $table->dropColumn('publicize');
            $table->dropColumn('protocols_copy_number');
            $table->dropColumn('protocol_presentation_deadline');
            $table->dropColumn('work_type');
            $table->dropColumn('calendar_schedule');
            $table->dropColumn('least_work_percent');
        });
    }
}
