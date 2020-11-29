<?php

use Illuminate\Support\Facades\Route;
use Furic\RedeemCodes\Http\Controllers\RedeemController;

Route::get('/redeem-codes', [RedeemCodeController::class, 'index'])->name('redeem-codes.index');
Route::post('/redeem-codes', [RedeemCodeController::class, 'create'])->name('redeem-codes.create');
Route::delete('/redeem-codes', [RedeemCodeController::class, 'delete'])->name('redeem-codes.delete');