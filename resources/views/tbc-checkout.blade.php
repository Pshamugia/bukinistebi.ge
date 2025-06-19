@extends('layouts.app')

@section('title', 'TBC Bank Payment')

@section('content')
<div class="container mt-5" style="position: relative;  min-height: 400px">
    <form action="{{ route('process.payment') }}" method="POST">
    @csrf
    <label for="total">Total Amount:</label>
    <input type="number" name="total" required>
    <button type="submit">Pay Now</button>
</form>

</div>
 
@endsection
