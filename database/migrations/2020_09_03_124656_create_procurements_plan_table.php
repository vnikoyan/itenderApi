<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcurementsPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procurement_plans', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('cpv_id');
            $table->foreign('cpv_id')
                  ->references('id')
                  ->on("cpv")
                  ->onDelete('cascade');

            $table->integer('cpv_drop')->nullable();

            $table->unsignedBigInteger('specifications_id')->nullable()->unsigned();

            $table->foreign('specifications_id')
                  ->references('id')
                  ->on("specifications")
                  ->onDelete('cascade');


            // $table->foreign('classifier_id')
            // ->references('id')
            // ->on("classifier")
            // ->onDelete('cascade');


            $table->unsignedBigInteger('organisation_id')->nullable()->unsigned();
            $table->foreign('organisation_id')
                  ->references('id')
                  ->on("users_state_organisation")
                  ->onDelete('cascade');

            $table->unsignedBigInteger('user_id_1')->nullable()->unsigned();
            $table->foreign('user_id_1')
                  ->references('id')
                  ->on("users")
                  ->onDelete('cascade');

            $table->unsignedBigInteger('user_id_2')->nullable()->unsigned();
            $table->foreign('user_id_2')
                  ->references('id')
                  ->on("users")
                  ->onDelete('cascade');

            $table->unsignedBigInteger('user_id_3')->nullable()->unsigned();
            $table->foreign('user_id_3')
                  ->references('id')
                  ->on("users")
                  ->onDelete('cascade');

            $table->unsignedBigInteger('user_id_4')->nullable()->unsigned();
            $table->foreign('user_id_4')
                  ->references('id')
                  ->on("users")
                  ->onDelete('cascade');

            $table->unsignedBigInteger('user_id_5')->nullable()->unsigned();
            $table->foreign('user_id_5')
                  ->references('id')
                  ->on("users")
                  ->onDelete('cascade');

            $table->unsignedBigInteger('procurement_id')->nullable()->unsigned();
            $table->foreign('procurement_id')
                  ->references('id')
                  ->on("procurements")
                  ->onDelete('cascade');



            $table->integer('status')->default(1);

            $table->timestamps();
        });
    }


            // $table->string('bank_account')->nullable();
            // $table->text('title');
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('procurement_plans');
    }
}
