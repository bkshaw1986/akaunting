<?php

// namespace App\Http\Controllers;
namespace App\Http\Controllers\Auth;

use App\Abstracts\Http\Controller;
// use Stripe\StripeClient;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Event;
use Stripe\Stripe;

class retrievePaymentData extends Controller
{
    public function retrievePaymentData()
    {
        // $paymentIntentId = $request->input('id');
        $paymentIntentId = session('param');
        // $paymentIntentId = $param;
        // dd($paymentIntentId);
        // Set your Stripe secret key
        Stripe::setApiKey('sk_test_51N8rkNSFfAuYhyxlY4J55KfAtdger1wbhPmvcpESByaFM1skf1Q4byD2Jt039Rz7TJ4Wc0aphJpgtBmhuLHqDWTT00fuUsndDl'); // Replace with your actual Stripe secret key
    
        try {
            // Retrieve the payment intent
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
    
            // Retrieve the payment method associated with the payment intent
            $paymentMethodId = $paymentIntent->payment_method;
            $paymentMethod = PaymentMethod::retrieve($paymentMethodId);
    
            // Retrieve the events associated with the payment intent (timeline)
            $events = Event::all([
                'type' => 'payment_intent.succeeded',
            ]);
            $filteredEvents = array_filter($events->data, function ($event) use ($paymentIntentId) {
                return $event->data->object->id === $paymentIntentId;
            });
            print_r($filteredEvents);
            dd($events);
            // Retrieve the checkout summary (amount, currency, status, etc.) from the payment intent
            $amount = $paymentIntent->amount;
            $currency = $paymentIntent->currency;
            $status = $paymentIntent->status;
            // Retrieve other properties as needed
    
            // Retrieve the payment details (transaction ID, created date, etc.) from the latest event
            $latestEvent = $events->data[0];
            $transactionId = $latestEvent->id;
            $createdDate = $latestEvent->created;
    
            // Retrieve the risk insights associated with the payment intent
            $riskInsights = $paymentIntent->risk;
    
            // Build an array containing all the retrieved data
            $paymentData = [
                'paymentMethod' => $paymentMethod,
                'timeline' => $events,
                'checkoutSummary' => [
                    'amount' => $amount,
                    'currency' => $currency,
                    'status' => $status,
                    // Include other relevant data as needed
                ],
                'paymentDetails' => [
                    'transactionId' => $transactionId,
                    'createdDate' => $createdDate,
                    // Include other relevant data as needed
                ],
                'riskInsights' => $riskInsights,
            ];
    
            // Return the payment data
            dd($paymentData);
            return $paymentData;
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Handle any API errors
            return $e->getMessage();
        }
    }
}
