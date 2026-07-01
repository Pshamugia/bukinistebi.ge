@extends('layouts.app')

@section('title', 'ბუკინისტები | შენაძენის ისტორია')

@section('content')
<style>
  .purchase-history-page {
    color: #172033;
    padding: 28px 0 46px;
  }

  .purchase-history-heading {
    align-items: center;
    border-bottom: 1px solid #e6ebf1;
    display: flex;
    justify-content: space-between;
    margin-bottom: 24px;
    margin-top: 96px;
    padding-bottom: 18px;
    position: static !important;
    top: auto !important;
  }

  .purchase-history-heading strong {
    color: #111827;
    font-size: clamp(22px, 3vw, 32px);
    font-weight: 800;
    line-height: 1.2;
  }

  .purchase-history-heading i {
    color: #475569;
    font-size: .92em;
    margin-right: 10px;
  }

  .purchase-history-panel {
    background: #fff;
    border: 1px solid #e5eaf0;
    border-radius: 8px;
    box-shadow: 0 16px 36px rgba(17, 24, 39, .07);
    margin-top: 0 !important;
    overflow: hidden;
    padding: 0;
    position: static !important;
  }

  .purchase-history-empty {
    color: #667085;
    font-size: 15px;
    margin: 0;
    padding: 36px 22px;
    text-align: center;
  }

  .purchase-history-table {
    margin: 0;
  }

  .purchase-history-table thead th {
    background: #f7f9fb;
    border-bottom: 1px solid #dbe3ec;
    color: #475467;
    font-size: 12px;
    font-weight: 800;
    letter-spacing: .04em;
    padding: 15px 18px;
    text-transform: uppercase;
    white-space: nowrap;
  }

  .purchase-history-table tbody td {
    border-color: #edf1f5;
    color: #1f2937;
    padding: 18px;
    vertical-align: middle;
  }

  .purchase-history-table tbody tr:hover {
    background: #fcfdff;
  }

  .purchase-order-id {
    color: #111827;
    font-weight: 800;
  }

  .purchase-total {
    color: #111827;
    font-size: 16px;
    font-weight: 800;
    white-space: nowrap;
  }

  .purchase-status-wrap {
    align-items: center;
    display: flex;
    gap: 12px;
    min-width: 210px;
  }

  .purchase-status-badge {
    align-items: center;
    border: 1px solid #d8dee8;
    border-radius: 999px;
    color: #2f3a4c;
    display: inline-flex;
    font-size: 13px;
    font-weight: 800;
    gap: 8px;
    line-height: 1.2;
    padding: 9px 12px;
    white-space: nowrap;
  }

  .purchase-status-badge::before {
    background: #94a3b8;
    border-radius: 50%;
    content: "";
    height: 8px;
    width: 8px;
  }

  .purchase-status-badge.is-courier {
    background: #fff7ed;
    border-color: #fed7aa;
    color: #9a3412;
  }

  .purchase-status-badge.is-courier::before {
    background: #f97316;
  }

  .purchase-status-badge.is-delivered {
    background: #ecfdf5;
    border-color: #bbf7d0;
    color: #047857;
  }

  .purchase-status-badge.is-delivered::before {
    background: #10b981;
  }

  .purchase-status-badge.is-processing,
  .purchase-status-badge.is-paid,
  .purchase-status-badge.is-succeeded {
    background: #eff6ff;
    border-color: #bfdbfe;
    color: #1d4ed8;
  }

  .purchase-status-badge.is-processing::before,
  .purchase-status-badge.is-paid::before,
  .purchase-status-badge.is-succeeded::before {
    background: #3b82f6;
  }

  .courier-motion {
    flex: 0 0 82px;
    height: 40px;
    overflow: hidden;
    position: relative;
  }

  .courier-road {
    background: repeating-linear-gradient(90deg, #cbd5e1 0 12px, transparent 12px 22px);
    bottom: 3px;
    height: 2px;
    left: 0;
    opacity: .9;
    position: absolute;
    right: 0;
  }

  .courier-bike {
    bottom: 6px;
    height: 30px;
    left: 13px;
    position: absolute;
    width: 54px;
  }

  .courier-bike::before {
    background:
      linear-gradient(135deg, transparent 0 18%, #111827 18% 28%, transparent 28%),
      linear-gradient(180deg, #fb923c 0 52%, #f97316 52% 100%);
    border: 2px solid #111827;
    border-radius: 16px 18px 8px 8px;
    box-shadow:
      31px -5px 0 -8px #111827,
      37px -4px 0 -9px #111827,
      -7px 1px 0 -5px #f97316;
    content: "";
    height: 15px;
    left: 8px;
    position: absolute;
    top: 11px;
    transform: skewX(-12deg);
    width: 34px;
  }

  .courier-bike::after {
    background: #f97316;
    border: 2px solid #111827;
    border-radius: 4px 4px 7px 7px;
    box-shadow: 0 2px 0 rgba(17, 24, 39, .12);
    content: "";
    height: 13px;
    left: 3px;
    position: absolute;
    top: 2px;
    width: 13px;
  }

  .courier-rider {
    background: #111827;
    border-radius: 8px 8px 10px 10px;
    height: 16px;
    left: 25px;
    position: absolute;
    top: 2px;
    transform: rotate(-15deg);
    width: 13px;
    z-index: 2;
  }

  .courier-rider::before {
    background: #f97316;
    border: 2px solid #111827;
    border-radius: 50%;
    content: "";
    height: 9px;
    left: 1px;
    position: absolute;
    top: -9px;
    width: 9px;
  }

  .courier-wheel {
    animation: courierWheelSpin .55s linear infinite;
    background:
      linear-gradient(90deg, transparent 45%, #94a3b8 45% 55%, transparent 55%),
      linear-gradient(0deg, transparent 45%, #94a3b8 45% 55%, transparent 55%),
      radial-gradient(circle at center, #fff 0 3px, #111827 3px 7px, transparent 7px);
    border-radius: 50%;
    bottom: 0;
    height: 16px;
    position: absolute;
    width: 16px;
    z-index: 3;
  }

  .courier-wheel-front {
    left: 35px;
  }

  .courier-wheel-back {
    left: 4px;
  }

  @keyframes courierWheelSpin {
    to { transform: rotate(360deg); }
  }

  .delivered-motion {
    align-items: center;
    display: inline-flex;
    flex: 0 0 72px;
    height: 34px;
    justify-content: center;
    position: relative;
  }

  .delivered-hand {
    border-bottom: 3px solid #047857;
    border-left: 3px solid #047857;
    border-radius: 0 0 0 10px;
    bottom: 8px;
    height: 13px;
    left: 15px;
    position: absolute;
    width: 31px;
  }

  .delivered-package {
    animation: deliveredDrop 1.8s ease-in-out infinite;
    background: #d9a35f;
    border: 2px solid #7c4a13;
    border-radius: 5px;
    height: 17px;
    left: 20px;
    position: absolute;
    top: 2px;
    width: 22px;
  }

  .delivered-package::before {
    background: rgba(124, 74, 19, .45);
    content: "";
    height: 100%;
    left: 9px;
    position: absolute;
    top: 0;
    width: 2px;
  }

  .delivered-check {
    animation: deliveredPulse 1.8s ease-in-out infinite;
    border: 2px solid #10b981;
    border-radius: 50%;
    color: #047857;
    font-size: 12px;
    font-weight: 900;
    height: 20px;
    line-height: 16px;
    position: absolute;
    right: 5px;
    text-align: center;
    top: 5px;
    width: 20px;
  }

  @keyframes deliveredDrop {
    0%, 100% { transform: translateY(-5px); }
    45%, 70% { transform: translateY(7px); }
  }

  @keyframes deliveredPulse {
    0%, 40%, 100% { opacity: .45; transform: scale(.88); }
    55%, 75% { opacity: 1; transform: scale(1); }
  }

  .purchase-products {
    display: grid;
    gap: 8px;
    list-style: none;
    margin: 0;
    padding: 0;
  }

  .purchase-products li {
    align-items: center;
    background: #fbfcfd;
    border: 1px solid #eef2f6;
    border-radius: 8px;
    color: #172033;
    display: flex;
    font-weight: 700;
    justify-content: space-between;
    padding: 10px 12px;
  }

  .purchase-products .qty {
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 999px;
    color: #475569;
    flex: 0 0 auto;
    font-size: 13px;
    font-weight: 800;
    margin-left: 12px;
    padding: 4px 9px;
  }

  body.dark-mode .purchase-history-page,
  body.dark-mode .purchase-history-heading strong,
  body.dark-mode .purchase-order-id,
  body.dark-mode .purchase-total,
  body.dark-mode .purchase-products li {
    color: #f8fafc;
  }

  body.dark-mode .purchase-history-heading {
    border-color: rgba(148, 163, 184, .3);
  }

  body.dark-mode .purchase-history-panel {
    background: #111827;
    border-color: rgba(148, 163, 184, .24);
  }

  body.dark-mode .purchase-history-table thead th,
  body.dark-mode .purchase-products li {
    background: #0f172a;
    border-color: rgba(148, 163, 184, .22);
  }

  body.dark-mode .purchase-history-table tbody td {
    border-color: rgba(148, 163, 184, .18);
    color: #e5e7eb;
  }

  @media (max-width: 760px) {
    .purchase-history-heading {
      align-items: flex-start;
      flex-direction: column;
      margin-top: 44px;
      padding-left: 0;
      padding-right: 0;
    }

    .purchase-history-panel {
      border-radius: 8px;
      box-shadow: none;
    }

    .purchase-history-table thead {
      display: none;
    }

    .purchase-history-table tbody tr {
      display: block;
      padding: 16px;
    }

    .purchase-history-table tbody td {
      border: 0;
      display: flex;
      justify-content: space-between;
      padding: 8px 0;
    }

    .purchase-history-table tbody td::before {
      color: #7b8494;
      content: attr(data-label);
      font-size: 12px;
      font-weight: 800;
      letter-spacing: .04em;
      margin-right: 16px;
      text-transform: uppercase;
    }

    .purchase-history-table tbody td.purchase-products-cell {
      display: block;
    }

    .purchase-history-table tbody td.purchase-products-cell::before {
      display: block;
      margin-bottom: 10px;
    }

    .purchase-status-wrap {
      align-items: flex-end;
      flex-direction: column;
      min-width: 0;
    }
  }

  @media (prefers-reduced-motion: reduce) {
    .courier-wheel,
    .delivered-package,
    .delivered-check {
      animation: none;
    }
  }
</style>
<h5 class="container section-title purchase-history-heading">
  <strong><i class="bi bi-receipt-cutoff"></i> {{ __('messages.purchaseHistory') }}</strong>
</h5>

<div class="purchase-history-page">
<div class="container purchase-history-panel">
  {{-- IMPORTANT: Paginators don’t always have ->isEmpty(). Use total()/count(). --}}
  @if(($orders->total() ?? 0) === 0)
    <p class="purchase-history-empty">{{ __('messages.nothingPuchased') }}</p>
  @else
    <div class="table-responsive">
    <table class="table purchase-history-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>{{ __('messages.date') }}</th>
          <th>{{ __('messages.total') }}</th>
          <th>{{ __('messages.status') }}</th>
          <th>{{ __('messages.products') }}</th>
        </tr>
      </thead>
      <tbody>
        @foreach($orders as $order)
          <tr>
            @php
              $statusKey = strtolower((string) $order->status);
              $statusClass = match ($statusKey) {
                \App\Models\Order::STATUS_COURIER_PICKED_UP => 'is-courier',
                \App\Models\Order::STATUS_DELIVERED => 'is-delivered',
                'processing' => 'is-processing',
                'paid' => 'is-paid',
                'succeeded' => 'is-succeeded',
                default => '',
              };
            @endphp
            <td data-label="ID"><span class="purchase-order-id">#{{ $order->id }}</span></td>
            <td data-label="{{ __('messages.date') }}">{{ optional($order->created_at)->format('Y-m-d') }}</td>
            <td data-label="{{ __('messages.total') }}"><span class="purchase-total">{{ number_format($order->total ?? 0) }} {{ __('ლარი') }}</span></td>
            <td data-label="{{ __('messages.status') }}">
              <div class="purchase-status-wrap">
                <span class="purchase-status-badge {{ $statusClass }}">{{ \App\Models\Order::statusLabel($order->status) }}</span>

                @if($statusKey === \App\Models\Order::STATUS_COURIER_PICKED_UP)
                  <span class="courier-motion" aria-hidden="true">
                    <span class="courier-road"></span>
                    <span class="courier-bike">
                      <span class="courier-rider"></span>
                      <span class="courier-wheel courier-wheel-back"></span>
                      <span class="courier-wheel courier-wheel-front"></span>
                    </span>
                  </span>
                @elseif($statusKey === \App\Models\Order::STATUS_DELIVERED)
                  <span class="delivered-motion" aria-hidden="true">
                    <span class="delivered-hand"></span>
                    <span class="delivered-package"></span>
                    <span class="delivered-check">✓</span>
                  </span>
                @endif
              </div>
            </td>
            <td class="purchase-products-cell" data-label="{{ __('messages.products') }}">
                <ul class="purchase-products">
                    @foreach(($order->orderItems ?? collect()) as $item)
                      @php
                        // 1) Skip pure "shipping" lines if they exist in your meta
                        $isShipping = strtolower((string) data_get($item, 'meta.type', '')) === 'shipping';
                        if ($isShipping) { continue; }
                  
                        // 2) Qty: show at least 1 even if the saved value is 0/null
                        $qtyRaw = $item->quantity ?? $item->qty;
                        $qty    = (is_numeric($qtyRaw) && $qtyRaw > 0) ? (int)$qtyRaw : 1;
                  
                        // 3) Title: try bundle, then book, then common meta keys
                        $bundleTitle = optional($item->bundle)->title;
                        $bookTitle   = optional($item->book)->title;
                  
                        $metaTitle = data_get($item, 'meta.title')
                                  ?? data_get($item, 'meta.name')
                                  ?? data_get($item, 'meta.product_title')
                                  ?? data_get($item, 'meta.book_title')
                                  ?? data_get($item, 'meta.souvenir_title'); // if you use souvenirs
                  
                        $title = $bundleTitle ?? $bookTitle ?? $metaTitle;
                  
                        // Optional: attach size if present (souvenirs/clothing)
                        $size  = trim((string) ($item->size ?? data_get($item, 'meta.size', '')));
                        $sizeSuffix = $size !== '' ? " ({$size})" : '';
                      @endphp
                  
                      @if($title)
                        <li>{{ $title }}{!! $sizeSuffix !!} — × {{ $qty }}</li>
                      @else
                        <li><em>უცნობი პროდუქტი</em> — × {{ $qty }}</li>
                      @endif
                    @endforeach
                  </ul>
                  
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    </div>

    {{-- Use default pagination view (avoid custom view 500s) --}}
    {{ $orders->links('pagination.custom-pagination') }} <!-- This will generate the pagination links -->

  @endif
</div>
</div>
@endsection
