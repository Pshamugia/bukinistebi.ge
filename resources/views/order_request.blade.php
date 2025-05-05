<!DOCTYPE html>
<html>
<head>
    <title>Order_Courier Request</title>
</head>
<body>
    <p> გადახდა კურიერთან </p>
     <p><strong> ID:</strong> {{ $order->id }}</p>
    <p><strong>მომხმარებლის სახელი:</strong> {{ $order->name }}</p>
    <p><strong>ტელეფონი:</strong> <a href="tel:{{ $order->phone }}">{{ $order->phone }}</a></p>
    <p><strong>ქალაქი:</strong> {{ session('city', 'No city selected') }}</p> <!-- Display city from session -->

    <p><strong>მისამართი:</strong> {{ $order->address }}</p>
    <p><strong>სრული გადასახდელი თანხა:</strong> {{ number_format($order->total, 2) }} ლარი</p>

    <h4>შეძენილი პროდუქცია:</h4>
    <ul>
        @foreach ($order->orderItems as $item)
            <li>
                {{ $item->book->title ?? 'Book not found' }} - 
                {{ $item->quantity }} x {{ number_format($item->price, 2) }} ლარი
                 <a href="{{ route('full', ['title' => Str::slug($item->book->title), 'id' => $item->book->id]) }}" target="_blank">
                   ლინკის ნახვა
                </a> <!-- Link to the book's full page -->
            </li>
        @endforeach
    </ul>
</body>
</html>
