<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTendersTableConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenders_table_config', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("user_id");
            $table->boolean("viewed");
            $table->boolean("password");
            $table->boolean("title");
            $table->boolean("organizator");
            $table->boolean("products");
            $table->boolean("opening_finish_date");
            $table->boolean("price");
            $table->boolean("region")->default(false);
            $table->boolean("type")->default(false);
            $table->boolean("application");
            $table->boolean("invitation");
            $table->boolean("favorite");
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
        Schema::dropIfExists('tenders_table_config');
    }
}
