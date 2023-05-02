<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpvStatisticsParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpv_statistics_participants', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('cpv_statistics_id');
            $table->foreign('cpv_statistics_id')
                  ->references('id')
                  ->on("cpv_statistics")
                  ->onDelete('cascade');
            
            $table->text('name');
            $table->text('name_ru');

            $table->double('value', 12, 2);
            $table->double('vat', 12, 2);
            $table->double('total', 12, 2);
            $table->boolean('is_winner');

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
        Schema::dropIfExists('cpv_statistics_participants');
    }
}
