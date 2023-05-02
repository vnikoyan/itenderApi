<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSpecificationIdToCpvStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cpv_statistics', function (Blueprint $table) {
            $table->unsignedBigInteger('specification_id');
            $table->foreign('specification_id')
                ->references('id')
                ->on("specifications");
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
