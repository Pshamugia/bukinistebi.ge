@extends('layouts.app')
@php use Illuminate\Support\Str; @endphp

@section('title', 'გადახდა შესრულებულია')

@section('content')
<div class="container" style="position:relative; top:30px; min-height:420px;">

    {{-- Success header --}}
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4" role="alert" style="border-radius:14px;">
        <i class="bi bi-check-circle-fill fs-4"></i>
        <div>
            <strong>გმადლობთ! თქვენი შეკვეთა მიღებულია.</strong>
            <div class="small text-muted">
                გადახდა შესრულებულია — მიწოდების დეტალები ქვემოთ.
            </div>
        </div>
    </div>

    @php
        // Ensure relations are loaded for rendering
        $order->loadMissing('user','orderItems.book','orderItems.bundle.books');
        $phone   = $order->phone   ?? null;
        $city    = $order->city    ?? null;
        $address = $order->address ?? null;
        $orderNo = $order->order_id ?? $order->id ?? '—';
    @endphp

    {{-- Order summary card --}}
    <div class="card shadow-sm mb-4" style="border-radius:18px;">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <h1 class="h4 mb-1">შეკვეთის დეტალები</h1>
                    <div class="text-muted small">
                        შეკვეთის №: <span class="fw-semibold">{{ $orderNo }}</span>
                    </div>
                </div>

                <div class="text-end">
                    <div class="fw-semibold">გადახდის მეთოდი</div>
                    <div class="small text-muted">კურიერთან მიღებისას (COD)</div>
                </div>
            </div>

            <hr>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-person"></i>
                        <div>
                            <div class="text-muted small">მომხმარებელი</div>
                            <div class="fw-semibold">
                                {{ $order->name ?? $order->user->name ?? '—' }}
                            </div>
                            
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-telephone"></i>
                        <div>
                            <div class="text-muted small">საკონტაქტო ნომერი</div>
                            <div class="fw-semibold">
                                @if($phone)
                                    <a href="tel:{{ $phone }}" class="link-dark text-decoration-none">{{ $phone }}</a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </div>
                            <div class="small text-muted">კურიერი დაგიკავშირდებათ შეთანხმებისთვის.</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 text-md-end">
                    <button class="btn btn-outline-secondary btn-sm" onclick="window.print()" title="გადაბეჭდვა">
                        <i class="bi bi-printer"></i> ქვითრის ბეჭდვა
                    </button>
                </div>
            </div>

            @if($address || $city)
            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-geo-alt"></i>
                        <div>
                            <div class="text-muted small">მისამართი</div>
                            <div class="fw-semibold">{{ $address ?? '—' }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-building"></i>
                        <div>
                            <div class="text-muted small">ქალაქი</div>
                            <div class="fw-semibold">{{ $city ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Items list --}}
    <div class="card shadow-sm" style="border-radius:18px;">
        <div class="card-header bg-light" style="border-top-left-radius:18px; border-top-right-radius:18px;">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-bag-check"></i>
                <span class="fw-semibold">შეკვეთილი წიგნები</span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width:220px;">პროდუქტი</th>
                            <th class="text-center">რაოდენობა</th>
                            <th class="text-end">ერთეულის ფასი</th>
                            <th class="text-center">ზომა</th>
                            <th class="text-end" style="min-width:120px;">ლინკი</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($order->orderItems as $item)
                            {{-- Bundle row --}}
                            @if ($item->bundle_id && $item->bundle)
                                <tr>
                                    <td>
                                        <span class="badge bg-info-subtle text-info border border-info me-2">Bundle</span>
                                        <span class="fw-semibold">{{ $item?->bundle?->title ?? 'No Title' }}</span>
                                        <div class="small text-muted mt-1">
                                            @foreach ($item->bundle->books as $b)
                                                <span class="d-inline-block me-2">
                                                    {{ $b?->title ?? 'No Title' }} × {{ $b->pivot->qty }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">{{ number_format($item->price, 2) }} ლარი</td>
                                    <td class="text-center">—</td>
                                    <td class="text-end">
                                        @if($item->bundle->slug ?? false)
                                            <a class="btn btn-link btn-sm text-decoration-none" target="_blank"
                                               href="{{ route('bundles.show', $item->bundle->slug) }}">
                                                ნახვა <i class="bi bi-box-arrow-up-right"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            {{-- Single book row --}}
                            @elseif ($item->book)
                                <tr>
                                    <td class="fw-semibold">{{ $item?->book?->title ?? 'No Title' }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">{{ number_format($item->price, 2) }} ლარი</td>
                                    <td class="text-center">
                                        @if ($item->size)
                                            <span class="badge bg-secondary-subtle text-secondary border">{{ $item->size }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a class="btn btn-link btn-sm text-decoration-none" target="_blank"
                                           href="{{ route('full', ['title' => Str::slug($item?->book?->title ?? 'No Title'), 'id' => $item->book->id]) }}">
                                            ნახვა <i class="bi bi-box-arrow-up-right"></i>
                                        </a>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="5" class="text-muted"><em>პროდუქტი ვერ მოიძებნა</em></td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">სია ცარიელია.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="d-flex flex-wrap gap-2 mt-4">
        <a href="{{ url('/') }}" class="btn btn-primary">
            <i class="bi bi-house"></i> მთავარი
        </a>
        <a href="{{ url('/books') }}" class="btn btn-outline-primary">
            <i class="bi bi-book-half"></i> წიგნების დათვალიერება
        </a>
        <a href="{{ url('/contact') }}" class="btn btn-outline-secondary">
            <i class="bi bi-headset"></i> დახმარება
        </a>
    </div>

</div>

{{-- Print polish --}}
<style>
@media print {
  .btn, .alert, .d-flex.gap-2 a { display: none !important; }
  .card, .table { box-shadow: none !important; }
}
</style>
@endsection
