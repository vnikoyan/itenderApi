<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractOrderLotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_order_lots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contract_order_id');
            $table->foreign('contract_order_id')
                ->references('id')
                ->on("contract_orders")
                ->onDelete('cascade');
            $table->unsignedBigInteger('contract_lot_id');
            $table->foreign('contract_lot_id')
                ->references('id')
                ->on("contract_lots")
                ->onDelete('cascade');
            $table->integer('ordered');
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
        Schema::dropIfExists('contract_order_lots');
    }
}
