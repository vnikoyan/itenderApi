<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizeParentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organize_row', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('organize_id');
            $table->foreign('organize_id')
                ->references('id')
                ->on("organize")
                ->onDelete('cascade');

            $table->unsignedBigInteger('procurement_plan_id');
            $table->foreign('procurement_plan_id')
                ->references('id')
                ->on("procurement_plans")
                ->onDelete('cascade');

            $table->float('count');
            $table->float('supply');
            $table->integer('supply_date');
            $table->integer('is_main_tool');
            $table->integer('is_collateral_requirement');
            $table->integer('is_product_info'); // Մասնակցի կողմից առաջարկվող ապրանքի, ապրանքային նշանի, ֆիրմային անվանման, մակնիշի և արտադրողի անվանման և ծագման երկրի վերաբերյալ տեղեկատվության ներկայացում Նշել բոլորը
            // Գնահատող հանձնաժողովի
            $table->string('shipping_address'); // Մատակարարման հասցեն

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
        Schema::dropIfExists('organize_parent');
    }
}
