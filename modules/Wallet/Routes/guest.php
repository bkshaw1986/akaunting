<?php
// AUTH ROUTE
Route::group([
  'prefix' => 'auth',
  'namespace' => 'Modules\Wallet\Http\Controllers'
], function () {
  Route::get('oxygenacc', 'Account@authenticate');
});