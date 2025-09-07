@extends('admin.layouts.app')

@section('title', 'Guest users purchase')

@section('content')
<div class="container">
    <h2>Guest Order Details</h2>

    <p><strong>Name:</strong> {{ $order->name }}</p>
    <p>
        <strong>Phone:</strong>
        <a href="tel:{{ $order->phone }}" style="text-decoration: none; color: inherit;">
            {{ $order->phone }}
        </a>
    </p>
        <p><strong>Address:</strong> {{ $order->address }}</p>
    <p><strong>City:</strong> {{ $order->city }}</p>
    <p><strong>Total:</strong> {{ $order->total }} ლარი </p>
    <p><strong>Status:</strong> {{ $order->status }}</p>

    <h4>Purchased Books</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Book</th>
                <th>Qty</th>
                <th>DATE</th>
                <th>Book Price</th>
                <th>Total price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
                <tr>
                    <td><a href="{{ route('full', ['title' => Str::slug($item->book->title), 'id' => $item->book->id]) }}" target="_blank">
                        {{ $item->book->title }}
                    </a>
                
                
                    ({{ $item->quantity }})
                    @if($item->book->publisher)
                       <Span style="color:red; font-weight: bold"> — <small>ბუკინისტი: {{ $item->book->publisher->name }}</small> </Span>
                    @else
                        — <small>ჩემი წიგნებიდან</small>
                    @endif
                </td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>

                    <td>{{ $item->price }} ლარი </td>
                    <td>{{ $item->order->total }} ლარი </td>
                    
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
