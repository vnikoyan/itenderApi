<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenderStateCpvTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tender_state_cpv', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('cpv_id');


            $table->unsignedBigInteger('tender_state_id');
            $table->foreign('tender_state_id')
                ->references('id')
                ->on("tender_state")
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
        Schema::dropIfExists('tender_state_cpv');
    }
}
