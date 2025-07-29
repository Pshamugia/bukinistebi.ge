@extends('layouts.app')

@section('title', 'გადახდა შესრულებულია')

@section('content')

<div class="container py-5" style="position: relative; margin-top:140px; min-height: 400px;">
    <div class="alert alert-success text-center">
        <h3>✅ გმადლობთ! თქვენი შეკვეთა მიღებულია.</h3>
        <p>შეკვეთის ნომერი: <strong>{{ $order->order_id }}</strong></p>
        <p>გადაიხდით კურიერთან მიღებისას.</p>
    </div>

    <h4 class="mt-4">შეკვეთილი წიგნები:</h4>
    <ul>
        @foreach($order->orderItems as $item)
            <li>{{ $item->book->title }} × {{ $item->quantity }}</li>
        @endforeach
    </ul>
</div>
@endsection