@extends('admin.layouts.app')
 

@section('title', 'User Details')

@section('content')
@php
use Illuminate\Support\Str;
$hasOrders = $user->orders && $user->orders->isNotEmpty();
$latestOrder = $hasOrders ? $user->orders->first() : null; // newest first if controller orders desc
@endphp

<style>
    .admin-user-page {
        max-width: 1180px;
    }

    .admin-user-page h2 {
        margin: 0 0 4px;
        font-size: 24px;
        font-weight: 700;
    }

    .admin-user-page > h4,
    .admin-user-page > p {
        display: none;
    }

    .admin-user-summary,
    .admin-user-orders {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        background: #fff;
        margin: 18px 0;
        padding: 16px;
    }

    .admin-user-summary {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px 18px;
    }

    .admin-user-metric {
        border-left: 3px solid #dee2e6;
        padding-left: 12px;
    }

    .admin-user-label {
        margin-bottom: 4px;
        color: #6c757d;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .admin-user-value {
        color: #212529;
        font-size: 15px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .admin-user-value.money {
        font-size: 18px;
    }

    .admin-user-page > .btn-warning {
        display: none;
    }

    .admin-user-orders .table {
        margin-bottom: 0;
        vertical-align: middle;
    }

    .admin-user-orders thead th {
        background: #f8f9fa;
        color: #495057;
        font-size: 13px;
        font-weight: 700;
        border-bottom: 1px solid #dee2e6;
    }

    .admin-user-orders tbody tr {
        border-color: #edf0f2;
    }

    .admin-user-orders ul {
        margin: 0;
        padding-left: 18px;
    }

    .admin-user-orders li {
        margin-bottom: 6px;
    }

    .admin-user-orders li:last-child {
        margin-bottom: 0;
    }

    .admin-user-orders a {
        font-weight: 700;
        text-decoration: none;
    }

    .admin-user-orders span[style] {
        color: #6c757d !important;
        font-weight: 600 !important;
    }

    .admin-user-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .admin-user-order-id {
        display: block;
        margin-bottom: 6px;
        font-weight: 700;
    }

    .admin-user-page .btn-sm {
        border-radius: 6px;
        font-weight: 600;
    }

    @media (max-width: 991.98px) {
        .admin-user-summary {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 575.98px) {
        .admin-user-summary {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container admin-user-page">






    <h2>{{ __('მომხმარებლის დეტალები') }}</h2>
    <div class="text-muted mb-3">Registered user order history</div>

    <h4>{{ __('სახელი:') }} {{ $user->name }}</h4>
    <p>{{ __('Email:') }} {{ $user->email }}</p>

    @if($hasOrders)
    <p>
        {{ __('ტელეფონი:') }}
        @php
        // pick the source
        $raw = preg_replace('/\D+/', '', $latestOrder->phone ?? ''); // or $order->phone

        // strip country code if present
        if (strpos($raw, '995') === 0) {
        $raw = substr($raw, 3);
        }

        // Format to 599-99-99-99 if it’s 9 digits, else fallback to original
        $displayPhone = preg_match('/^\d{9}$/', $raw)
        ? preg_replace('/^(\d{3})(\d{2})(\d{2})(\d{2})$/', '$1-$2-$3-$4', $raw)
        : ($latestOrder->phone ?? ''); // or $order->phone

        // For the clickable link, E.164 is safest (keeps +995 inside tel:),
        // but if you truly want the link without +995, set $telPhone = $raw instead.
        $telPhone = '+995' . $raw;
        @endphp

        <a href="tel:{{ $telPhone }}" style="text-decoration: none; color: inherit;">
            {{ $displayPhone }}
        </a>

    </p>
    <p>{{ __('მისამართი:') }} {{ $latestOrder->city }}, {{ $latestOrder->address }}</p>
    @if($latestOrder->delivery_latitude && $latestOrder->delivery_longitude)
    <p>
        <strong>Map:</strong>
        <a href="https://www.openstreetmap.org/?mlat={{ $latestOrder->delivery_latitude }}&mlon={{ $latestOrder->delivery_longitude }}#map=18/{{ $latestOrder->delivery_latitude }}/{{ $latestOrder->delivery_longitude }}"
            target="_blank" rel="noopener">
            {{ __('messages.openMap') }}
        </a>
        <small class="text-muted">
            ({{ $latestOrder->delivery_latitude }}, {{ $latestOrder->delivery_longitude }})
        </small>
    </p>
    @endif
    @else
    <p>{{ __('ტელეფონი:') }} {{ __('არ არის ხელმისაწვდომი') }}</p>
    <p>{{ __('მისამართი:') }} {{ __('არ არის ხელმისაწვდომი') }}</p>
    @endif

    <div class="admin-user-summary">
        <div class="admin-user-metric">
            <div class="admin-user-label">User</div>
            <div class="admin-user-value">{{ $user->name }}</div>
        </div>
        <div class="admin-user-metric">
            <div class="admin-user-label">Email</div>
            <div class="admin-user-value">{{ $user->email ?: '-' }}</div>
        </div>
        <div class="admin-user-metric">
            <div class="admin-user-label">Phone</div>
            <div class="admin-user-value">
                @if($hasOrders && $latestOrder?->phone)
                {{ $displayPhone }}
                @else
                -
                @endif
            </div>
        </div>
        <div class="admin-user-metric">
            <div class="admin-user-label">Latest Address</div>
            <div class="admin-user-value">
                @if($hasOrders)
                {{ trim(($latestOrder->city ? $latestOrder->city . ', ' : '') . ($latestOrder->address ?? '')) ?: '-' }}
                @else
                -
                @endif
            </div>
        </div>
        <div class="admin-user-metric">
            <div class="admin-user-label">New Purchase</div>
            <div class="admin-user-value money text-danger">{{ number_format((float) $newPurchaseTotal, 2) }} ლარი</div>
        </div>
        <div class="admin-user-metric">
            <div class="admin-user-label">Previous Orders</div>
            <div class="admin-user-value money">{{ number_format((float) $oldTotal, 2) }} ლარი</div>
        </div>
        <div class="admin-user-metric">
            <div class="admin-user-label">Orders</div>
            <div class="admin-user-value">{{ $hasOrders ? $user->orders->count() : 0 }}</div>
        </div>
        <div class="admin-user-metric">
            <div class="admin-user-label">Latest Date</div>
            <div class="admin-user-value">{{ $latestOrder?->created_at?->format('M d, Y | H:i') ?? '-' }}</div>
        </div>
    </div>

    <button type="button" class="btn btn-warning">
        <h4>{{ __('ახალი შეკვეთა:') }} <span class="text-danger">{{ $newPurchaseTotal }} {{ __('ლარი') }}</span></h4>
        <h4>{{ __('ძველი შეკვეთების ჯამი:') }} {{ $oldTotal }} {{ __('ლარი') }}</h4>
    </button>

    <br>

    <div class="admin-user-orders">
    <div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>{{ __('შეკვეთების ID') }}</th>
                <th>Map</th>
                <th>{{ __('ჯამი') }}</th>
                <th>{{ __('თარიღი') }}</th>
                <th>{{ __('სტატუსი') }}</th>
                <th>{{ __('პროდუქტი') }}</th>
            </tr>
        </thead>
        <tbody>
            @if($hasOrders)
            @foreach($user->orders as $order)
            @php
            $isFailedOrder = in_array(strtolower((string) $order->status), [
                'failed',
                'declined',
                'canceled',
                'cancelled',
                'expired',
            ], true);
            $isSuccessfulOrder = $order->payment_method === 'courier' || in_array(strtolower((string) $order->status), [
                'paid',
                'succeeded',
                'delivered',
            ], true);
            $statusClass = $isFailedOrder ? 'danger' : ($isSuccessfulOrder ? 'success' : 'secondary');
            @endphp
            <tr>
                <td><span class="admin-user-order-id">#{{ $order->id }}</span>



                    <a href="{{ route('admin.order.label', $order->id) }}"
                        class="btn btn-dark btn-sm mt-1" target="_blank">
                        🖨️ ბეჭდვა
                    </a>

                </td>
                <td>
                    @if($order->delivery_latitude && $order->delivery_longitude)
                    <a href="https://www.openstreetmap.org/?mlat={{ $order->delivery_latitude }}&mlon={{ $order->delivery_longitude }}#map=18/{{ $order->delivery_latitude }}/{{ $order->delivery_longitude }}"
                        class="btn btn-outline-primary btn-sm" target="_blank" rel="noopener">
                        Map
                    </a>
                    <div class="small text-muted mt-1">
                        {{ $order->delivery_latitude }}, {{ $order->delivery_longitude }}
                    </div>
                    @else
                    <span class="text-muted">-</span>
                    @endif
                </td>


                <td class="{{ $order->created_at?->gte(now()->subMinutes(5)) ? 'text-danger' : '' }}">
                    {{ $order->total }} {{ __('ლარი') }}
                </td>
                <td>{{ $order->created_at?->format('M d, Y | H:i:s') }}</td>
                <td>
                    <span class="badge bg-{{ $statusClass }}">
                        {{ $order->payment_method === 'courier' ? 'კურიერთან გადახდა' : ($order->status ?: '-') }}
                    </span>
                    @if($isFailedOrder)
                    <form action="{{ route('admin.orders.failed.delete', $order->id) }}"
                        method="POST"
                        onsubmit="return confirm('წავშალოთ failed შეკვეთა?');"
                        style="display:inline-block; margin-left: 8px;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" title="Failed შეკვეთის წაშლა">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                    @endif
                </td>
                <td>
                    @if($order->orderItems->isEmpty())
                    <em>—</em>
                    @else
                    <ul>
                        @foreach($order->orderItems as $item)
                        @if($item->book)
                        {{-- Single book line --}}
                        @php
                        $bookTitle = $item->book->title ?? '—';
                        $bookId = $item->book->id ?? null;
                        $slug = Str::slug($bookTitle);
                        @endphp
                        <li>
                            @if($bookId)
                            <a href="{{ route('full', ['title' => $slug, 'id' => $bookId]) }}" target="_blank">
                                {{ $bookTitle }}
                            </a>
                            @else
                            {{ $bookTitle }}
                            @endif
                            (× {{ $item->quantity }})
                            @if($item->book->publisher ?? null)
                            <span style="color:red; font-weight:bold">
                                — <small>ბუკინისტი: {{ $item->book->publisher->name ?? $item->book->publisher->title ?? '—' }}</small>
                            </span>
                            @else
                            — <small>ჩემი წიგნებიდან</small>
                            @endif
                            @if($item->book->full ?? null)
<span style="font-size: 14px;">
    {!! \Illuminate\Support\Str::limit($item->book->full, 25) !!}
</span>
                             @endif
                        </li>

                        @elseif($item->bundle)
                        {{-- Bundle line --}}
                        <li>
                            <strong>ბანდლი:</strong> {{ $item->bundle->title ?? '—' }} (× {{ $item->quantity }})
                            @if($item->bundle->books?->isNotEmpty())
                            <ul style="margin:4px 0 0 16px;">
                                @foreach($item->bundle->books as $b)
                                <li>{{ $b->title ?? '—' }} — × {{ $b->pivot->qty ?? 1 }}</li>
                                @endforeach
                            </ul>
                            @endif
                        </li>

                        @else
                        {{-- Missing/removed relation --}}
                        <li><em>ჩანაწერი ვერ იძებნება</em> (× {{ $item->quantity }})</li>
                        @endif
                        @endforeach
                    </ul>
                    @endif
                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="6">— {{ __('შეკვეთები არ მოიძებნა') }} —</td>
            </tr>
            @endif
        </tbody>
    </table>
    </div>
    </div>

    <a href="{{ route('admin.users_transactions') }}" class="btn btn-outline-secondary">{{ __('დაბრუნდით უკან') }}</a>
</div>
@endsection
