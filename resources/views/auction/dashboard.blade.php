@extends('layouts.app')
@section('title', 'ჩემი აუქციონები')

@section('content')
<div class="container mt-5" style="position: relative; top:50px; padding-bottom:25px;">
    <h2>🎯 ჩემი აუქციონები</h2>


    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <h5 class="card-title">🔢 ჯამური ბიჯები</h5>
                    <p class="h4">{{ $activeBids->count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h5 class="card-title">🏆 მოგებული აუქციონები</h5>
                    <p class="h4">{{ $wonAuctions->count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <h5 class="card-title">💰 გადახდილი</h5>
                    <p class="h4">{{ $paidAuctions->count() }}</p>
                </div>
            </div>
        </div>
    </div>
    

    {{-- 🔔 Unpaid Winning Auctions --}}
    <h4 class="mt-4">გამარჯვებული აუქციონები (გადასახდელი)</h4>
    @forelse($wonAuctions->where('is_paid', false) as $auction)
        <div class="alert alert-warning d-flex justify-content-between align-items-center">
            <div>
                <strong>@if($auction->book)
                    {{ $auction->book->title }}
                @else
                    <span class="text-danger">წიგნი წაშლილია</span>
                @endif</strong> -  გადასახდელი: {{ number_format($auction->current_price, 2) }} ₾
            </div>
            <form action="{{ route('auction.payment') }}" method="POST">
                @csrf
                <input type="hidden" name="auction_id" value="{{ $auction->id }}">
                <button type="submit" class="btn btn-success">გადახდა აუქციონისთვის</button>
            </form>
        </div>
    @empty
        <p class="text-muted">გადასახდელი აუქციონი არ გაქვს.</p>
    @endforelse


    {{-- ✅ Paid Auctions --}}
<h4 class="mt-4">გადახდილი აუქციონები</h4>
@forelse($paidAuctions as $auction)
    <div class="alert alert-success d-flex justify-content-between align-items-center">
        <div>
            <strong>@if($auction->book)
                {{ $auction->book->title }}
            @else
                <span class="text-danger">წიგნი წაშლილია</span>
            @endif</strong> - გადახდილია: {{ number_format($auction->current_price, 2) }} ₾
        </div>
        <a href="{{ route('auction.show', $auction->id) }}" class="btn btn-outline-success btn-sm">ნახვა</a>
    </div>
@empty
    <p class="text-muted">გადახდილი აუქციონი არ გაქვს.</p>
@endforelse


    {{-- 📝 My Bidding Activity --}}
    <h4 class="mt-5">ჩემი ბიჯები</h4>
    @forelse($activeBids as $bid)
        <div class="card mb-2">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    📚 <strong> @if($bid->auction && $bid->auction->book)
                        {{ $bid->auction->book->title }}
                    @else
                        <span class="text-danger">წიგნი ან აუქციონი წაშლილია</span>
                    @endif</strong><br>
                    ბიჯი: {{ number_format($bid->amount, 2) }} ₾ —
                    <small>{{ \Carbon\Carbon::parse($bid->auction->end_time)->diffForHumans() }}</small>
                </div>
                <a href="{{ route('auction.show', $bid->auction->id) }}" class="btn btn-outline-primary btn-sm">ნახვა</a>
            </div>
        </div>
    @empty
        <p class="text-muted">ჯერ ბიჯი არ გაქვს.</p>
    @endforelse
</div>
@endsection
