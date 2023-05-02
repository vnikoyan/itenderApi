<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_filters', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('user_id')->default(1);
            $table->foreign('user_id')
                  ->references('id')
                  ->on("users")
                  ->onDelete('cascade');

            $table->text('status')->nullable();
            $table->text('type')->nullable();
            $table->text('procedure')->nullable();
            $table->text('region')->nullable();
            $table->text('organizator')->nullable();
            $table->text('isElectronic')->nullable();
            $table->text('guaranteed')->nullable();

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
        Schema::dropIfExists('user_filters');
    }
}
