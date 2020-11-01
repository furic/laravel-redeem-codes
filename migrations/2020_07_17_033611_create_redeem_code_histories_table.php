<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRedeemCodeHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redeem_code_histories', function (Blueprint $table) {
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
        Schema::drop('redeem_code_histories');
    }
}
