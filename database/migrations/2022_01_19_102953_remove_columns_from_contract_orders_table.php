<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RemoveColumnsFromContractOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_orders', function (Blueprint $table) {
            DB::statement('ALTER TABLE contract_orders Modify column completion_actual_date varchar(10);  ');
            DB::statement('ALTER TABLE contract_orders Modify column discharge_date varchar(10);  ');
            $table->dropColumn('delivery_date');
            $table->dropColumn('completion_contract_date');
            $table->dropColumn('payment_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contract_orders', function (Blueprint $table) {
            //
        });
    }
}
