<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('name', ['Անվճար','Էկոնոմ','Պրեմիում','Գոլդ'])->unique()->default("Անվճար");
            $table->float('price_1')->nullable();
            $table->float('price_3')->nullable();
            $table->float('price_6')->nullable();
            $table->float('price_12')->nullable();
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
        Schema::dropIfExists('packages');
    }
}
