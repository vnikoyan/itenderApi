<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewCololmToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // $table->string('company_type')->nullable();  // tr 

            // $table->text('nickname')->nullable();  // tr 
            // $table->text('region')->nullable();  // tr 
            // $table->text('city')->nullable();  // tr 

            // $table->string('bank_name')->nullable();  // tr 
            // $table->string('bank_account')->nullable();  
            // $table->text('director_name')->nullable();  // tr 

            // nickname
            //
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
            //
        });
    }
}
