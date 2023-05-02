<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteColumnsFromOrderStateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_state', function (Blueprint $table) {
            $table->dropForeign(['package_id_one_person']);
            $table->dropForeign(['package_id_competitive']);
            $table->dropColumn('package_id_one_person');
            $table->dropColumn('package_id_competitive');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_state', function (Blueprint $table) {
            //
        });
    }
}
