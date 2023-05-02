<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewValueToTenderStateTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tender_state', function (Blueprint $table) {
            DB::statement("ALTER TABLE tender_state MODIFY type ENUM('ELECTRONIC','PAPER','ELECTRONIC AUCTION')");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tender_state_type', function (Blueprint $table) {
            //
        });
    }
}
