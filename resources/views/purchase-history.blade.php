@extends('layouts.app')

@section('title', 'ბუკინისტები | შენაძენის ისტორია')

@section('content')
<h5 class="section-title" style="position: relative; top:30px; margin-bottom:25px; padding-bottom:25px;">
  <strong><i class="bi bi-bookmarks-fill"></i> {{ __('messages.purchaseHistory') }}</strong>
</h5>

<div class="container mt-5" style="position:relative; margin-top:-15px!important">
  {{-- IMPORTANT: Paginators don’t always have ->isEmpty(). Use total()/count(). --}}
  @if(($orders->total() ?? 0) === 0)
    <p>{{ __('messages.nothingPuchased') }}</p>
  @else
    <table class="table">
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
            <td>{{ $order->id }}</td>
            <td>{{ optional($order->created_at)->format('Y-m-d') }}</td>
            <td>{{ number_format($order->total ?? 0) }} {{ __('ლარი') }}</td>
            <td>{{ $order->status ?? '' }}</td>
            <td>
                <ul class="mb-0">
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

    {{-- Use default pagination view (avoid custom view 500s) --}}
    {{ $orders->links('pagination.custom-pagination') }} <!-- This will generate the pagination links -->

  @endif
</div>
@endsection
