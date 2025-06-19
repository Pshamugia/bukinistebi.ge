@extends('layouts.app')

@section('title', 'Current Book Auctions')

@section('content')
<div class="container mt-5">
    <h2>📚 {{ __('messages.bookAuctions') }}</h2>

    <div class="row">
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
