<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUnitPriceToCpvStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cpv_statistics', function (Blueprint $table) {
            $table->double('estimated_price_unit', 12, 2)->after('estimated_price')->nullable();
        });

        Schema::table('cpv_statistics_participants', function (Blueprint $table) {
            $table->double('total_unit', 12, 2)->after('total');
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
