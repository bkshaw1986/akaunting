<html>
  <head>
    <title>Simplia Credit Point System</title>
  </head>
  <body>
    <!-- Use action="/create-checkout-session.php" if your server is PHP based. -->
    <form action="{{ route('payment') }}" method="POST">
        @csrf
      <button type="submit">Checkout</button>
    </form>
  </body>
</html>