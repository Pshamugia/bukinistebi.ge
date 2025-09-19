@extends('layouts.app')

@section('title', 'გადახდა შესრულებულია')

@section('content')
@php
  $orderNo = $order->order_id ?? $order->id ?? null;
@endphp

<style>
  .success-page { position:relative; top:30px; min-height:420px; }
  .card-soft    { border-radius:18px; }
</style>

<div class="container success-page">
  {{-- Success banner --}}
  <div class="alert alert-success d-flex align-items-center gap-2 mb-4" role="alert" style="border-radius:14px;">
    <i class="bi bi-check-circle-fill fs-4"></i>
    <div>
      <strong>გადახდა შესრულებულია წარმატებით!</strong>
      @if($orderNo)
        <div class="small text-muted">შეკვეთის №: <span class="fw-semibold">{{ $orderNo }}</span></div>
      @endif
    </div>
  </div>

  {{-- Message card --}}
  <div class="card card-soft shadow-sm mb-4">
    <div class="card-body">
      <p class="mb-1">თქვენი შეკვეთა მიღებულია და მალე დაგიკავშირდებით.</p>
      <div class="small text-muted">მიწოდების დეტალები მიიღებთ მითითებულ ნომერზე/ელფოსტაზე.</div>
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
    <button class="btn btn-outline-secondary" onclick="window.print()">
      <i class="bi bi-printer"></i> ქვითრის ბეჭდვა
    </button>
  </div>
</div>
@endsection
