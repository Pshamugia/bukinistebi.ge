@extends('layouts.app')

@section('title', 'Checkout Test')

@section('content')
<div class="container mt-5">
    <h3>Checkout Test</h3>

    <!-- Button to trigger the test function -->
    <button id="testCheckout">Test Checkout</button>
</div>
@endsection

@section('scripts')
<script>
    // Define the testCheckout function
    function testCheckout() {
        console.log("Button clicked");
        alert("Button is working");
    }

    // Attach the click event listener to the button
    document.getElementById("testCheckout").addEventListener("click", testCheckout);
</script>
@endsection
