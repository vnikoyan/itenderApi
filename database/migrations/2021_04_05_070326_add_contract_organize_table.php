<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContractOrganizeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organize', function($table) {
            $table->longText('contract_html_hy');
            $table->longText('contract_html_ru');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organize', function($table) {
            $table->dropColumn('contract_html_hy');
            $table->dropColumn('contract_html_ru');
        });
    }
}
