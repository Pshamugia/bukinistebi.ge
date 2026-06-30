@extends('admin.layouts.app')

@section('title', 'მომხმარებლის ქცევის დეტალები')

@push('styles')
<style>
    .journey-page { color: #172033; }
    .journey-header, .journey-card, .timeline-panel {
        background: #fff;
        border: 1px solid #e6eaf0;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
    }
    .journey-header { padding: 22px; }
    .journey-title { font-size: 1.35rem; font-weight: 800; margin: 0; }
    .muted { color: #667085; font-size: .92rem; }
    .metric { padding: 16px; height: 100%; }
    .metric-label { color: #667085; font-size: .78rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; }
    .metric-value { color: #111827; font-size: 1.35rem; font-weight: 800; margin-top: 7px; }
    .journey-card { padding: 18px; height: 100%; }
    .card-title-sm { font-size: .98rem; font-weight: 800; margin-bottom: 14px; }
    .path-text { display: inline-block; max-width: 360px; overflow: hidden; text-overflow: ellipsis; vertical-align: bottom; white-space: nowrap; }
    .timeline { position: relative; margin: 0; padding: 0; list-style: none; }
    .timeline-item { display: grid; grid-template-columns: 128px 32px minmax(0, 1fr); gap: 14px; position: relative; padding-bottom: 18px; }
    .timeline-item::before { content: ""; position: absolute; left: 143px; top: 34px; bottom: 0; border-left: 1px solid #d0d5dd; }
    .timeline-item:last-child::before { display: none; }
    .timeline-time { color: #667085; font-size: .82rem; line-height: 1.35; padding-top: 2px; }
    .timeline-dot { align-items: center; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 50%; color: #1d4ed8; display: flex; height: 32px; justify-content: center; width: 32px; z-index: 1; }
    .timeline-body { border: 1px solid #e6eaf0; border-radius: 8px; padding: 14px; min-width: 0; }
    .timeline-title { font-weight: 800; text-decoration: none; }
    .chip { border-radius: 999px; display: inline-flex; align-items: center; gap: 6px; font-size: .78rem; font-weight: 700; padding: .32rem .52rem; }
    .chip-blue { background: #eff6ff; color: #1d4ed8; }
    .chip-green { background: #ecfdf3; color: #067647; }
    .chip-red { background: #fef3f2; color: #b42318; }
    .chip-gray { background: #f2f4f7; color: #475467; }
    .compact-list { margin: 0; padding: 0; list-style: none; }
    .compact-list li { border-bottom: 1px solid #eef2f7; padding: 10px 0; }
    .compact-list li:last-child { border-bottom: 0; }
    @media (max-width: 767.98px) {
        .journey-header { padding: 16px; }
        .timeline-item { grid-template-columns: 1fr; gap: 8px; }
        .timeline-item::before, .timeline-dot { display: none; }
        .path-text { max-width: 230px; }
    }
</style>
@endpush

@section('content')
@php
    $formatSeconds = function ($seconds) {
        $seconds = (int) $seconds;

        if ($seconds < 60) {
            return $seconds . ' წმ';
        }

        if ($seconds < 3600) {
            return floor($seconds / 60) . ' წთ ' . ($seconds % 60) . ' წმ';
        }

        return gmdate('H:i:s', $seconds);
    };

    $consentLabel = fn ($value) => [
        'accepted' => 'თანხმობა',
        'rejected' => 'უარი',
        'not_given' => 'არ არის მითითებული',
    ][$value] ?? $value;
@endphp

<div class="journey-page pb-4">
    <div class="journey-header mb-4">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div>
                <div class="muted mb-1">მომხმარებლის პროფილი</div>
                <h1 class="journey-title">{{ $user?->name ? $user->name . ' - ' . $userLabel : $userLabel }}</h1>
                <div class="muted mt-2">
                    @if($user)
                        {{ $user->phone ?: 'ტელეფონი არ არის მითითებული' }}
                        @if($user->address) · {{ $user->address }} @endif
                    @else
                        სტუმარი მომხმარებელი. კალათისა და შეკვეთების დეტალური ისტორია ხელმისაწვდომია მხოლოდ ავტორიზებულ მომხმარებელზე.
                    @endif
                </div>
            </div>
            <a href="{{ route('admin.user.preferences.purchases') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
                უკან
            </a>
        </div>
    </div>

    @if($logs->isEmpty())
        <div class="alert alert-warning">მონაცემები ვერ მოიძებნა</div>
    @else
        <div class="row g-3 mb-4">
            <div class="col-6 col-xl-3"><div class="journey-card metric"><div class="metric-label">პირველი შემოსვლა</div><div class="metric-value">{{ optional($firstVisit)->format('Y-m-d') ?: '-' }}</div><div class="muted">{{ optional($firstVisit)->format('H:i') }}</div></div></div>
            <div class="col-6 col-xl-3"><div class="journey-card metric"><div class="metric-label">ბოლო აქტივობა</div><div class="metric-value">{{ optional($lastVisit)->format('Y-m-d') ?: '-' }}</div><div class="muted">{{ optional($lastVisit)->format('H:i') }}</div></div></div>
            <div class="col-6 col-xl-3"><div class="journey-card metric"><div class="metric-label">ჯამური დრო</div><div class="metric-value">{{ $formatSeconds($totalTimeSpent) }}</div><div class="muted">{{ $logs->count() }} ვიზიტი / {{ $uniquePagesCount }} გვერდი</div></div></div>
            <div class="col-6 col-xl-3"><div class="journey-card metric"><div class="metric-label">კალათა / checkout</div><div class="metric-value">{{ $cartVisits }} / {{ $checkoutVisits }}</div><div class="muted">ვიზიტები შესაბამის გვერდებზე</div></div></div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-lg-6">
                <div class="journey-card">
                    <div class="card-title-sm">ამჟამინდელი კალათა</div>
                    @if($cart && $cart->cartItems->isNotEmpty())
                        <ul class="compact-list">
                            @foreach($cart->cartItems as $item)
                                <li class="d-flex justify-content-between gap-3">
                                    <div>
                                        <strong>{{ $item->book?->title ?? $item->bundle?->title ?? 'უცნობი პროდუქტი' }}</strong>
                                        <div class="muted">
                                            {{ $item->book?->author?->name }}
                                            @if($item->size) · ზომა: {{ $item->size }} @endif
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <strong>{{ $item->quantity }} ც.</strong>
                                        <div class="muted">{{ number_format($item->price, 2) }} ლარი</div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="muted">ამჟამად კალათაში პროდუქტი არ ჩანს. ისტორიული “დაამატა/წაშალა” event ცალკე არ ინახება, ამიტომ აქ ჩანს მიმდინარე მდგომარეობა და journey-ში კალათის გვერდზე ვიზიტები.</div>
                    @endif
                </div>
            </div>
            <div class="col-lg-6">
                <div class="journey-card">
                    <div class="card-title-sm">ბოლო შეკვეთები</div>
                    @if($orders->isNotEmpty())
                        <ul class="compact-list">
                            @foreach($orders as $order)
                                <li class="d-flex justify-content-between gap-3">
                                    <div>
                                        <strong>
                                            {{ $order->orderItems->map(fn ($item) => $item->book?->title ?? $item->bundle?->title)->filter()->join(', ') ?: 'სათაური არ ჩანს' }}
                                        </strong>
                                        <div class="muted">
                                            {{ $order->created_at->format('Y-m-d H:i') }} · {{ $order->status }}
                                            · {{ $order->order_id ?: '#' . $order->id }}
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <strong>{{ number_format($order->total, 2) }} ლარი</strong>
                                        <div class="muted">{{ $order->orderItems->sum('quantity') }} ნივთი</div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="muted">ამ მომხმარებელზე შეკვეთა არ ჩანს.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-lg-5">
                <div class="journey-card">
                    <div class="card-title-sm">ყველაზე მეტი დრო მასალებზე</div>
                    @if($topPages->isNotEmpty())
                        <ul class="compact-list">
                            @foreach($topPages as $page)
                                <li>
                                    <div class="d-flex justify-content-between gap-3">
                                        <strong class="path-text" title="{{ $page->path }}">{{ $page->title }}</strong>
                                        <span class="chip chip-blue">{{ $formatSeconds($page->time_spent) }}</span>
                                    </div>
                                    <div class="muted">{{ $page->visits }} ვიზიტი · {{ $page->path }}</div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="muted">გვერდების მონაცემი არ არის.</div>
                    @endif
                </div>
            </div>
            <div class="col-lg-7">
                <div class="timeline-panel p-3">
                    <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                        <div class="card-title-sm mb-0">ქრონოლოგიური გზა</div>
                        <div class="muted">{{ $journey->count() }} ნაბიჯი</div>
                    </div>
                    <ul class="timeline">
                        @foreach($journey as $item)
                            <li class="timeline-item">
                                <div class="timeline-time">
                                    <strong>{{ $item->log->created_at->format('H:i') }}</strong><br>
                                    {{ $item->log->created_at->format('Y-m-d') }}
                                </div>
                                <div class="timeline-dot"><i class="bi {{ $item->icon }}"></i></div>
                                <div class="timeline-body">
                                    <div class="d-flex align-items-start justify-content-between gap-3">
                                        <div class="min-w-0">
                                            <a href="{{ $item->log->page }}" target="_blank" class="timeline-title text-primary">
                                                {{ $item->title }}
                                                <i class="bi bi-box-arrow-up-right small text-muted"></i>
                                            </a>
                                            <div class="muted mt-1"><span class="path-text" title="{{ $item->path }}">{{ $item->path }}</span></div>
                                        </div>
                                        <span class="chip {{ $item->log->cookie_consent === 'accepted' ? 'chip-green' : ($item->log->cookie_consent === 'rejected' ? 'chip-red' : 'chip-gray') }}">
                                            {{ $consentLabel($item->log->cookie_consent) }}
                                        </span>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2 mt-3">
                                        <span class="chip chip-blue"><i class="bi bi-clock"></i> {{ $formatSeconds($item->log->time_spent) }}</span>
                                        <span class="chip chip-gray">{{ $item->type }}</span>
                                    </div>
                                    <div class="muted mt-3">
                                        @if($item->previous_path)
                                            მოვიდა: <span class="path-text" title="{{ $item->previous_path }}">{{ $item->previous_path }}</span>
                                        @else
                                            პირველი დაფიქსირებული გვერდი
                                        @endif
                                        <br>
                                        @if($item->next_path)
                                            შემდეგ გადავიდა: <span class="path-text" title="{{ $item->next_path }}">{{ $item->next_path }}</span>
                                        @else
                                            ბოლო დაფიქსირებული აქტივობა
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
