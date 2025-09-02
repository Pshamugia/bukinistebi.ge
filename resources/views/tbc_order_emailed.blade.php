@php use Illuminate\Support\Str; @endphp
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>ბუკინისტები — შეკვეთა</title>
  <style>
    body { font-family: Arial, Helvetica, sans-serif; color:#222; }
    .badge { display:inline-block; padding:2px 6px; background:#17a2b8; color:#fff; border-radius:3px; font-size:12px; }
    .small { color:#666; font-size:12px; }
  </style>
</head>
<body>
  <h1>BUKINISTEBI.GE — შეკვეთა</h1>

  <p><strong>Order ID:</strong> {{ $order->order_id }}</p>
  <p><strong>Status:</strong> {{ $order->status }}</p>
  <p><strong>Name:</strong> {{ $order->name }}</p>
  <p><strong>Phone:</strong> <a href="tel:{{ $order->phone }}">{{ $order->phone }}</a></p>
  <p><strong>ქალაქი:</strong> {{ $order->city ?? 'No city provided' }}</p>
  <p><strong>მისამართი:</strong> {{ $order->address }}</p>
  <p><strong>Total:</strong> {{ number_format($order->total, 2) }} GEL</p>

  <h2>Ordered Items</h2>
  <ul>
    @foreach($order->orderItems as $item)
      @if($item->bundle_id && $item->bundle)
        <li>
          <span class="badge">Bundle</span>
          {{ $item?->bundle?->title  ?? 'No Title' }}
          — {{ $item->quantity }} × {{ number_format($item->price, 2) }} GEL
          <div class="small">
            @foreach ($item->bundle->books as $b)
              {{ $b->title ?? 'No Title' }} × {{ $b->pivot->qty }}@if(!$loop->last), @endif
            @endforeach
          </div>
          @if($item->bundle->slug ?? false)
            <div>
              <a href="{{ route('bundles.show', $item->bundle->slug) }}" target="_blank">Bundle link</a>
            </div>
          @endif
        </li>
      @elseif($item->book)
        <li>
          {{ $item?->book?->title ?? 'No Title' }}
          — {{ $item->quantity }} × {{ number_format($item->price, 2) }} GEL
          @if($item->size)
            — Size: {{ $item->size }}
          @endif
          <div>
            <a href="{{ route('full', ['title' => Str::slug($item?->book?->title ?? '__'), 'id' => $item->book->id]) }}" target="_blank">
              View item
            </a>
          </div>
        </li>
      @else
        <li><em>Unknown item</em> — {{ $item->quantity }} × {{ number_format($item->price, 2) }} GEL</li>
      @endif
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
