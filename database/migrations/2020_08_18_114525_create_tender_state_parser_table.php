<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenderStateParserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tender_state_parser', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('title');
            $table->text('link')->nullable();
            $table->dateTime('start_date',0);
            $table->dateTime('end_date',0);
            $table->bigInteger('cpv')->nullable();
            $table->float('estimated')->nullable();
            $table->bigInteger('type');
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
        Schema::dropIfExists('tender_state_parser');
    }
}
