@extends('admin.layouts.app')

@section('title', 'Auction Participants')

@section('content')
    <div class="container mt-5">
        <h1 style="position: relative; padding-bottom: 20px; font-size: 18px; font-weight: bold" class="btn btn-warning mb-3">
            🎯 აუქციონის მონაწილეთა სტატისტიკა</h1>

        @foreach ($auctions as $auction)
            <div class="card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <strong>
                        @if ($auction->book)
                            {{ $auction->book->title }}
                        @else
                            ❌ Book not found
                        @endif
                    </strong>
                    <span>დასრულების დრო: {{ $auction->end_time->format('Y-m-d H:i') }}</span>
                </div>

                <div class="card-body">
                    <p><strong>Winner:</strong>
                        @if ($auction->winner)
                        <a href="{{ route('admin.user.details', $auction->winner->id) }}" style="text-decoration: none">  {{ $auction->winner->name }} </a>
                            @if ($auction->is_paid)
                                <span class="btn btn-success">(Paid)</span>
                            @else
                                <span class="btn btn-danger "> Not Paid </span>
                            @endif
                        @else
                            ❌ No winner
                        @endif
                    </p>
                    
                    <p><strong>Final Price:</strong> {{ number_format($auction->current_price, 2) }} ₾</p>
                    <p><strong>Paid:</strong>
                        @if ($auction->is_paid)
                            ✅ Yes
                        @else
                            ❌ No
                        @endif
                    </p>

                    <h5 class="mt-3">👥 All Bidders:</h5>
                    <ul class="list-group">
                        @foreach ($auction->bids->sortByDesc('amount') as $bid)
                            <li class="list-group-item d-flex justify-content-between">
                                <span> <a href="{{ route('admin.user.details', $bid->user->id) }}">
                                        {{ $bid->user->name }} </a></span>
                                <span>{{ number_format($bid->amount, 2) }} ₾</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
@endsection
