<!DOCTYPE html>
<html>
<head>
    <title>Order_Courier Request</title>
    <!-- ✅ Facebook Pixel should be loaded -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '1049503350038938'); // ჩასვი შენი Pixel ID
        fbq('track', 'PageView');
    </script>
</head>
<body>
    <p> გადახდა კურიერთან </p>
     <p><strong> ID:</strong> {{ $order->id }}</p>
    <p><strong>მომხმარებლის სახელი:</strong> {{ $order->name }}</p>
    <p><strong>ტელეფონი:</strong> <a href="tel:{{ $order->phone }}">{{ $order->phone }}</a></p>
    <p><strong>ქალაქი:</strong>{{ $order->city ?? "No city provided" }}</p> <!-- Display city from session -->

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
    <script>
        fbq('track', 'Purchase', {
          value: {{ $order->total ?? 0 }},
          currency: 'GEL'
        });
      </script>
</body>
</html>
