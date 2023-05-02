<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpvStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpv_statistics', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('cpv_id');
            $table->foreign('cpv_id')
                  ->references('id')
                  ->on("cpv")
                  ->onDelete('cascade');

            $table->unsignedBigInteger('region_id');
            $table->foreign('region_id')
                  ->references('id')
                  ->on("regions");
            
            $table->unsignedBigInteger('unit_id');
            $table->foreign('unit_id')
                ->references('id')
                ->on("units");

            $table->text('specification');
            $table->text('specification_ru');
            $table->integer('count')->nullable();
            $table->date('winner_get_date');
            $table->boolean('established')->nullable();
            $table->double('estimated_price', 12, 2)->nullable();
            $table->enum('failed_substantiation', ['not_match_conditions', 'not_requirement_purchase', 'no_submitted_application', 'no_contract_signed'])->nullable();
            $table->double('plan_summary', 12, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cpv_statistics');
    }
}
