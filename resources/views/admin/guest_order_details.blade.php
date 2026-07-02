@extends('admin.layouts.app')

@section('title', 'Guest users purchase')

@section('content')
@php
    $failedStatuses = ['failed', 'declined', 'canceled', 'cancelled', 'expired'];
    $successfulStatuses = ['paid', 'succeeded', 'delivered'];
    $statusKey = strtolower((string) $order->status);
    $isFailedOrder = in_array($statusKey, $failedStatuses, true);
    $isSuccessfulOrder = $order->payment_method === 'courier' || in_array($statusKey, $successfulStatuses, true);
    $statusClass = $isFailedOrder ? 'danger' : ($isSuccessfulOrder ? 'success' : 'secondary');
    $statusLabel = $order->payment_method === 'courier' ? 'კურიერთან გადახდა' : ($order->status ?: '-');
@endphp

<style>
    .guest-order-page { max-width: 1180px; }
    .guest-order-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
    }
    .guest-order-title { margin: 0; font-size: 24px; font-weight: 700; }
    .guest-order-muted { color: #6c757d; font-size: 14px; }
    .guest-order-actions { display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 8px; }
    .guest-order-panel {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        background: #fff;
        margin-bottom: 18px;
        padding: 16px;
    }
    .guest-order-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px 16px;
    }
    .guest-order-label {
        margin-bottom: 4px;
        color: #6c757d;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .guest-order-value {
        color: #212529;
        font-size: 15px;
        font-weight: 600;
        overflow-wrap: anywhere;
    }
    .guest-order-table { margin-bottom: 0; vertical-align: middle; }
    .guest-order-table thead th {
        background: #f8f9fa;
        color: #495057;
        font-size: 13px;
        font-weight: 700;
    }
    .guest-order-book-title { font-weight: 700; text-decoration: none; }
    .guest-order-source, .guest-order-desc {
        display: block;
        margin-top: 4px;
        color: #6c757d;
        font-size: 13px;
    }
    .guest-order-table .btn.btn-dark {
        display: none;
    }
    .guest-order-table span[style] {
        color: #6c757d !important;
        font-weight: 600 !important;
    }
    @media (max-width: 991.98px) {
        .guest-order-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 575.98px) {
        .guest-order-header { display: block; }
        .guest-order-actions { justify-content: flex-start; margin-top: 12px; }
        .guest-order-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="container guest-order-page">
    <div class="guest-order-header">
        <div>
            <h2 class="guest-order-title">Guest Order #{{ $order->id }}</h2>
            <div class="guest-order-muted">{{ $order->created_at?->format('M d, Y | H:i:s') }}</div>
        </div>
        <div class="guest-order-actions">
            <a href="{{ route('admin.order.label', $order->id) }}"
                class="btn btn-dark btn-sm"
                target="_blank">
                <i class="bi bi-printer"></i> ბეჭდვა
            </a>

            @if($order->delivery_latitude && $order->delivery_longitude)
            <a href="https://www.openstreetmap.org/?mlat={{ $order->delivery_latitude }}&mlon={{ $order->delivery_longitude }}#map=18/{{ $order->delivery_latitude }}/{{ $order->delivery_longitude }}"
                class="btn btn-outline-primary btn-sm"
                target="_blank"
                rel="noopener">
                <i class="bi bi-geo-alt"></i> Map
            </a>
            @endif

            @if($isFailedOrder)
            <form action="{{ route('admin.orders.failed.delete', $order->id) }}"
                method="POST"
                onsubmit="return confirm('წავშალოთ failed guest order?');"
                style="display:inline-block">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm">
                    <i class="bi bi-trash"></i> Failed-ის წაშლა
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="guest-order-panel">

    <div class="guest-order-grid">
        <div>
            <div class="guest-order-label">Name</div>
            <div class="guest-order-value">{{ $order->name ?: 'Guest' }}</div>
        </div>
        <div>
            <div class="guest-order-label">Phone</div>
            <div class="guest-order-value">
        @php
        $raw    = (string) $order->phone;
        $digits = preg_replace('/\D+/', '', $raw);   // keep only digits
        $local9 = substr($digits, -9);               // last 9 digits (e.g., 599999999)
    
        // Default fallbacks
        $displayPhone = $raw;
        $telPhone     = $raw;
    
        if (strlen($local9) === 9 && $local9[0] === '5') {
            // Format: 599-99-99-99
            $displayPhone = preg_replace('/^(\d{3})(\d{2})(\d{2})(\d{2})$/', '$1-$2-$3-$4', $local9);
    
            // Always dial with +995
            $telPhone = '+995' . $local9;
        }
    @endphp
    
    <a href="tel:{{ $telPhone }}" style="text-decoration: none; color: inherit;">
        {{ $displayPhone }}
    </a>
    
            </div>
        </div>
        <div>
            <div class="guest-order-label">E-mail</div>
            <div class="guest-order-value">{{ $order->email ?: '-' }}</div>
        </div>

        <div>
            <div class="guest-order-label">Address</div>
            <div class="guest-order-value">{{ $order->address ?: '-' }}</div>
        </div>
        <div>
            <div class="guest-order-label">Coordinates</div>
            <div class="guest-order-value">
                @if($order->delivery_latitude && $order->delivery_longitude)
                {{ $order->delivery_latitude }}, {{ $order->delivery_longitude }}
                @else
                -
                @endif
            </div>
        </div>
        <div>
            <div class="guest-order-label">City</div>
            <div class="guest-order-value">{{ $order->city ?: '-' }}</div>
        </div>
        <div>
            <div class="guest-order-label">Total</div>
            <div class="guest-order-value">{{ number_format((float) $order->total, 2) }} ლარი</div>
        </div>
        <div>
            <div class="guest-order-label">Status</div>
            <div class="guest-order-value">
                <span class="badge bg-{{ $statusClass }}">
                    {{ $statusLabel }}
                </span>
            </div>
        </div>
        <div>
            <div class="guest-order-label">Courier</div>
            <div class="guest-order-value">
                <form action="{{ route('admin.orders.assign_courier', $order->id) }}" method="POST">
                    @csrf
                    <select name="courier_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">არ არის მინიჭებული</option>
                        @foreach($couriers as $courier)
                            <option value="{{ $courier->id }}" {{ (int) $order->courier_id === (int) $courier->id ? 'selected' : '' }}>
                                {{ $courier->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>
    </div>

    <div class="guest-order-panel">
    <h4 class="mb-3">Purchased Books</h4>
    <div class="table-responsive">
    <table class="table table-hover guest-order-table">
        <thead>
            <tr>
                <th>Product</th>
                <th class="text-center">სტატუსი</th>
                <th class="text-center">Qty</th>
                <th>DATE</th>
                <th class="text-end">Unit Price</th>
                <th class="text-end">Line Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($order->orderItems as $item)
                @php
                    $lineTotal = (float) $item->price * (int) $item->quantity;
                @endphp
                <tr>
                    <td><a href="{{ route('full', ['title' => Str::slug($item->book->title), 'id' => $item->book->id]) }}" target="_blank">
                        {{ $item->book->title }}
                    </a>
                     <a href="{{ route('admin.order.label', $order->id) }}" 
       class="btn btn-dark btn-sm mt-1" target="_blank">
       🖨️ ბეჭდვა
    </a>
                
                
                    ({{ $item->quantity }})
                    @if($item->book->publisher)
                       <Span style="color:red; font-weight: bold"> — <small>ბუკინისტი: {{ $item->book->publisher->name }}</small> </Span>
                    @else
                        — <small>ჩემი წიგნებიდან</small>
                    @endif

                      @if($item->book->full ?? null)
<span style="font-size: 14px;">
    {!! \Illuminate\Support\Str::limit($item->book->full, 25) !!}
</span>
                             @endif
                </td>
                    <td class="text-center">
                        <span class="badge bg-{{ $statusClass }}">
                            {{ $statusLabel }}
                        </span>
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>

                    <td class="text-end">{{ number_format((float) $item->price, 2) }} ლარი</td>
                    <td class="text-end">{{ number_format($lineTotal, 2) }} ლარი</td>
                    
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">პროდუქტები ვერ მოიძებნა</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    </div>

    <a href="{{ route('admin.users_transactions') }}" class="btn btn-outline-secondary">
        დაბრუნება
    </a>
</div>
@endsection
