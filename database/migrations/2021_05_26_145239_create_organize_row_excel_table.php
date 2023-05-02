<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizeRowExcelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organize_row_excel', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('view_id');

            $table->unsignedBigInteger('organize_id');
            $table->foreign('organize_id')
                ->references('id')
                ->on("organize")
                ->onDelete('cascade');

            $table->unsignedBigInteger('cpv_id');
            $table->foreign('cpv_id')
                    ->references('id')
                    ->on("cpv")
                    ->onDelete('cascade');

            $table->bigInteger('cpv_code');

            $table->string('specification');
            $table->string('cpv_name');
            $table->string('unit');

            $table->float('total_price');
            $table->float('unit_amount');

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
        Schema::dropIfExists('organize_row_excel');
    }
}
