<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvoiceDetailsToContractOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_orders', function (Blueprint $table) {
            $table->string('invoice_number');           // Ֆակտուրայի համար
            $table->date('discharge_date');             // Դուրս գրման ամսաթիվ
            $table->date('delivery_date');              // Մատակարարման ամսաթիվ
            $table->date('completion_contract_date');   // Գնման առարկայի մակատարման ժամկետը ըստ պայմանագրով հաստատված գնման ժամանակացույցի 
            $table->date('completion_actual_date');     // Գնման առարկայի մակատարման ժամկետը փաստացի
            $table->date('payment_date');             // Վճարման ժամկետը
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
            $table->dropColumn('invoice_number');
            $table->dropColumn('discharge_date');
            $table->dropColumn('delivery_date');
            $table->dropColumn('completion_contract_date');
            $table->dropColumn('completion_actual_date');
            $table->dropColumn('payment_date');
        });
    }
}
