<!-- resources/views/payment/form.blade.php -->
@extends('layout.app')

@section('content')
    <h1>Payment Form</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('payment.process') }}" method="POST">
        @csrf
        <script type="text/javascript" src="https://checkout.stripe.com/checkout.js" class="stripe-button"
             data-key={{env('STRIPE_KEY')}}
             data-amount="500"
            data-name="Requested by A"
            data-description="500 sent by A to accept Jitesh"
             data-image=""
             data-currency="inr"
            data-email="A@g.com"></script>
        <!-- Payment details input fields -->
        <!-- e.g., card number, expiry date, CVC, etc. -->

        {{-- <button type="submit" class="btn btn-primary">Pay</button> --}}
    </form>
@endsection
