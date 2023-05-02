<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipantDataRowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participant_data_row', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('row_group_id')->unsigned()->nullable();
//TODO
//            $table->foreign('row_group_id')
//                ->references('group_id')
//                ->on("participant_data");

            $table->unsignedBigInteger('organize_row_id');
            $table->foreign('organize_row_id')
                ->references('id')
                ->on("organize_row")
                ->onDelete('cascade');


            $table->float('cost'); // Ինքնարժեք
            $table->float('profit'); // Շահույթ
            $table->float('value'); // Արժեք
            $table->integer('vat'); // ԱԱՀ


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
        Schema::dropIfExists('participant_data_row');
    }
}
