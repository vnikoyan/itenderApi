<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDefinedRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('defined_requirements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('value');
            $table->text('valueOrder');
            $table->integer('order')->default(0);
            $table->unsignedBigInteger('cpv_id');
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
        Schema::dropIfExists('defined_requirements');
    }
}
