<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')
                ->references('id')
                ->on("users")
                ->onDelete('cascade');
            $table->unsignedBigInteger('provider_id');
            $table->foreign('provider_id')
                ->references('id')
                ->on("participant_groups")
                ->onDelete('cascade');
            $table->unsignedBigInteger('organize_id');
            $table->foreign('organize_id')
                ->references('id')
                ->on("organize")
                ->onDelete('cascade');
            $table->string('code');
            $table->date('sign_date');
            $table->string('name')->nullable();
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
        Schema::dropIfExists('contracts');
    }
}
