<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItenderItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itender_item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('itender_id');
            $table->foreign('itender_id')
                  ->references('id')
                  ->on("itender")
                  ->onDelete('cascade');
            $table->string('cpv');
            $table->text('tex_info')->nullable();
            $table->string('unit');
            $table->integer('count');
            $table->string('purchase_conditions');
            $table->string('terms_of_payment');
            $table->string('image')->nullable();
            $table->float('maximum_share_price');
            $table->float('total_maximum_share_price');
            $table->string('min_step');
            $table->string('min_allowable_price');
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
        Schema::dropIfExists('itender_item');
    }
}
