<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumsToAdmins extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->enum('status', ['ACTIVE', 'BLOCK'])->default("BLOCK");
            $table->string('tin')->nullable(); // The taxpayer identification number (TIN)  Հարկ վճարողի նույնականացման համարը ( ՀՎՀՀ )
        });
        // 5․Կատեգորիա
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
