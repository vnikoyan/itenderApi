<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ConnectingTenderId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        // Schema::table('user_corresponding_tenders', function (Blueprint $table) {
        //     $table->foreign('tender_id')
        //         ->references('id')
        //         ->on("tender_state")
        //         ->onDelete('cascade');
        //     $table->foreign('user_id')
        //         ->references('id')
        //         ->on("users")
        //         ->onDelete('cascade');
        // });


        Schema::table('tender_state_cpv', function (Blueprint $table) {
            $table->dropIndex(['tender_state_id']);
            $table->foreign('tender_state_id')
                ->references('id')
                ->on("tender_state")
                ->onDelete('cascade');
        });
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
