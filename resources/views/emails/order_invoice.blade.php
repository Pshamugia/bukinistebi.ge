@component('mail::message')
# ინვოისი / Order Receipt  
**Order:** {{ $order->order_id }}  
**Status:** {{ ucfirst($order->status) }}  
**Name:** {{ $order->name }}  
**Phone:** <a href="tel:{{ $order->phone }}">{{ $order->phone }}</a>  
**City:** {{ $order->city }}  
**Address:** {{ $order->address }}

---

### Items
@foreach($order->orderItems as $item)
- @if($item->book)
  **{{ $item->book->title }}** × {{ $item->quantity }} — {{ number_format($item->price) }} ₾
  @if($item->size) (ზომა: {{ $item->size }}) @endif
  @elseif($item->bundle)
  **Bundle:** {{ $item->bundle->title }} × {{ $item->quantity }} — {{ number_format($item->price) }} ₾  
  @foreach($item->bundle->books as $b)
    · {{ $b->title }} — × {{ $b->pivot->qty }}
  @endforeach
  @endif
@endforeach

---

**Subtotal:** {{ number_format($order->subtotal, 2) }} ₾  
**Shipping:** {{ number_format($order->shipping, 2) }} ₾  
**Total:** **{{ number_format($order->total, 2) }} ₾**

მადლობა შეძენისთვის!   
bukinistebi.ge
 
@endcomponent
