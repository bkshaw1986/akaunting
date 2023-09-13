<?php
// namespace App\Http\Controllers;
namespace App\Http\Controllers\Auth;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redirect;
use App\Abstracts\Http\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class PaymentApi extends Controller
{
    public function makeApiRequest(Request $request)
    {
        $client = new Client();
        // Validate the input
        $request->validate([
            'order_id' => 'required',
        ]);
        // Retrieve the order ID from the request
        $orderId = $request->input('order_id');

        
        // $response = $client->get('http://debianlargeserver-0050-developer.laxroute53.com/JiteshKPHP/get_payment_details/api_fetch_single.php?id='.$orderId, [
        //     'headers' => [
        //         'Content-Type' => 'application/json'
        //     ],
        //     'verify' => false, // Disable SSL verification
        // ]);
        $response = $client->get('http://debianlargeserver-0050-developer.laxroute53.com/JiteshKPHP/create-simple-rest-api-php-mysql/items/?id='.$orderId, [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'verify' => false, // Disable SSL verification
        ]);

        $resultData = $response->getBody()->getContents();
        $array = json_decode($resultData, true);

        // var_dump($array);

        // echo"<pre>";print_r($array);exit;
        return view('payment.payment_details', ['result' => $array]);
        // echo"<pre>";print_r($data);exit;
        // return $data;
    }
}
