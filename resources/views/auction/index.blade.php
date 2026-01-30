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
            <div class="btn-group view-toggle" role="group">
                <button class="btn btn-outline-secondary active" data-view="grid">
                    <i class="bi bi-grid-3x3-gap"></i>
                </button>
                <button class="btn btn-outline-secondary" data-view="list">
                    <i class="bi bi-list"></i>
                </button>
            </div>


           <form method="GET"
      action="{{ route('auction.index') }}"
      class="auction-filter-form">
    <select name="category"
        class="form-select auction-category-select"
        onchange="this.form.submit()">

        <option value=""><span>ყველა კატეგორია</span></option>

        @foreach($categories as $category)
            <option value="{{ $category->slug }}"
                {{ request('category') === $category->slug ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</form>



            <a href="{{ route('auction.rules') }}" class="btn btn-outline-secondary">
                <i class="bi bi-file-text"></i> <span style="font-size:14px; font: weight 100;">წესები</span>
            </a>

            @auth
            <a href="{{ route('auction.submit') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> 
                <span style="font-size:14px; font: weight 100;">
                    აუქციონის შექმნა
                    </span>
            </a>
            @else
            <a href="{{ route('login') }}" class="btn btn-outline-primary">
             <span style="font-size:14px; font: weight 100;">   ავტორიზაცია</span>
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

    <div class="row g-4 auction-container grid-view">
        @forelse($auctions as $auction)
        @php
        $endsSoon = \Carbon\Carbon::parse($auction->end_time)->diffInMinutes(now(), false) >= -60;
        $auctionUrl = route('auction.show', $auction->id);
        @endphp

        @php
        $images = $auction->book?->images ?? collect();
        $mainImage = $images->first()->path ?? $auction->book?->photo;
        @endphp

        <div class="col-12 col-sm-6 col-lg-4">
            <div class="auction-card">
                <div class="auction-image">

                    <a href="{{ $auctionUrl }}" class="auction-image d-block text-decoration-none">
                        <img src="{{ $mainImage ? asset('storage/'.$mainImage) : asset('images/default-book.jpg') }}"
                            alt="{{ $auction->book?->title }}">
                    </a>

                    <span class="auction-badge {{ $endsSoon ? 'ending-soon' : '' }}">
                        <i class="bi bi-clock"></i>
                        {{ \Carbon\Carbon::parse($auction->end_time)->diffForHumans() }}
                    </span>

                </div>

                <div class="auction-body">
                    <h3 class="auction-book-title mb-1">
                        <a href="{{ $auctionUrl }}" class="text-dark text-decoration-none">
                            {{ $auction->book?->title }}
                        </a>
                    </h3>

                    @if($auction->auctionCategory)
                    <div class="auction-category">
                        {{ $auction->auctionCategory->name }}
                    </div>
                    @endif



                    @php
                    $priceUp = $auction->current_price > $auction->starting_price;
                    @endphp

                    <div class="auction-price">
                        <span>
                            მიმდინარე ფასი
                            @if($priceUp)
                            <i class="bi bi-arrow-up text-success"></i>
                            @else
                            <i class="bi bi-dash text-muted"></i>
                            @endif
                        </span>

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
            ამჟამად ეს კატეგორია ცარიელია
        </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $auctions->links() }}
    </div>
</div>

<script>
    document.querySelectorAll('.view-toggle button').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.view-toggle button')
                .forEach(b => b.classList.remove('active'));

            this.classList.add('active');

            const container = document.querySelector('.auction-container');
            container.classList.toggle('list-view', this.dataset.view === 'list');
            container.classList.toggle('grid-view', this.dataset.view === 'grid');
        });
    });
</script>


@endsection