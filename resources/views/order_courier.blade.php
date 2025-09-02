@extends('layouts.app')
@php use Illuminate\Support\Str; @endphp

@section('content')
<div class="container" style="position: relative; min-height: 400px; top:30px;">
  <h1>შეკვეთა მიღებულია</h1>
  <p>
    შენი შეკვეთა მიღებულია და მიიღებ მაქსიმუმ დათქმულ დროში. <br>
    ჩვენი კურიერი დაგიკავშირდება მითითებულ ტელეფონის ნომერზე:
    {{ $order->phone ?? 'Phone number not provided' }}
  </p>

  @php
    $order->loadMissing('user','orderItems.book','orderItems.bundle.books');
  @endphp

  <h3>შეკვეთის დეტალები</h3>
  <p>შეკვეთის ID: {{ $order->id }}</p>
  <p>მომხმარებელი: {{ $order->user->name ?? '—' }}</p>
  <p>სრული გადასახდელი თანხა: {{ number_format($order->total, 2) }} ლარი</p>

  <h4>თქვენ მიერ შეძენილი პროდუქცია:</h4>
  <ul>
    @foreach ($order->orderItems as $item)
      @if ($item->bundle_id && $item->bundle)
        <li>
          <span class="badge bg-info">Bundle</span>
          {{ $item?->bundle?->title ?? 'No Title' }}
          — {{ $item->quantity }} × {{ number_format($item->price, 2) }} ლარი

          <div class="small text-muted">
            @foreach ($item->bundle->books as $b)
              {{ $b?->title  ?? 'No Title' }} × {{ $b->pivot->qty }}@if(!$loop->last), @endif
            @endforeach
          </div>

          @if($item->bundle->slug ?? false)
            <a href="{{ route('bundles.show', $item->bundle->slug) }}" target="_blank">ლინკის ნახვა</a>
          @endif
        </li>
      @elseif ($item->book)
        <li>
          {{ $item?->book?->title ??  'No Title' }}
          — {{ $item->quantity }} × {{ number_format($item->price, 2) }} ლარი
          @if ($item->size) — ზომა: {{ $item->size }} @endif
          <a href="{{ route('full', ['title' => Str::slug($item?->book?->title ?? 'No Title'), 'id' => $item->book->id]) }}" target="_blank">
            ლინკის ნახვა
          </a>
        </li>
      @else
        <li><em>პროდუქტი ვერ მოიძებნა</em></li>
      @endif
    @endforeach
  </ul>
</div>

<script>
  fbq('track','Purchase',{ value: {{ $order->total ?? 0 }}, currency:'GEL' });
</script>
@endsection
