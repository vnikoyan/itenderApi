<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateContractLotsTable.
 */
class CreateContractLotsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contract_lots', function(Blueprint $table) {
			$table->bigIncrements('id');
            $table->unsignedBigInteger('contract_id');
            $table->foreign('contract_id')
                ->references('id')
                ->on("contracts")
                ->onDelete('cascade');
            $table->unsignedBigInteger('organize_row_id');
            $table->foreign('organize_row_id')
                ->references('id')
                ->on("organize_row")
                ->onDelete('cascade');
            $table->float('total_price');
            $table->integer('ordered');
            $table->integer('supplied');
            $table->integer('available');
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
		Schema::drop('contract_lots');
	}
}
