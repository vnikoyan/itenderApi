<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToCpvStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cpv_statistics', function (Blueprint $table) {
            $table->index('cpv_id');
            $table->index('region_id');
            $table->index('unit_id');
        });

        Schema::table('tender_state_cpv', function (Blueprint $table) {
            $table->index('cpv_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cpv_statistics', function (Blueprint $table) {
            //
        });
    }
}
