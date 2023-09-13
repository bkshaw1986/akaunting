<?php

Route::group([
    'middleware' => 'admin',
    'namespace' => 'Modules\Wallet\Http\Controllers'
], function () {
    Route::prefix('wallet')->group(function() {
        // Route::get('/', 'Main@index');
    });
});
