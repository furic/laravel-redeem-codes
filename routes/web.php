<?php

use Illuminate\Support\Facades\Route;
use Furic\RedeemCodes\Http\Controllers\RedeemCodeController;

Route::resource('redeem-codes', RedeemCodeController::class);