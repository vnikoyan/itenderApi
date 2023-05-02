<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasingProcessParentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchasing_process_parent', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('organisation_id');
            $table->foreign('organisation_id')
                ->references('id')
                ->on("users_state_organisation")
                ->onDelete('cascade');

            $table->string('title');

            $table->string('code');
            $table->string('address');
            $table->string('other_requirements');

            $table->integer('is_full_decide');
            $table->integer('is_all_participants');
            $table->integer('timetable');
            $table->date('deadline');

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
        Schema::dropIfExists('purchasing_process_parent');
    }
}
