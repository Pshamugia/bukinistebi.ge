@extends('layouts.app')

@section('content')
<div class="container" style="position: relative; min-height: 400px">
    <h1>შეკვეთა მიღებულია</h1>
    <p>შენი შეკვეთა მიღებულია და მიიღებ მაქსიმუმ 2 სამუშაო დღეში. <br>
        ჩვენი კურიერი დაგიკავშირდება მითითებულ ტელეფონის ნომერზე: {{ $order->phone ?? 'Phone number not provided' }} 

    <!-- Display order details -->
    <h3>შეკვეთის დეტალები</h3>
    <p>შეკვეთის ID: {{ $order->id }}</p>
    <p>მომხმარებელი: {{ $order->user->name }}</p>
    <p>სრული გადასახდელი თანხა: {{ number_format($order->total, 2) }} ლარი</p>

    <h4>თქვენ მიერ შეძენილი პროდუქცია:</h4>
    <ul>
        @foreach ($order->orderItems as $item)
            <li>{{ $item->book->title }} - {{ $item->quantity }} ცალი {{ number_format($item->price, 2) }} ლარი</li>
        @endforeach
    </ul>
</div>
@endsection
