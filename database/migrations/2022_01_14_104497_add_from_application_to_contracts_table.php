<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFromApplicationToContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->boolean('from_application')->default(false);
            $table->unsignedBigInteger('contract_client_id')->nullable();
        });

        Schema::create('contract_client', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name");
            $table->string("tin");
            $table->string("bank");
            $table->string("address");
            $table->string("director");
            $table->string("account_number");
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_to_contracts', function (Blueprint $table) {
            //
        });
    }
}
