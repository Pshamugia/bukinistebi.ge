<!DOCTYPE html>
<html>
<head>
    <title>Order_Courier Request</title>
    @php use Illuminate\Support\Str; @endphp
    <script>
      !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
      n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}
      (window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
      fbq('init','1049503350038938'); fbq('track','PageView');
    </script>
</head>
<body>
  <p>გადახდა კურიერთან</p>
  <p><strong>ID:</strong> {{ $order->id }}</p>
  <p><strong>მომხმარებლის სახელი:</strong> {{ $order->name }}</p>
  <p><strong>ტელეფონი:</strong> <a href="tel:{{ $order->phone }}">{{ $order->phone }}</a></p>
  <p><strong>ქალაქი:</strong> {{ $order->city ?? 'No city provided' }}</p>
  <p><strong>მისამართი:</strong> {{ $order->address }}</p>
  <p><strong>სრული გადასახდელი თანხა:</strong> {{ number_format($order->total, 2) }} ლარი</p>

  <h4>შეძენილი პროდუქცია:</h4>
  <ul>
    @foreach ($order->orderItems as $item)
      @php $isBundle = !empty($item->bundle_id) && $item->bundle; @endphp

      <li>
        @if ($isBundle)
          <span class="badge bg-info">Bundle</span>
          {{ $item?->bundle?->title ?? 'No Title'}}
          — {{ $item->quantity }} × {{ number_format($item->price, 2) }} ლარი

          <div class="small text-muted">
            @foreach ($item->bundle->books as $b)
              {{ $b->title ?? 'No Title' }} × {{ $b->pivot->qty }}@if(!$loop->last), @endif
            @endforeach
          </div>

          @if($item->bundle->slug ?? false)
            <a href="{{ route('bundles.show', $item->bundle->slug) }}" target="_blank">ლინკის ნახვა</a>
          @endif

        @elseif ($item->book)
          {{ $item?->book?->title ?? 'No Title'}}
          — {{ $item->quantity }} × {{ number_format($item->price, 2) }} ლარი
          @if ($item->size)
            — ზომა: {{ $item->size }}
          @endif

          <a href="{{ route('full', ['title' => Str::slug($item?->book?->title ?? '__'), 'id' => $item->book->id]) }}" target="_blank">
            ლინკის ნახვა
          </a>
        @else
          <em>Item not found (probably removed)</em>
        @endif
      </li>
    @endforeach
  </ul>

  <script>
    fbq('track','Purchase',{ value: {{ $order->total ?? 0 }}, currency:'GEL' });
  </script>
</body>
</html>
