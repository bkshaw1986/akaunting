<?php

Route::group([
    'prefix' => 'api',
    'middleware' => 'openapi',
    'namespace' => 'Modules\Wallet\Http\Controllers'
], function () {
    // Route::post('transactions/sync', 'Banking\Transactions@sync');
    Route::post('oxygenaccounting/user', 'Account@createUser');
    Route::put('oxygenaccounting/user', 'Account@updateUser');
    Route::post('wallet/transaction', 'Transaction@createTransaction');
    Route::post('wallet/transaction/accept', 'Transaction@acceptTransaction');
    Route::post('wallet/transaction/grant', 'Transaction@grantTransaction');
    Route::post('wallet/balance', 'Transaction@checkBalance');
    Route::post('wallet/points/add', 'Transaction@addPoints');
    Route::get('/siddharth', function() {
        return '<h1>Hello SIMPLIA!</h1>';
    });
    Route::get('/siddahrth_new_route', function() {
        return '<h1>Hello World, New Changes Here</h1>';
    });
});

