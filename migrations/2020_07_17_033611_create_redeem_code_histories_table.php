<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRedeemCodeHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redeem_code_histories', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('redeem_code_id')->unsigned()->index();
            $table->string('ip', 15);
            $table->text('agent');
            $table->timestamps();
            
            $table->foreign('redeem_code_id')->references('id')->on('redeem_codes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('redeem_code_histories');
    }
}
