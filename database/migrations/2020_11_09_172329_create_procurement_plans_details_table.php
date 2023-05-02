<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcurementPlansDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procurement_plans_details', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('budget_type')->default(0);//
            $table->float('count');
            $table->float('unit_amount');
            $table->integer('type');
            $table->integer('classifier_id');
            $table->string('unit')->nullable();

            $table->integer('financial_classifier_id');

            $table->unsignedBigInteger('procurement_plans_id');
            $table->foreign('procurement_plans_id')
                  ->references('id')
                  ->on("procurement_plans")
                  ->onDelete('cascade');


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
        Schema::dropIfExists('procurement_plans_details');
    }
}
