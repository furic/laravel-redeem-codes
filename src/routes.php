<?php
Route::get('/redeem/{code}', 'RedeemController@redeem'); // API
Route::get('/redeem-codes', 'RedeemCodeController@index'); // Web console
Route::post('/redeem-codes', 'RedeemCodeController@create');
Route::delete('/redeem-codes', 'RedeemCodeController@delete');