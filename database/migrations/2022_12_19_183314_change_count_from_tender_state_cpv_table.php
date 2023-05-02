<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCountFromTenderStateCpvTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tender_state_cpv', function (Blueprint $table) {
            DB::statement("ALTER TABLE tender_state_cpv MODIFY column count TEXT");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tender_state_cpv', function (Blueprint $table) {
            //
        });
    }
}
