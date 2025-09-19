@extends('layouts.app')
@php use Illuminate\Support\Str; @endphp

@section('title', 'შეკვეთის სტატუსი')

@section('content')
@php
  // Optional helpers (safe if not provided)
  $orderNo = $order->order_id ?? $order->id ?? null;

  // Use whatever you have: $status->key OR raw $order->status from DB (e.g. "Succeeded")
  $rawStatus = $status->key ?? $order->status ?? 'unknown';

  // Normalize gateway → internal keys + user-facing labels
  $map = [
    // Gateway (TBC) statuses
    'Succeeded'   => ['key' => 'success', 'label' => 'დადასტურდა'],
    'Authorized'  => ['key' => 'pending', 'label' => 'მუშავდება'],
    'Initialized' => ['key' => 'pending', 'label' => 'მუშავდება'],
    'Processing'  => ['key' => 'pending', 'label' => 'მუშავდება'],
    'Declined'    => ['key' => 'failed',  'label' => 'ჩაიშალა'],
    'Failed'      => ['key' => 'failed',  'label' => 'ჩაიშალა'],
    'Canceled'    => ['key' => 'failed',  'label' => 'გაუქმდა'],
    'Cancelled'   => ['key' => 'failed',  'label' => 'გაუქმდა'],

    // Your own internal statuses (if already normalized elsewhere)
    'success'     => ['key' => 'success', 'label' => 'დადასტურდა'],
    'pending'     => ['key' => 'pending', 'label' => 'მუშავდება'],
    'processing'  => ['key' => 'pending', 'label' => 'მუშავდება'],
    'failed'      => ['key' => 'failed',  'label' => 'ჩაიშალა'],
    'error'       => ['key' => 'failed',  'label' => 'შეცდომა'],
  ];

  $normalized = $map[$rawStatus] ?? ['key' => Str::lower((string)$rawStatus), 'label' => (string)$rawStatus];

  $key    = $normalized['key'];
  $label  = $normalized['label'];
  $isSuccess = $key === 'success';
  $isPending = in_array($key, ['pending','processing'], true);
  $isFailed  = in_array($key, ['failed','canceled','cancelled','declined','error'], true);
@endphp

<style>
  .status-page { position:relative; top:30px; min-height:420px; }
  .card-soft   { border-radius:18px; }
</style>

<div class="container status-page">
  {{-- Banner --}}
  <div
    class="alert @if($isSuccess) alert-success @elseif($isPending) alert-warning @elseif($isFailed) alert-danger @else alert-secondary @endif
                d-flex align-items-center gap-2 mb-4"
    role="alert" style="border-radius:14px;">
    <i class="bi @if($isSuccess) bi-check-circle-fill
                 @elseif($isPending) bi-hourglass-split
                 @elseif($isFailed) bi-x-circle-fill
                 @else bi-info-circle-fill @endif fs-4"></i>
    <div>
      <strong>გადახდის სტატუსი: {{ $label }}</strong>
      @if($orderNo)
        <div class="small text-muted">№ {{ $orderNo }}</div>
      @endif
    </div>
  </div>

  {{-- Content card --}}
  <div class="card card-soft shadow-sm mb-4">
    <div class="card-body">
      @if($isSuccess)
        <p class="mb-2">თქვენი შეკვეთა მიღებულია და მალე დაგიკავშირდებით.</p>
        <div class="small text-muted">მადლობა ნდობისთვის! ინვოისი მოგივათ ელფოსტაზე.</div>
      @elseif($isPending)
        <p class="mb-2">გადახდა მუშავდება. შესაძლოა ამას რამდენიმე წამი დასჭირდეს.</p>
        <div class="small text-muted">თუ პრობლემა გაგრძელდა, გთხოვთ, განაახლოთ გვერდი ან დაგვიკავშირდეთ.</div>
      @elseif($isFailed)
        <p class="mb-2">ვერ მოხერხდა შეკვეთის დადასტურება.</p>
        <ul class="mb-0">
          <li>შეამოწმეთ ბარათის მონაცემები/ბალანსი.</li>
          <li>სცადეთ სხვა ბრაუზერით ან მოწყობილობით.</li>
          <li>საჭიროების შემთხვევაში დაგვიკავშირდით დახმარებისთვის.</li>
        </ul>
      @else
        <p class="mb-0">სტატუსის ნახვა ამ მომენტში ვერ ხერხდება. სცადეთ მოგვიანებით.</p>
      @endif
    </div>
  </div>

  {{-- Actions --}}
  <div class="d-flex flex-wrap gap-2">
    <a href="{{ route('welcome') }}" class="btn btn-primary">
      <i class="bi bi-house"></i> მთავარი
    </a>
    <a href="{{ route('books') }}" class="btn btn-outline-primary">
      <i class="bi bi-book-half"></i> წიგნების დათვალიერება
    </a>
    @if($isFailed)
      <a href="{{ url('/cart') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-repeat"></i> სცადე თავიდან
      </a>
    @endif
    @if($orderNo ?? false)
      <button class="btn btn-outline-secondary" onclick="window.print()">
        <i class="bi bi-printer"></i> ქვითრის ბეჭდვა
      </button>
    @endif
  </div>
</div>
@endsection
