@php
    $order->loadMissing(['orderItems.book', 'orderItems.bundle.books']);

    $transactionId = (string) ($order->order_id ?? $order->id ?? '');
    $gaItems = $order->orderItems
        ->map(function ($item, $index) {
            if ($item->bundle_id && $item->bundle) {
                return [
                    'item_id' => 'BUNDLE_' . $item->bundle->id,
                    'item_name' => $item->bundle->title ?? 'Bundle',
                    'item_category' => 'Bundle',
                    'price' => (float) ($item->price ?? 0),
                    'quantity' => (int) ($item->quantity ?? 1),
                    'index' => $index,
                ];
            }

            if ($item->book) {
                return [
                    'item_id' => (string) $item->book->id,
                    'item_name' => $item->book->title ?? 'Book',
                    'item_category' => 'Book',
                    'price' => (float) ($item->price ?? 0),
                    'quantity' => (int) ($item->quantity ?? 1),
                    'index' => $index,
                ];
            }

            return null;
        })
        ->filter()
        ->values();
@endphp

@if($transactionId !== '' && $gaItems->isNotEmpty())
<script>
(function () {
    var eventKey = @json('ga4_purchase_' . $transactionId);

    try {
        if (window.sessionStorage && sessionStorage.getItem(eventKey)) {
            return;
        }
    } catch (e) {}

    if (typeof gtag !== 'function') {
        return;
    }

    gtag('event', 'purchase', {
        transaction_id: @json($transactionId),
        value: {{ (float) ($order->total ?? 0) }},
        tax: 0,
        shipping: {{ (float) ($order->shipping ?? 0) }},
        currency: 'GEL',
        items: @json($gaItems)
    });

    try {
        if (window.sessionStorage) {
            sessionStorage.setItem(eventKey, '1');
        }
    } catch (e) {}
})();
</script>
@endif
