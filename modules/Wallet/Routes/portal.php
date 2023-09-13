<?php

Route::group([
    'prefix' => 'portal',
    'middleware' => 'portal',
    'namespace' => 'Modules\Wallet\Http\Controllers'
], function () {
    // Route::get('invoices/{invoice}/wallet', 'Main@show')->name('portal.invoices.wallet.show');
    // Route::post('invoices/{invoice}/wallet/confirm', 'Main@confirm')->name('portal.invoices.wallet.confirm');
});
