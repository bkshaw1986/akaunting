<?php

// namespace App\Http\Controllers;
namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Abstracts\Http\Controller;
// use Stripe\StripeClient;
use Illuminate\Http\Request;

class Testpayment extends Controller
{
    public function payment(Request $request)
    {

// $stripe = new  \Stripe\StripeClient(env('STRIPE_SECRET'));
$stripe = new  \Stripe\StripeClient('sk_test_51N8rkNSFfAuYhyxlY4J55KfAtdger1wbhPmvcpESByaFM1skf1Q4byD2Jt039Rz7TJ4Wc0aphJpgtBmhuLHqDWTT00fuUsndDl');
$clientSecret = $request->input('clientSecret');
// dd($stripe);
$checkout_session=$stripe->checkout->sessions->create([
    'payment_method_types' => ['card'],
    'line_items' => [
      [
        'price_data' => [
          'currency' => 'inr',
          'product_data' => [
            'name' => 'Simplia Point Debit From A and Credit to B',
            // 'images' => ['https://example.com/images/t-shirt.jpg'],
          ],
          
          'unit_amount' => 20000,
        ],
        
        'quantity' => 1,
      ],
    ],
    'metadata' => [
        'order_id' => '12345',
        'customer_name' => 'John Doe',
        'custom_field' => 'Custom Value',
      ],
      'customer_email'=>'A@g.com',
    
    'mode' => 'payment',
    'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => route('cancel'),
]);
// dd($checkout_session);
return redirect()->away($checkout_session->url);

        
    }
    public function success(Request $request)
    {
        $sessionId = $request->input('session_id');

        // Retrieve the checkout session from Stripe
        $stripe = new  \Stripe\StripeClient('sk_test_51N8rkNSFfAuYhyxlY4J55KfAtdger1wbhPmvcpESByaFM1skf1Q4byD2Jt039Rz7TJ4Wc0aphJpgtBmhuLHqDWTT00fuUsndDl'); // Replace with your actual Stripe secret key
        $checkoutSession = $stripe->checkout->sessions->retrieve($sessionId);
        $payment_intentId = $checkoutSession->payment_intent;
        // return redirect::route('retrievePaymentData', ['id' => $payment_intentId]);
        // return redirect()->action([retrievePaymentData::class, 'retrievePaymentData'], ['param' => $payment_intentId]);
        // $value = 'example';
        session(['param' => $payment_intentId]);
        return redirect()->action([retrievePaymentData::class, 'retrievePaymentData']);
        dd($payment_intentId);
        dd($checkoutSession);
        $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);
        dd($paymentIntent);
    // Access the metadata associated with the checkout session
    $metadata = $checkoutSession->metadata;

        // Pass the metadata to the success view
        // return view('payment.success', compact('metadata'));
    }

    public function cancel(Request $request)
    {
      dd($request);
        return 'Payment Failed';
    }
    // public function success(Request $request){
    //     return 'Payment Successfull';
        
    // }
    // public function cancel(Request $request){
    //     return 'Payment Failed';
        
    // }

}
