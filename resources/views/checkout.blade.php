@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container mt-5">
    <h3>Checkout</h3>

    <!-- Form to trigger the payment -->
    <form id="checkoutForm" action="{{ route('checkout') }}" method="POST">
        @csrf
        <input type="hidden" name="amount" value="100"> <!-- Set your dynamic or test amount here -->
        <button type="submit" class="btn btn-primary">Proceed to Pay</button>
    </form>

    <!-- Optional: Test Button to Trigger Checkout Form Submission -->
    <button id="testCheckout" class="btn btn-secondary mt-3">Test Checkout Button</button>
</div>

<script>
    // JavaScript to ensure the form submits as POST when Test Checkout Button is clicked
    document.getElementById("testCheckout").addEventListener("click", function() {
        document.getElementById("checkoutForm").submit();
    });
</script>
@endsection
