@extends('admin.layouts.app')

@section('title', 'Guest users purchase')

@section('content')
<div class="container">
    <h2>Guest Order Details</h2>

    <p><strong>Name:</strong> {{ $order->name }}</p>
    <p><strong>Phone:</strong> {{ $order->phone }}</p>
    <p><strong>Address:</strong> {{ $order->address }}</p>
    <p><strong>City:</strong> {{ $order->city }}</p>
    <p><strong>Total:</strong> {{ $order->total }} ₾</p>
    <p><strong>Status:</strong> {{ $order->status }}</p>

    <h4>Purchased Books</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Book</th>
                <th>Qty</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->book->title ?? 'Deleted Book' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->price }} ₾</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
