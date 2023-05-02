<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipantDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participant_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('organize_id');
            $table->foreign('organize_id')
                ->references('id')
                ->on("organize")
                ->onDelete('cascade');

            $table->bigInteger('group_id')->unsigned();

            $table->string('tin')->nullable();
            $table->string('name');
            $table->string('address');
            $table->string('email');
            $table->string('phone');
            $table->date('date_of_submission');

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
        Schema::dropIfExists('participant_data');
    }
}
