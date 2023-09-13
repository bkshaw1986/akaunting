{{-- <!DOCTYPE html>
<html>
<head>
    <title>Order Lookup</title>
</head>
<body>
    <h1>Order Lookup</h1>
    <form action="{{ route('payment_details') }}" method="post">
        @csrf
        <label for="order_id">Order ID:</label>
        <input type="text" id="order_id" name="order_id" required>
        <button type="submit">Lookup</button>
    </form>

    @isset($result)
        <h2>Result</h2>
        <p>{{ $result }}</p>
    @endisset
</body>
</html> --}}
<!DOCTYPE html>
<html>
<head>
    <title>Payment Lookup</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            text-align: center;
            margin-top: 50px;
        }

        h1, h2 {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"] {
            padding: 5px;
            width: 200px;
        }

        button[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Payment Lookup</h1>
        <form action="{{ route('payment_details') }}" method="post">
            @csrf
            <label for="order_id">Payment ID:</label>
            <input type="text" id="order_id" name="order_id" required>
            <br>
            <button type="submit">Lookup</button>
        </form>
        @isset($result)
        <div>
            <h2>Result</h2>
            <table>
                <tbody>
                    <tr>
                        <th>Field</th>
                        <th>Value</th>
                    </tr>
                    @if(isset($result['msg']) && $result['msg'] == 'No Data!')
                        <tr>
                            <td>msg</td>
                            <td>{{ $result['msg'] }}</td>
                        </tr>
                    @else
                        @foreach ($result as $item)
                            @foreach ($item as $key => $value)
                                <tr>
                                    <td>{{ $key }}</td>
                                    <td>{{ $value }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    @endisset
    
    
    </div>
</body>
</html>
