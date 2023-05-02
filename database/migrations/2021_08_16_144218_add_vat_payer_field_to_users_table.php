<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVatPayerFieldToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('vat_payer_type', [
                'payer', // ԱԱՀ վճարող *
                'not_payer', // ԱԱՀ չվճարող *
                'payer_with_but' // ԱԱՀ-ով աշխատող, սակայն հաղթողը որոշվում է հատուցման գումարով *
            ])->default("not_payer");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('vat_payer_type');
        });
    }
}
