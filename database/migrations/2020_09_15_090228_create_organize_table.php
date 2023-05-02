<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organize', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('procurement_id');
            $table->foreign('procurement_id')
                ->references('id')
                ->on("procurements")
                ->onDelete('cascade');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on("users")
                ->onDelete('cascade');

            $table->integer('organize_type'); /// enum add model
            $table->date('text_approval_date');
            $table->integer('decision_number');
            $table->text('name');
            $table->string('code');
            $table->date('public_date');
            $table->integer('submission_date');
            $table->date('opening_date');
            $table->time('opening_time');

            $table->integer('prepayment');
            $table->float('prepayment_max');
            $table->string('prepayment_time');

            $table->integer('paper_fee');
            $table->float('fee');
            $table->string('account_number');

            $table->text('evaluator_president')->nullable();; //Նախագահ
            $table->text('evaluator_secretary_name')->nullable();; //Քարտուղար
            $table->text('evaluator_secretary_email')->nullable();; //Քարտուղար
            $table->text('evaluator_secretary_phone')->nullable();; //Քարտուղար
            $table->text('evaluator_member')->nullable();; //Անդամ

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
        Schema::dropIfExists('organize');
    }
}
