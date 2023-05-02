<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSelectedParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('selected_participants', function (Blueprint $table) {
            $table->bigIncrements('id');


            $table->unsignedBigInteger('organize_row_id');
            $table->foreign('organize_row_id')
                ->references('id')
                ->on("organize_row")
                ->onDelete('cascade');

            $table->unsignedBigInteger('participant_group_id');
//            $table->foreign('participant_group_id')
//                ->references('group_id')
//                ->on("participant_data")
//                ->onDelete('cascade');

            $table->string('bank'); //
            $table->string('hh'); //
            $table->string('director_full_name'); //

            $table->string('name'); //
            $table->string('manufacturer_name'); //
            $table->string('country_of_origin'); //

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
        Schema::dropIfExists('selected_participants');
    }
}
