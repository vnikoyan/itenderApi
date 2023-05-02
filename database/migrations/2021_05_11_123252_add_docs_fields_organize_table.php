<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDocsFieldsOrganizeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organize', function($table) {
            $table->boolean('get_evaluation_session')->default(false);
            $table->boolean('get_invitation')->default(false);
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
            $table->dropColumn('get_evaluation_session');
            $table->dropColumn('get_invitation');
        });
    }
}
