<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipantGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participant_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('is_agency_agreement');
            $table->boolean('is_cooperation');
            $table->unsignedBigInteger('organize_id');
            $table->foreign('organize_id')
                ->references('id')
                ->on("organize")
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
        Schema::dropIfExists('participant_groups');
    }
}
