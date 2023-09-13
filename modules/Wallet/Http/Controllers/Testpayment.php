<?php

namespace App\Http\Controllers;

// use Stripe\StripeClient;
use Illuminate\Http\Request;

class Testpayment extends Controller
{
    public function payment(Request $request)
    {
            // Set your secret key. Remember to switch to your live secret key in production.
// See your keys here: https://dashboard.stripe.com/apikeys
$stripe = new \Stripe\StripeClient('sk_test_51N8rkNSFfAuYhyxlY4J55KfAtdger1wbhPmvcpESByaFM1skf1Q4byD2Jt039Rz7TJ4Wc0aphJpgtBmhuLHqDWTT00fuUsndDl');
$clientSecret = $request->input('clientSecret');
// dd($stripe);
$checkout_session=$stripe->checkout->sessions->create([
    'payment_method_types' => ['card'],
    'line_items' => [
      [
        'price_data' => [
          'currency' => 'inr',
          'product_data' => [
            'name' => 'Simplia Debit From A',
            // 'images' => ['https://example.com/images/t-shirt.jpg'],
          ],
          
          'unit_amount' => 10000,
        ],
        
        'quantity' => 1,
      ],
      [
        'price_data' => [
          'currency' => 'inr',
          'product_data' => [
            'name' => 'Simplia Credit To B',
            // 'images' => ['https://example.com/images/pants.jpg'],
          ],
          'unit_amount' => 10000,
        ],
        'quantity' => 1,
      ],
    ],
    'customer_email'=>'A@g.com',
    'mode' => 'payment',  
    'success_url' => route('success'),
    'cancel_url' => route('cancel'), 
]);
return redirect()->away($checkout_session->url);
// dd($checkout_session);
        
    }
    public function success(Request $request){
        return 'Payment Successfull';
        
    }
    public function cancel(Request $request){
        return 'Payment Failed';
        
    }

}
