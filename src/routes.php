<?php
$app->get('/redeem/{code}', 'RedeemController@redeem'); // API
$app->get('/redeem-codes', 'RedeemCodeController@index'); // Web console
$app->post('/redeem-codes', 'RedeemCodeController@create');
$app->delete('/redeem-codes', 'RedeemCodeController@delete');
$app->delete('/redeem-codes', 'RedeemCodeController@test');