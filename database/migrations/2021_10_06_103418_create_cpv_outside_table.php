<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpvOutsideTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpv_outside', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->string('name');
            $table->string('name_ru')->nullable();
            $table->string('unit');
            $table->string('unit_ru')->nullable();
            $table->timestamps();
        });

        Schema::table('procurement_plans', function (Blueprint $table) {
            $table->boolean('is_from_outside')->default(false);
            $table->unsignedBigInteger('cpv_outside_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cpv_outsides');
    }
}
