@extends('admin.layouts.app')

@section('title', 'Courier Orders')

@section('content')
<div class="container-fluid courier-orders-page">
<style>
.courier-orders-page {
    color: #172033;
    padding-bottom: 32px;
}

.courier-orders-page h2 {
    color: #111827;
    font-size: 1.75rem;
    font-weight: 800;
    margin: 0 0 18px;
}

.courier-toolbar,
.courier-table-card {
    background: #fff;
    border: 1px solid #e5eaf0;
    border-radius: 8px;
    box-shadow: 0 10px 26px rgba(17, 24, 39, .05);
}

.courier-toolbar {
    margin-bottom: 18px;
    padding: 18px;
}

.courier-filter-bar {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 18px;
}

.courier-filter-bar .btn,
.courier-action-btn {
    border-radius: 8px;
    font-weight: 800;
}

.courier-table-card {
    overflow: hidden;
}

.courier-table {
    margin-bottom: 0;
    min-width: 1080px;
}

.courier-table thead th {
    background: #f7f9fb;
    border-bottom: 1px solid #dbe3ec;
    color: #475467;
    font-size: .76rem;
    font-weight: 800;
    letter-spacing: .04em;
    padding: 14px 16px;
    text-transform: uppercase;
    white-space: nowrap;
}

.courier-table tbody td {
    border-color: #edf1f5;
    color: #1f2937;
    padding: 16px;
    vertical-align: middle;
}

.courier-table tbody tr.is-delivered {
    background: #f8fafc;
    opacity: .48;
}

.courier-table tbody tr.is-delivered:hover {
    opacity: .64;
}

.courier-status {
    border-radius: 999px;
    display: inline-flex;
    font-size: .82rem;
    font-weight: 800;
    padding: 8px 11px;
}

.courier-note-box {
    border: 1px dashed #98a2b3;
    border-radius: 4px;
    cursor: pointer;
    display: inline-flex;
    height: 18px;
    margin-right: 8px;
    transition: all .2s ease;
    vertical-align: middle;
    width: 18px;
}

.courier-note-box:hover {
    border-color: #475467;
}

.courier-note-box.has-note {
    background: #dc3545;
    border-color: #dc3545;
}

.courier-note-btn {
    align-items: center;
    border-radius: 8px;
    display: inline-flex;
    font-weight: 800;
    gap: 8px;
}
</style>

    <h2>კურიერის შეკვეთები</h2>

    <div class="courier-toolbar">
        <form method="GET" action="{{ route('admin.courier_transactions') }}" class="row g-3 mb-0">
            <div class="col-md-4">
                <input type="text"
                    name="q"
                    value="{{ request('q') }}"
                    class="form-control"
                    placeholder="სახელი, ტელეფონი, მისამართი ან წიგნი">
            </div>
            <div class="col-md-2">
                <input type="date"
                    name="from_date"
                    value="{{ request('from_date') }}"
                    class="form-control"
                    title="დან">
            </div>
            <div class="col-md-2">
                <input type="date"
                    name="to_date"
                    value="{{ request('to_date') }}"
                    class="form-control"
                    title="მდე">
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary">ძებნა</button>
                @if(request()->hasAny(['q', 'from_date', 'to_date', 'delivery_filter']))
                    <a href="{{ route('admin.courier_transactions') }}" class="btn btn-outline-secondary ms-2">გასუფთავება</a>
                @endif
            </div>
            @if(request('delivery_filter'))
                <input type="hidden" name="delivery_filter" value="{{ request('delivery_filter') }}">
            @endif
        </form>
    </div>

    <form method="GET" action="{{ route('admin.courier_transactions') }}" class="courier-filter-bar">
        @if(request('q'))
            <input type="hidden" name="q" value="{{ request('q') }}">
        @endif
        @if(request('from_date'))
            <input type="hidden" name="from_date" value="{{ request('from_date') }}">
        @endif
        @if(request('to_date'))
            <input type="hidden" name="to_date" value="{{ request('to_date') }}">
        @endif

        @php
            $activeDeliveryFilter = request('delivery_filter');
            $deliveryFilters = [
                '' => ['label' => 'ყველა', 'icon' => 'bi-list-ul'],
                'not_delivered' => ['label' => 'დაუსრულებელი', 'icon' => 'bi-hourglass-split'],
                'courier_picked_up' => ['label' => 'კურიერმა აიღო', 'icon' => 'bi-truck'],
                'delivered' => ['label' => 'ჩაბარებულია', 'icon' => 'bi-check-circle'],
            ];
        @endphp

        @foreach($deliveryFilters as $filterValue => $filter)
            <button
                type="submit"
                class="btn {{ $activeDeliveryFilter === $filterValue || (!$activeDeliveryFilter && $filterValue === '') ? 'btn-primary' : 'btn-outline-primary' }}"
                name="delivery_filter"
                value="{{ $filterValue }}">
                <i class="bi {{ $filter['icon'] }}"></i>
                {{ $filter['label'] }}
            </button>
        @endforeach
    </form>

    <div class="courier-table-card">
        <table class="table table-hover courier-table">
            <thead>
                <tr>
                    <th>შეკვეთა</th>
                    <th>მყიდველი</th>
                    <th>მისამართი</th>
                    <th>გადახდა</th>
                    <th>ჯამი</th>
                    <th>სტატუსი</th>
                    <th>მოქმედება</th>
                    <th>თარიღი</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    @php
                        $deliveryStatus = strtolower((string) $order->status);
                        $statusLabel = \App\Models\Order::statusLabel($order->status);
                        $buyerName = $order->user?->name ?: ($order->name ?: 'Guest');
                    @endphp
                    <tr class="{{ $deliveryStatus === \App\Models\Order::STATUS_DELIVERED ? 'is-delivered' : '' }}">
                        <td>
                            <strong>#{{ $order->id }}</strong>
                            @if(auth()->user()->isAdmin())
                                <div class="text-muted small mt-1">კურიერი: {{ $order->courier?->name ?: '-' }}</div>
                            @endif
                            <div class="mt-2">
                                <button
                                    type="button"
                                    class="btn btn-outline-secondary btn-sm courier-note-btn"
                                    data-id="{{ $order->id }}"
                                    data-note="{{ $order->courier_note }}"
                                    onclick="openCourierNoteModal(this)">
                                    <span class="courier-note-box {{ $order->courier_note ? 'has-note' : '' }}"></span>
                                    კურიერის კომენტარი
                                </button>
                            </div>
                        </td>
                        <td>
                            <strong>{{ $buyerName }}</strong>
                            <div class="text-muted small">{{ $order->phone ?: '-' }}</div>
                            <div class="text-muted small">{{ $order->email ?: '-' }}</div>
                        </td>
                        <td>
                            {{ trim(($order->city ? $order->city . ', ' : '') . ($order->address ?? '')) ?: '-' }}
                            @if($order->delivery_latitude && $order->delivery_longitude)
                                <div class="mt-2">
                                    <a href="https://www.openstreetmap.org/?mlat={{ $order->delivery_latitude }}&mlon={{ $order->delivery_longitude }}#map=18/{{ $order->delivery_latitude }}/{{ $order->delivery_longitude }}"
                                        class="btn btn-outline-primary btn-sm"
                                        target="_blank"
                                        rel="noopener">
                                        <i class="bi bi-geo-alt"></i> Map
                                    </a>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>
                                {{ $order->payment_method === 'courier' ? 'კურიერთან გადახდა' : 'ბანკით გადახდა' }}
                            </strong>
                        </td>
                        <td><strong>{{ number_format((float) $order->total, 2) }} ლარი</strong></td>
                        <td>
                            <span class="courier-status bg-light border">{{ $statusLabel ?: '-' }}</span>
                            @if($order->courier_picked_up_at)
                                <div class="text-muted small mt-1">აიღო: {{ $order->courier_picked_up_at->format('Y-m-d H:i') }}</div>
                            @endif
                            @if($order->delivered_at)
                                <div class="text-muted small mt-1">ჩაბარდა: {{ $order->delivered_at->format('Y-m-d H:i') }}</div>
                            @endif
                        </td>
                        <td>
                            @if ($deliveryStatus === \App\Models\Order::STATUS_DELIVERED)
                                <button type="button" class="btn btn-sm btn-success courier-action-btn" disabled>
                                    <i class="bi bi-check-circle"></i> ჩაბარებულია
                                </button>
                            @elseif ($deliveryStatus === \App\Models\Order::STATUS_COURIER_PICKED_UP)
                                <form action="{{ route('admin.markAsDelivered', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-info courier-action-btn">
                                        <i class="bi bi-check-lg"></i> ჩაიბარა მყიდველმა
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.markAsDelivered', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-danger courier-action-btn">
                                        <i class="bi bi-truck"></i> მიიღო კურიერმა
                                    </button>
                                </form>
                            @endif
                        </td>
                        <td>{{ $order->created_at?->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">მინიჭებული შეკვეთები არ არის.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $orders->links('pagination.custom-pagination') }}
    </div>
</div>

<div class="modal fade" id="courierNoteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">კურიერის კომენტარი</h5>
                <button type="button" class="btn-close" onclick="closeCourierNoteModal()"></button>
            </div>

            <div class="modal-body">
                <textarea id="courierNoteText"
                    class="form-control"
                    rows="4"
                    placeholder="მიზეზი ან კომენტარი"></textarea>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeCourierNoteModal()">დახურვა</button>
                <button class="btn btn-danger" onclick="saveCourierNote()">შენახვა</button>
            </div>
        </div>
    </div>
</div>

<script>
let currentCourierNoteBox = null;

function openCourierNoteModal(el) {
    currentCourierNoteBox = el;
    document.getElementById('courierNoteText').value = el.dataset.note || '';

    new bootstrap.Modal(
        document.getElementById('courierNoteModal')
    ).show();
}

function closeCourierNoteModal() {
    const modal = bootstrap.Modal.getInstance(
        document.getElementById('courierNoteModal')
    );
    if (modal) modal.hide();
}

function saveCourierNote() {
    const note = document.getElementById('courierNoteText').value;
    const url = '{{ route('admin.orders.courier_note', ['order' => '__ORDER_ID__']) }}'
        .replace('__ORDER_ID__', currentCourierNoteBox.dataset.id);

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ note: note })
    })
    .then((response) => {
        if (!response.ok) {
            throw new Error('Save failed');
        }

        currentCourierNoteBox.dataset.note = note;
        const indicator = currentCourierNoteBox.querySelector('.courier-note-box');
        if (indicator) {
            indicator.classList.toggle('has-note', note.trim() !== '');
        }
        closeCourierNoteModal();
    });
}
</script>
@endsection
