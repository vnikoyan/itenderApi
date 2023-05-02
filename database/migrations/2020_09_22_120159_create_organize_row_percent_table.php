<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizeRowPercentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organize_row_percent', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('organize_row_id');
            $table->foreign('organize_row_id')
                ->references('id')
                ->on("organize_row")
                ->onDelete('cascade');
            $table->string('name'); //
            $table->float('month_1')->nullable();
            $table->float('month_2')->nullable();
            $table->float('month_3')->nullable();
            $table->float('month_4')->nullable();
            $table->float('month_5')->nullable();
            $table->float('month_6')->nullable();
            $table->float('month_7')->nullable();
            $table->float('month_8')->nullable();
            $table->float('month_9')->nullable();
            $table->float('month_10')->nullable();
            $table->float('month_11')->nullable();
            $table->float('month_12')->nullable();
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
        Schema::dropIfExists('organize_row_percent');
    }
}
