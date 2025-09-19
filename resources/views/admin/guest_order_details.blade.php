@extends('admin.layouts.app')

@section('title', 'Guest users purchase')

@section('content')
<div class="container">
    <h2>Guest Order Details</h2>

    <p><strong>Name:</strong> {{ $order->name }}</p>
    <p>
        <strong>Phone:</strong>
        @php
        $raw    = (string) $order->phone;
        $digits = preg_replace('/\D+/', '', $raw);   // keep only digits
        $local9 = substr($digits, -9);               // last 9 digits (e.g., 599999999)
    
        // Default fallbacks
        $displayPhone = $raw;
        $telPhone     = $raw;
    
        if (strlen($local9) === 9 && $local9[0] === '5') {
            // Format: 599-99-99-99
            $displayPhone = preg_replace('/^(\d{3})(\d{2})(\d{2})(\d{2})$/', '$1-$2-$3-$4', $local9);
    
            // Always dial with +995
            $telPhone = '+995' . $local9;
        }
    @endphp
    
    <a href="tel:{{ $telPhone }}" style="text-decoration: none; color: inherit;">
        {{ $displayPhone }}
    </a>
    
    </p>
    <p><strong>E-mail:</strong> {{ $order->email }}</p>

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
