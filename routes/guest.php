<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RetrievePaymentData;
/**
 * 'guest' middleware applied to all routes
 *
 * @see \App\Providers\Route::mapGuestRoutes
 * @see \modules\PaypalStandard\Routes\guest.php for module example
 */
Route::group(['prefix' => 'auth'], function () {
    Route::get('login', 'Auth\Login@create')->name('login');
    Route::post('login', 'Auth\Login@store')->name('login.store');
	
	// Added by Rajasekar from OLD version Starts
	Route::get('oxygen', 'Auth\Login@authenticate');
    Route::get('oxygen/simple', 'Auth\Login@authenticateSimple');
	// Added by Rajasekar from OLD version ENDS
	
    Route::get('forgot', 'Auth\Forgot@create')->name('forgot');
    Route::post('forgot', 'Auth\Forgot@store')->name('forgot.store');

    //Route::get('reset', 'Auth\Reset@create');
    Route::get('reset/{token}', 'Auth\Reset@create')->name('reset');
    Route::post('reset', 'Auth\Reset@store')->name('reset.store');

   // Route::get('register/{token}', 'Auth\Register@create')->name('register');
   // Route::post('register', 'Auth\Register@store')->name('register.store');
   //Added By Jitesh For Testing Simplia Credit Point System Payment
    Route::post('payment', 'Auth\Testpayment@payment')->name('payment');
    Route::get('success', 'Auth\Testpayment@success')->name('success');
    // Route::get('success', function(){                                                           
    //     return view('payment/success');
    //     })->name('success');
        // ****************Payment Api******************
        Route::get('payment_api', function () {
            return view('payment/payment_details');
        });
        Route::post('payment_details', 'Auth\PaymentApi@makeApiRequest')->name('payment_details');
         // ****************Payment Api End******************
    Route::get('cancel', 'Auth\Testpayment@cancel')->name('cancel');
    Route::get('payment-intent/{paymentIntentId}', 'Auth\PaymentController@getPaymentIntentDetails');
    // Route::get('retrievePaymentData/{paymentIntentId}', 'Auth\retrievePaymentData@retrievePaymentData');
    Route::get('retrievePaymentData', 'Auth\retrievePaymentData@retrievePaymentData')->name('retrievePaymentData');

    Route::get('testpayment', function () {
        return view('payment/testpayment');
    });
    Route::get('jitesh', function () {
        return '<h1>Hello Simplia! This is Jitesh</h1>';
    });
});

Route::get('helloworld/oxygen', 'HelloWorldController@index');

Route::get('/', function () {
    return redirect()->route('login');
});


