<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasingProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchasing_process', function (Blueprint $table) {
            $table->bigIncrements('id');


            $table->unsignedBigInteger('procurement_plan_id');
            $table->foreign('procurement_plan_id')
                ->references('id')
                ->on("procurement_plans")
                ->onDelete('cascade');


            $table->unsignedBigInteger('purchasing_process_parent_id');
//            $table->foreign('purchasing_process_parent_id')
//                ->references('id')
//                ->on("purchasing_process_parent")
//                ->onDelete('cascade');

            $table->float('count');



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
        Schema::dropIfExists('purchasing_process');
    }
}
