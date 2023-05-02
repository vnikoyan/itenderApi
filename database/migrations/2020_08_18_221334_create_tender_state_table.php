<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenderStateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tender_state', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('title');
            $table->text('link')->nullable();
            $table->dateTime('start_date',0);
            $table->dateTime('end_date',0);
            $table->text('cpv');

            $table->string('ministry')->nullable();
            $table->string('state_institution')->nullable();
            $table->string('regions');
            $table->enum('type', ['ELECTRONIC','PAPER']);
            $table->integer('tender_type');
            $table->integer('is_million10')->nullable();
            $table->integer('is_competition')->nullable();
            $table->integer('is_new')->nullable();
            $table->integer('is_closed')->nullable();
            
            $table->float('estimated')->nullable();
            $table->string('estimated_file')->nullable();
            $table->string('customer_name');
            $table->string('password');
            $table->enum('procedure', ["GH", "BM", "UBA", "HMA"]);
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
        Schema::dropIfExists('tender_state');
    }
}
