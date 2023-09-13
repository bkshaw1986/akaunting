
<!DOCTYPE html>
<html>
<head>
    <title>Payment Success</title>
</head>
<body>
    <h1>Payment Successful!</h1>

    <h2>Order ID: {{ $metadata['order_id'] }}</h2>
    <h3>Customer Name: {{ $metadata['customer_name'] }}</h3>
    <p>Custom Field: {{ $metadata['custom_field'] }}</p>

    {{-- <h2>Payment Method: {{ $paymentMethodType }}</h2> --}}
{{-- 
    @if ($paymentMethodType === 'card')
        <p>Card Brand: {{ $cardBrand }}</p>
        <p>Last 4 Digits: {{ $cardLastFour }}</p>
    @endif --}}
</body>
</html>