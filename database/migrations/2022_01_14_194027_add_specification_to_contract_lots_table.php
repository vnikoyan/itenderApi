<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSpecificationToContractLotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_lots', function (Blueprint $table) {
            $table->text('name');
            $table->text('specification');
            $table->text('delivery_date');
            $table->text('payment_date');
            $table->text('unit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contract_lots', function (Blueprint $table) {
            //
        });
    }
}
