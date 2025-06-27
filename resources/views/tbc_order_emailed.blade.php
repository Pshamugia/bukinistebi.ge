<!-- resources/views/emails/order_purchased.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>ბუკინისტი - TBC შეკვეთა</title>
</head>
<body>
    <h1>TBC შეკვეთა - BUKINISTEBI.GE</h1>
    <p><strong>Order ID:</strong> {{ $order->order_id }}</p>
    <p><strong>Status:</strong> {{ $order->status }}</p>
    <p><strong>Name:</strong> {{ $order->name }}</p>
    <p><strong>Phone:</strong> <a href="tel:{{ $order->phone }}">{{ $order->phone }}</a></p>
    <p><strong>ქალაქი:</strong> {{ session('city', 'No city selected') }}</p> <!-- Display city from session -->
    <p><strong>Address:</strong> {{ $order->address }}</p>
    <p><strong>Total Amount:</strong> {{ $order->total }} GEL</p>
    
    <h2>Ordered Items:</h2>
    <ul>
        @foreach($order->orderItems as $item)
            <li>{{ $item->book->title }} (Quantity: {{ $item->quantity }}, Price: {{ $item->price }} GEL)
            <br>
            
                <a href="{{ route('full', ['title' => Str::slug($item->book->title), 'id' => $item->book->id]) }}" target="_blank">
                    ლინკის ნახვა
                 </a> <!-- Link to the book's full page -->
                 
            </li>
            
        @endforeach
    </ul>
    <script>
        fbq('track', 'Purchase', {
          value: {{ $order->total ?? 0 }},
          currency: 'GEL'
        });
      </script>
</body>
</html>
