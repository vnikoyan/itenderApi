<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCountFromCpvStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cpv_statistics', function (Blueprint $table) {
            DB::statement("ALTER TABLE cpv_statistics MODIFY column count double(12,2)");
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
