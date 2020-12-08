<?php

use Illuminate\Support\Facades\Route;
use Furic\RedeemCodes\Http\Controllers\RedeemController;

Route::get('redeem/{code}', [RedeemController::class, 'redeem'])->name('redeem-codes.redeem');