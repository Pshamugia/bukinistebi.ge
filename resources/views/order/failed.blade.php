@extends('layouts.app')
@php use Illuminate\Support\Str; @endphp

@section('title', 'გადახდა ჩაიშალა')

@section('content')
<div class="container" style="position:relative; top:30px; min-height:420px;">

    {{-- Error banner --}}
    <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert" style="border-radius:14px;">
        <i class="bi bi-x-circle-fill fs-4"></i>
        <div>
            <strong>ვაი! გადახდა ვერ განხორციელდა.</strong>
            <div class="small text-muted">
                სცადეთ თავიდან ან დაგვიკავშირდით დახმარებისთვის.
            </div>
        </div>
    </div>

    @php
        // Safely load what we can (works for guests too)
        $order?->loadMissing('orderItems.book','orderItems.bundle.books');
        $orderNo = $order->order_id ?? $order->id ?? null;
        $name    = $order->name ?? $order->user->name ?? null;
        $phone   = $order->phone ?? null;
        $city    = $order->city ?? null;
        $address = $order->address ?? null;

        // If you flash a specific failure reason, surface it:
        $failureMsg = session('payment_error') ?? ($error ?? null);
    @endphp

    {{-- Summary card (optional fields shown only if present) --}}
    <div class="card shadow-sm mb-4" style="border-radius:18px;">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <h1 class="h4 mb-1">ტრანზაქცია ვერ შესრულდა</h1>
                    <div class="text-muted small">
                        @if($orderNo)
                            შეკვეთის №: <span class="fw-semibold">{{ $orderNo }}</span>
                        @else
                            შეკვეთის ნომერი მიუწვდომელია
                        @endif
                    </div>
                </div>

                <div class="text-end">
                    <div class="fw-semibold">სტატუსი</div>
                    <div class="small text-danger">წარუმატებელი გადახდა</div>
                </div>
            </div>

            @if($name || $phone || $address || $city)
            <hr>
            <div class="row g-3">
                @if($name)
                <div class="col-md-4">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-person"></i>
                        <div>
                            <div class="text-muted small">მსახ. სახელი</div>
                            <div class="fw-semibold">{{ $name }}</div>
                        </div>
                    </div>
                </div>
                @endif

                @if($phone)
                <div class="col-md-4">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-telephone"></i>
                        <div>
                            <div class="text-muted small">ტელეფონი</div>
                            <div class="fw-semibold">
                                <a href="tel:{{ $phone }}" class="link-dark text-decoration-none">{{ $phone }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if($address || $city)
                <div class="col-md-4">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-geo-alt"></i>
                        <div>
                            <div class="text-muted small">მიწოდების მისამართი</div>
                            <div class="fw-semibold">
                                {{ trim(($city ? $city.' • ' : '').($address ?? '')) ?: '—' }}
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif

            @if($failureMsg)
            <div class="alert alert-light border mt-3 mb-0">
                <div class="small text-muted">დამატებითი ინფორმაცია</div>
                <div class="fw-semibold">{{ $failureMsg }}</div>
            </div>
            @endif
        </div>
    </div>

    {{-- Items (only if order & items exist) --}}
    @if(!empty($order?->orderItems?->count()))
    <div class="card shadow-sm mb-4" style="border-radius:18px;">
        <div class="card-header bg-light" style="border-top-left-radius:18px; border-top-right-radius:18px;">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-bag-x"></i>
                <span class="fw-semibold">შეკვეთილი პროდუქცია (გადახდა ვერ შესრულდა)</span>
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
                        @foreach($order->orderItems as $item)
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Helpful tips --}}
    <div class="card shadow-sm mb-4" style="border-radius:18px;">
        <div class="card-body">
            <div class="fw-semibold mb-2">სცადეთ ეს ნაბიჯები:</div>
            <ul class="mb-0">
                <li>დაადასტურეთ ბარათის მონაცემები და საკმარისი თანხა.</li>
                <li>შეამოწმეთ ინტერნეტი ან სცადეთ სხვა ბრაუზერით/მოწყობილობით.</li>
                <li>თუ ბანკმა დაგირიცხათ „დადასტურების“ SMS, მაგრამ შეკვეთა არ ჩაირთო — დაგვიკავშირდით.</li>
            </ul>
        </div>
    </div>

    {{-- Actions --}}
    <div class="d-flex flex-wrap gap-2">
        {{-- Try again: set your own route if you have a dedicated retry endpoint --}}
        <a href="{{ url('/checkout') }}" class="btn btn-primary">
            <i class="bi bi-arrow-repeat"></i> სცადე თავიდან
        </a>
        <a href="{{ url('/cart') }}" class="btn btn-outline-primary">
            <i class="bi bi-cart"></i> კალათა
        </a>
        <a href="{{ url('/contact') }}" class="btn btn-outline-secondary">
            <i class="bi bi-headset"></i> დახმარება
        </a>
        <a href="{{ url('/') }}" class="btn btn-outline-secondary">
            <i class="bi bi-house"></i> მთავარი
        </a>
    </div>

</div>

{{-- Print: hide extra UI on printouts --}}
<style>
@media print {
  .btn, .alert, .d-flex.gap-2 a { display: none !important; }
  .card, .table { box-shadow: none !important; }
}
</style>
@endsection
