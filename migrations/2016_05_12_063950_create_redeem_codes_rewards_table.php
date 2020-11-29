<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRedeemCodesRewardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redeem_code_rewards', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('redeem_code_id')->unsigned()->nullable();
            $table->integer('event_id')->unsigned()->nullable();
            $table->tinyInteger('type')->unsigned()->nullable();
            $table->integer('amount')->unsigned()->default(1);
            $table->integer('item_id')->unsigned()->nullable();

            $table->timestamps();

            $table->foreign('redeem_code_id')->references('id')->on('redeem_codes')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('redeem_code_rewards');
    }
}
