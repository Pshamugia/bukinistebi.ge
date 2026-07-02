<!-- resources/views/admin/users_transactions.blade.php -->

@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

<div class="container-fluid admin-transactions-page">
<style>
.admin-transactions-page {
    color: #172033;
    padding-bottom: 32px;
}

.admin-transactions-page .page-title,
.admin-transactions-page > h2 {
    color: #111827;
    font-size: 1.75rem;
    font-weight: 800;
    letter-spacing: 0;
    margin: 0 0 18px;
}

.admin-transactions-toolbar {
    background: #fff;
    border: 1px solid #e5eaf0;
    border-radius: 8px;
    box-shadow: 0 10px 26px rgba(17, 24, 39, .05);
    margin-bottom: 18px;
    padding: 18px;
}

.admin-transactions-toolbar .form-label {
    color: #667085;
    font-size: .78rem;
    font-weight: 800;
    letter-spacing: .04em;
    text-transform: uppercase;
}

.admin-transactions-toolbar .form-control {
    border-color: #d9e1ea;
    border-radius: 8px;
    min-height: 42px;
}

.admin-transactions-search {
    align-items: center;
}

.admin-transactions-search .form-control {
    min-width: min(100%, 360px);
}

.admin-transactions-export {
    align-items: end;
}

.transactions-table-card {
    background: #fff;
    border: 1px solid #e5eaf0;
    border-radius: 8px;
    box-shadow: 0 14px 32px rgba(17, 24, 39, .06);
    overflow: hidden;
}

.admin-transactions-table {
    margin-bottom: 0;
    min-width: 980px;
}

.admin-transactions-table thead th {
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

.admin-transactions-table tbody td {
    border-color: #edf1f5;
    color: #1f2937;
    padding: 16px;
    vertical-align: middle;
}

.admin-transactions-table tbody tr:hover {
    background: #fcfdff;
}

.transaction-user-link {
    color: #111827;
    font-weight: 800;
}

.transaction-money,
.admin-transactions-table td.transaction-money {
    color: #111827;
    font-weight: 800;
    white-space: nowrap;
}

.transaction-status-text {
    align-items: center;
    border-radius: 999px;
    display: inline-flex;
    font-size: .82rem;
    font-weight: 800;
    gap: 7px;
    line-height: 1.2;
    padding: 8px 11px;
}

.transaction-status-text::before {
    border-radius: 50%;
    content: "";
    height: 8px;
    width: 8px;
}

.transaction-status-text.is-courier-pay {
    background: #fff7ed;
    border: 1px solid #fed7aa;
    color: #9a3412;
}

.transaction-status-text.is-courier-pay::before {
    background: #f97316;
}

.transaction-status-text.is-paid {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    color: #1d4ed8;
}

.transaction-status-text.is-paid::before {
    background: #3b82f6;
}

.transaction-status-text.is-neutral {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #475569;
}

.transaction-status-text.is-neutral::before {
    background: #94a3b8;
}

.transaction-action-form {
    display: inline-block;
}

.transaction-action-btn {
    border-radius: 8px;
    font-weight: 800;
    min-width: 132px;
}

.transaction-courier-form {
    display: flex;
    gap: 8px;
    min-width: 230px;
}

.transaction-courier-form .form-select {
    border-radius: 8px;
    min-height: 36px;
}

    .admin-note-box {
    width: 14px;
    height: 14px;
    border-radius: 3px;
    border: 1px dashed #aaa;
    cursor: pointer;
    transition: all .2s ease;
}

.admin-note-box:hover {
    border-color: #666;
}

.admin-note-box.has-note {
    background: #dc3545; /* red */
    border: none;
}

/* Admin users table links */
.table a {
    text-decoration: none;
 }

.table a:hover {
    text-decoration: underline; /* optional: show on hover */
}

.delivery-filter-bar {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 18px;
}

.delivery-filter-bar .btn {
    border-radius: 8px;
    font-weight: 700;
    min-height: 40px;
}

@media (max-width: 767.98px) {
    .admin-transactions-toolbar {
        padding: 14px;
    }

    .admin-transactions-page .page-title {
        font-size: 1.45rem;
    }
}

</style>

    <h2>{{ __('ტრანზაქციები') }}</h2>

    <div class="admin-transactions-toolbar">
    <form method="GET" action="{{ route('admin.users_transactions') }}" class="row g-3 mb-0 admin-transactions-search">
        <div class="col-md-4">
            <input type="text"
                name="q"
                value="{{ request('q') }}"
                class="form-control"
                placeholder="სახელი, ელფოსტა, ტელეფონი ან წიგნის სახელი">
        </div>

        <div class="col-md-3">
            <button class="btn btn-primary">
                ძებნა
            </button>

            @if(request()->has('q'))
            <a href="{{ route('admin.users_transactions') }}"
                class="btn btn-outline-secondary ms-2">
                გასუფთავება
            </a>
            @endif
        </div>

        {{-- preserve delivery filter --}}
        @if(request('delivery_filter'))
        <input type="hidden" name="delivery_filter" value="{{ request('delivery_filter') }}">
        @endif
    </form>
    </div>
    <!-- Add the download button -->
    <div class="admin-transactions-toolbar">
    <form method="GET" action="{{ route('admin.users.transactions.export') }}" class="row g-3 mb-0 admin-transactions-export">
        <div class="col-md-3">
            <label for="from_date" class="form-label">დან</label>
            <input type="date" id="from_date" name="from_date" class="form-control" value="{{ request('from_date') }}">
        </div>

        <div class="col-md-3">
            <label for="to_date" class="form-label">მდე</label>
            <input type="date" id="to_date" name="to_date" class="form-control" value="{{ request('to_date') }}">
        </div>

        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">ტრანზაქციების გადმოწერა</button>
        </div>
    </form>
    </div>

    <form method="GET" action="{{ route('admin.users_transactions') }}" class="delivery-filter-bar">
        @if(request('q'))
            <input type="hidden" name="q" value="{{ request('q') }}">
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


    <div class="transactions-table-card">
    <table class="table table-hover admin-transactions-table">
        <thead>
            <tr>
                <th>{{ __('მომხმარებელი') }}</th>
                <th>{{ __('ახალი შეკვეთა') }}</th> <!-- New column for the new purchase amount -->
                <th>{{ __('საერთო ნავაჭრი') }}</th>
                <th>{{ __('სტატუსი') }}</th>
                <th>{{ __('მიტანა') }}</th> <!-- New column for the button -->
                <th>კურიერი</th>
                <th>{{ __('შეძენის თარიღი') }}</th>
                <th>წაშლა</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-2">

                  @php
    if ($user instanceof \App\Models\User) {
        // ✅ Registered user
        $noteType = 'user';
        $noteId   = $user->id;
        $noteText = $user->admin_note;
    } else {
        // ✅ Guest order
        $noteType = 'order';
        $noteId   = $user->orders->first()->id;
        $noteText = $user->orders->first()->admin_note ?? null;
    }
@endphp

{{-- ADMIN NOTE BOX (works for both) --}}
<div
    class="admin-note-box {{ $noteText ? 'has-note' : '' }}"
    data-type="{{ $noteType }}"
    data-id="{{ $noteId }}"
    data-note="{{ $noteText }}"
    onclick="openNoteModal(this)"
    title="ადმინისტრატორის შენიშვნა">
</div>

{{-- USER / GUEST NAME --}}
@if ($user instanceof \App\Models\User)
    <a class="transaction-user-link" href="{{ route('admin.user.details', $user->id) }}">
        {{ $user->name }}
    </a>
@else
    <a class="transaction-user-link" href="{{ route('admin.guest.order.details', $user->orders->first()->id) }}">
        {{ $user->name ?? 'Guest' }}
    </a>
    <span class="text-muted ms-1">Direct pay</span>
@endif

                    </div>
                </td>

                <td class="transaction-money {{ $user->last_order_total >= 0 ? 'text-danger' : '' }}">
                    {{ $user->last_order_total ?? 0 }} {{ __('ლარი') }}
                    <!-- Show 0 if last_order_total is null -->
                </td>

                <td class="transaction-money">{{ $user->total_spent ?? 0 }} {{ __('ლარი') }}</td>
                <td>
                    @if ($user->orders->isNotEmpty())
                    @php
                    $lastOrder = $user->orders->first();
                    $statusKey = strtolower((string) $lastOrder->status);
                    $translatedStatus = \App\Models\Order::statusLabel($lastOrder->status);
                    @endphp

                    @if ($lastOrder->payment_method === 'courier' && !in_array($statusKey, [\App\Models\Order::STATUS_COURIER_PICKED_UP, \App\Models\Order::STATUS_DELIVERED], true))
                    <span class="transaction-status-text is-courier-pay"> გადახდა კურიერთან </span>
                    @elseif ($lastOrder->payment_method === 'bank_transfer' && $lastOrder->status === 'paid')
                    <span class="transaction-status-text is-paid">გადახდილია (ბანკით)</span>
                    @else
                    <span class="transaction-status-text is-neutral">{{ $translatedStatus  }}</span>
                    @endif
                    @else
                    არ არის
                    @endif
                </td>

                <td>
                    @if ($user->orders->isNotEmpty())
                    @php
                    $lastOrder = $user->orders->first();
                    $deliveryStatus = strtolower((string) $lastOrder->status);
                    @endphp

                    @if ($deliveryStatus === \App\Models\Order::STATUS_DELIVERED)
                    <div class="d-flex align-items-center">

                        <form action="{{ route('admin.undoDelivered', $lastOrder->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit"
                                class="btn btn-sm btn-warning d-flex align-items-center gap-2 transaction-action-btn">
                                <i class="bi bi-check-lg text-success"></i> ჩაბარებულია
                            </button>
                        </form>



                    </div>
                    @elseif ($deliveryStatus === \App\Models\Order::STATUS_COURIER_PICKED_UP)
                    <form action="{{ route('admin.markAsDelivered', $lastOrder->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit"
                            class="btn btn-sm btn-info d-flex align-items-center gap-2 transaction-action-btn">
                            <i class="bi bi-truck"></i> კურიერმა აიღო
                        </button>
                    </form>
                    @else
                    <form action="{{ route('admin.markAsDelivered', $lastOrder->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit"
                            class="btn btn-sm btn-danger d-flex align-items-center gap-2 transaction-action-btn">
                            <i class="bi bi-x"></i> დაუსრულებელი
                        </button>
                    </form>
                    @endif
                    @else
                    {{ __('არ არის შეკვეთა') }}
                    @endif
                </td>
                <td>
                    @if ($user->orders->isNotEmpty())
                    @php $lastOrder = $user->orders->first(); @endphp
                    <form action="{{ route('admin.orders.assign_courier', $lastOrder->id) }}"
                        method="POST"
                        class="transaction-courier-form">
                        @csrf
                        <select name="courier_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">არ არის მინიჭებული</option>
                            @foreach($couriers as $courier)
                                <option value="{{ $courier->id }}" {{ (int) $lastOrder->courier_id === (int) $courier->id ? 'selected' : '' }}>
                                    {{ $courier->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                    @else
                    -
                    @endif
                </td>
                <td>{{ $user->orders->isNotEmpty() ? $user->orders->first()->created_at->format('Y-m-d') : __('არ არის') }}
                </td>
                <td>
                    @if ($user->orders->isNotEmpty())
                    @php $lastOrder = $user->orders->first(); @endphp

                    <form action="{{ route('admin.order.delete', $lastOrder->id) }}"
                        method="POST"
                        onsubmit="return confirm('მართლა წაშლა გსურთ?');"
                        style="display:inline-block">
                        @csrf
                        @method('DELETE')

                        <button class="btn btn-sm btn-danger" title="წაშლა">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                    @else
                    -
                    @endif
                </td>


            </tr>
            @endforeach
        </tbody>
    </table>
    </div>



    <!-- Pagination links -->
    {{ $users->links('pagination.custom-pagination') }} <!-- This will generate the pagination links -->
    

</div>


<div class="modal fade" id="adminNoteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">ადმინისტრატორის შენიშვნა</h5>
                <button type="button" class="btn-close" onclick="closeNoteModal()"></button>
            </div>

            <div class="modal-body">
                <textarea id="adminNoteText"
                          class="form-control"
                          rows="4"
                          placeholder="შენიშვნების გაკეთება"></textarea>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeNoteModal()">დახურვა</button>
                <button class="btn btn-danger" onclick="saveAdminNote()">შენახვა</button>
            </div>

        </div>
    </div>
</div>
<script>
let currentNoteBox = null;

/* OPEN MODAL */
function openNoteModal(el) {
    currentNoteBox = el;

    document.getElementById('adminNoteText').value =
        el.dataset.note || '';

    new bootstrap.Modal(
        document.getElementById('adminNoteModal')
    ).show();
}

/* CLOSE MODAL */
function closeNoteModal() {
    const modal = bootstrap.Modal.getInstance(
        document.getElementById('adminNoteModal')
    );
    if (modal) modal.hide();
}

/* SAVE NOTE (USER + GUEST) */
function saveAdminNote() {
    const note = document.getElementById('adminNoteText').value;

    fetch('{{ route('admin.users.admin_note') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            type: currentNoteBox.dataset.type, // user | order
            id: currentNoteBox.dataset.id,
            note: note
        })
    })
    .then(() => {
        currentNoteBox.dataset.note = note;
        currentNoteBox.classList.toggle(
            'has-note',
            note.trim() !== ''
        );
        closeNoteModal();
    });
}
</script>



@endsection
