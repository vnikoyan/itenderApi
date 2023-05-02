<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                  ->references('id')
                  ->on("users")
                  ->onDelete('cascade');

            $table->unsignedBigInteger('package_id');
            $table->foreign('package_id')
                  ->references('id')
                  ->on("packages")
                  ->onDelete('cascade');

            $table->dateTime('strat_date');
            $table->dateTime('end_date');
            $table->string('payment_method');
            $table->float('amount_paid');
            $table->enum('type', ['ACTIVE','SUSPENDED','PASSIVE'])->default("ACTIVE");
            $table->softDeletes();
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
        Schema::dropIfExists('order');
    }
}
