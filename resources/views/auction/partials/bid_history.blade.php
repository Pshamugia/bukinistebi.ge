<ul class="list-group mt-2">
    @php
        $latestBids = $auction->bids->sortByDesc('created_at')->values();
        $topBidders = $auction->bids->sortByDesc('amount')->pluck('user_id')->take(3)->toArray();
    @endphp

    @forelse($latestBids as $bid)
        <li class="list-group-item d-flex justify-content-between align-items-center 
            {{ Auth::check() && $bid->user_id === Auth::id() ? 'bg-light border-primary fw-bold' : '' }}">

            <div>
                @if (in_array($bid->user_id, $topBidders))
                    @php
                        $rank = array_search($bid->user_id, $topBidders);
                        $badge = match($rank) {
                            0 => '🥇',
                            1 => '🥈',
                            2 => '🥉',
                            default => ''
                        };
                    @endphp
                    <span class="me-1">{{ $badge }}</span>
                @endif

                <strong>
@if ($bid->is_anonymous)
    ანონიმური (ID: #{{ substr(md5($bid->id), 0, 4) }})
@else
    {{ $bid->user->name }}
@endif

                </strong>
            </div>

            <div>
                <strong>{{ number_format($bid->amount, 2) }} ₾</strong>
                <small class="text-muted">({{ \Carbon\Carbon::parse($bid->created_at)->diffForHumans() }})</small>
            </div>

        </li>
    @empty
        <li class="list-group-item text-muted">ჯერჯერობით ბიჯი არ არის.</li>
    @endforelse
</ul>
