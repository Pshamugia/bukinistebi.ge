@extends('layouts.app')

@section('title', 'Current Auctions')

@section('content')
<h5 class="section-title"
        style="position: relative; margin-bottom:25px; top:30px; padding-bottom:25px; align-items: left;
    justify-content: left;">
        <strong>
            <i class="bi bi-graph-up"></i> {{ __('messages.bookAuctions') }}
        </strong>
    </h5>



    <!-- Featured Books -->
    <nav class="container mt-5" style="position:relative; min-height: 400px;">
   

    <div class="row" >
        @forelse($auctions as $auction)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    @if($auction->book->photo)
                        <img src="{{ asset('storage/' . $auction->book->photo) }}" class="card-img-top" alt="{{ $auction->book->title }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $auction->book->title }}</h5>
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
