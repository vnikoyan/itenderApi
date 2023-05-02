<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassifierCpvTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classifier_cpv', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cpv_id');
            $table->foreign('cpv_id')
                  ->references('id')
                  ->on("cpv")
                  ->onDelete('cascade');
            $table->unsignedBigInteger('classifier_id');
            $table->foreign('classifier_id')
                  ->references('id')
                  ->on("classifier")
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
        Schema::dropIfExists('classifier_cpv');
    }
}
