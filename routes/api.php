<?php

use Illuminate\Support\Facades\Route;
use Furic\RedeemCodes\Http\Controllers\RedeemController;

Route::prefix('api')->group(function() {
    Route::get('redeem/{code}', [RedeemController::class, 'redeem'])->name('redeem-codes.redeem');
});