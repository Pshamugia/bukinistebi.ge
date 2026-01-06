@extends('layouts.app')

@section('title', 'Current Auctions')
@section('og')
  <meta property="og:type" content="website">
  <meta property="og:title" content="ბუკინისტური აუქციონი | BUKINISTEBI.GE">
  <meta property="og:description" content="წიგნები, ხელნაწერები, ნივთები.">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:image" content="{{ asset('images/auction/auction.jpg') }}">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta name="twitter:card" content="summary_large_image">
@endsection
@section('content')
 


    <div class="d-flex justify-content-between align-items-center mb-4" 
    style="position: relative; margin-bottom:25px; padding-top:45px; align-items: left;
    justify-content: left;">
   <h5 class="section-title">
      <strong>
            <i class="bi bi-graph-up"></i> {{ __('messages.bookAuctions') }}
        </strong>
    </h5>

    @auth
        <a href="{{ route('auction.submit') }}" class="btn btn-primary">
            ➕ აუქციონის შექმნა
        </a>
    @else
        <a href="{{ route('login') }}" class="btn btn-outline-primary">
            ავტორიზაცია აუქციონის შესაქმნელად
        </a>
    @endauth
</div>


<a href="{{ route('auction.rules') }}" class="btn btn-outline-dark mb-3" target="_self">
    📜 ნახე აუქციონის წესები
</a>




    <!-- Featured Books -->
    <nav class="container mt-5" style="position:relative; min-height: 400px;">
   

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
        <h5 class="mb-1">✔ აუქციონი მიღებულია</h5>
        <p class="mb-0">
            {{ session('success') }}
        </p>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

    <div class="row" >
        @forelse($auctions as $auction)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    @php
    $images = $auction->book?->images ?? collect();
    $mainImage =
        $images->first()->path
        ?? $auction->book?->photo
        ?? null;
@endphp

@if ($mainImage)
    <img src="{{ asset('storage/' . $mainImage) }}"
         class="card-img-top"
         alt="{{ $auction->book?->title }}"
         style="object-fit: contain; height: 250px;">
@else
    <img src="{{ asset('public/uploads/default-book.jpg') }}"
         class="card-img-top"
         style="object-fit: contain; height: 250px;">
@endif

                    <div class="card-body">
                        <h5 class="card-title">{{ $auction->book?->title }}</h5>
                        <p>💰 მიმდინარე: <strong>{{ number_format($auction->current_price, 2) }}  <img src="{{ asset('images/GEL.png') }}" width="20px"></strong></p>
                        <p>⏳ სრულდება: {{ \Carbon\Carbon::parse($auction->end_time)->diffForHumans() }}</p>
                        <a href="{{ route('auction.show', $auction->id) }}" class="btn btn-primary w-100">აუქციონის ნახვა</a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">ამჟამად აქტიური აუქციონი არ გვაქვს.</p>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $auctions->links() }}
    </div>
</div>
@endsection
