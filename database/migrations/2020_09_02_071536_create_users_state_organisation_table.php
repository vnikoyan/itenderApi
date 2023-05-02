<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersStateOrganisationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_state_organisation', function (Blueprint $table) {
            $table->bigIncrements('id');


            $table->string('tin');           // tr 
            $table->longText('name');           // tr 
            $table->string('company_type');  // tr 

            $table->string('phone');   // tr 
            $table->text('nickname');       // tr 
            $table->text('region');        // tr 
            $table->text('city');         // tr 
            $table->text('address');     // tr 

            $table->string('bank_name');  // tr 
            $table->string('bank_account');  
            $table->text('director_name');  // tr 
            $table->float('balans')->default("0");
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            
            $table->unsignedBigInteger('parent_id')->nullable()->unsigned();
            
            $table->foreign('parent_id')
                  ->references('id')
                  ->on("users_state_organisation")
                  ->onDelete('cascade');

        });
        
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_state_organisation');
    }
}
