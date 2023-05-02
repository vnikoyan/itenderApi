<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeOpeningFinishFateFromTendersTableConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tenders_table_config', function (Blueprint $table) {
            DB::statement('ALTER TABLE tenders_table_config Modify column opening_finish_date tinyint(1);');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenders_table_config', function (Blueprint $table) {
            //
        });
    }
}
