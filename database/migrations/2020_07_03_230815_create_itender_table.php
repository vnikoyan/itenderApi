<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItenderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itender', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->string('cpv');
            
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                  ->references('id')
                  ->on("users")
                  ->onDelete('cascade');

            $table->integer('tiem');
            $table->integer('count');
            $table->enum('type', ['COMPLEAT','NEW','APPROVED','REJECTED'])->default("NEW");
            $table->text('rejected')->nullable();
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
        Schema::dropIfExists('itender');
    }
}
