<?php

// namespace App\Http\Controllers;
namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Route;
use App\Abstracts\Http\Controller;
// use Stripe\StripeClient;
use Illuminate\Http\Request;
use Stripe\Stripe;

class PaymentController extends Controller
{
    public function getPaymentIntentDetails($paymentIntentId)
    {
        $stripe = new  \Stripe\StripeClient('sk_test_51N8rkNSFfAuYhyxlY4J55KfAtdger1wbhPmvcpESByaFM1skf1Q4byD2Jt039Rz7TJ4Wc0aphJpgtBmhuLHqDWTT00fuUsndDl');
        Stripe::setApiKey('sk_test_51N8rkNSFfAuYhyxlY4J55KfAtdger1wbhPmvcpESByaFM1skf1Q4byD2Jt039Rz7TJ4Wc0aphJpgtBmhuLHqDWTT00fuUsndDl');
        try {
            $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);
            dd($paymentIntent);
            // Access the payment intent details
            $amount = $paymentIntent->amount;
            $currency = $paymentIntent->currency;
            $status = $paymentIntent->status;
        // Access the payment method details
        $paymentMethodId = $paymentIntent->payment_method;
        $paymentMethod = null;
        if ($paymentMethodId) {
            $paymentMethod = \Stripe\PaymentMethod::retrieve($paymentMethodId);
        }
        $paymentMethodName = $paymentMethod ? $paymentMethod->type : null;
                // Access the payment ID
                $paymentId = null;
                if ($paymentIntent->charges && count($paymentIntent->charges->data) > 0) {
                    $paymentId = $paymentIntent->charges->data[0]->payment_method_details->payment_intent;
                }
        // Return the details or perform any necessary actions
        return response()->json([
            'amount' => $amount,
            'currency' => $currency,
            'status' => $status,
            'payment_method' => $paymentMethodName,
            'payment_id' => $paymentMethodId,
            ]);
        } catch (\Exception $e) {
            // Handle any errors that occur during the retrieval
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
