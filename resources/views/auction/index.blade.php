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
 
<style>
    /* Auction page */

.auction-hero {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}

.auction-title {
    font-size: 32px;
    font-weight: 700;
}

.auction-subtitle {
    color: #6c757d;
    margin-top: 6px;
}

.auction-actions {
    display: flex;
    gap: 10px;
}

/* Card */
.auction-card {
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,.06);
    transition: transform .2s ease, box-shadow .2s ease;
}

.auction-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 15px 40px rgba(0,0,0,.1);
}

/* Image */
.auction-image {
    position: relative;
    background: #f8f9fa;
}

.auction-image img {
    width: 100%;
    height: 260px;
    object-fit: contain;
    padding: 12px;
}

/* Badge */
.auction-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: rgba(0,0,0,.75);
    color: #fff;
    font-size: 13px;
    padding: 6px 10px;
    border-radius: 999px;
}

/* Body */
.auction-body {
    padding: 18px;
}

.auction-book-title {
    font-size: 18px;
    font-weight: 600;
    line-height: 1.4;
    margin-bottom: 12px;
}

/* Price */
.auction-price {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f1f3f5;
    padding: 10px 14px;
    border-radius: 12px;
    font-size: 15px;
}

.auction-price strong {
    font-size: 18px;
}

</style>

  <div class="container pt-5">
    <div class="auction-hero mb-4" style="padding-top:45px; ">
        <div>
            <h1 class="auction-title">
                <i class="bi bi-graph-up"></i> {{ __('messages.bookAuctions') }}
            </h1>
            <p class="auction-subtitle">
                იშვიათი წიგნები, ხელნაწერები და უნიკალური ნივთები
            </p>
        </div>

        <div class="auction-actions">
            <a href="{{ route('auction.rules') }}" class="btn btn-outline-secondary">
                <i class="bi bi-file-text"></i> წესები
            </a>

            @auth
                <a href="{{ route('auction.submit') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i> აუქციონის შექმნა
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-primary">
                    ავტორიზაცია
                </a>
            @endauth
        </div>
    </div>
</div>
 




     
    <div class="container pb-5">
    @if(session('success'))
        <div class="alert alert-success rounded-4 shadow-sm">
            <strong>✔ აუქციონი მიღებულია</strong><br>
            {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        @forelse($auctions as $auction)
            @php
                $images = $auction->book?->images ?? collect();
                $mainImage = $images->first()->path ?? $auction->book?->photo;
            @endphp

            <div class="col-12 col-sm-6 col-lg-4">
                <div class="auction-card">
                    <div class="auction-image">
                        <img src="{{ $mainImage ? asset('storage/'.$mainImage) : asset('images/default-book.jpg') }}"
                             alt="{{ $auction->book?->title }}">
                        <span class="auction-badge">
                            ⏳ {{ \Carbon\Carbon::parse($auction->end_time)->diffForHumans() }}
                        </span>
                    </div>

                    <div class="auction-body">
                        <h3 class="auction-book-title">
                            {{ $auction->book?->title }}
                        </h3>

                        <div class="auction-price">
                            <span>მიმდინარე ფასი</span>
                            <strong>
                                {{ number_format($auction->current_price, 2) }} ₾
                            </strong>
                        </div>

                        <a href="{{ route('auction.show', $auction->id) }}"
                           class="btn btn-dark w-100 mt-3">
                            აუქციონის ნახვა
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-5">
                ამჟამად აქტიური აუქციონი არ გვაქვს
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $auctions->links() }}
    </div>
</div>

@endsection
