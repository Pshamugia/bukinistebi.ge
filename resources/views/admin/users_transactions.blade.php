<!-- resources/views/admin/users_transactions.blade.php -->

@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

<div class="container">
<style>
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

</style>

    <form method="GET" action="{{ route('admin.users_transactions') }}" class="row g-3 mb-4">
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


    <h2>{{ __('ტრანზაქციები') }}</h2>
    <!-- Add the download button -->
    <form method="GET" action="{{ route('admin.users.transactions.export') }}" class="row g-3 mb-3">
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

    <form method="GET" action="{{ route('admin.users_transactions') }}" class="mb-3">
        @if(request('delivery_filter') === 'not_delivered')
        <button type="submit" class="btn btn-secondary" name="delivery_filter" value="">ყველა</button>
        @else
        <button type="submit" class="btn btn-primary" name="delivery_filter" value="not_delivered">მხოლოდ დაუსრულებელი</button>
        @endif
    </form>


    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>{{ __('მომხმარებელი') }}</th>
                <th>{{ __('ახალი შეკვეთა') }}</th> <!-- New column for the new purchase amount -->
                <th>{{ __('საერთო ნავაჭრი') }}</th>
                <th>{{ __('სტატუსი') }}</th>
                <th>{{ __('მიტანა') }}</th> <!-- New column for the button -->
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
    <a href="{{ route('admin.user.details', $user->id) }}">
        {{ $user->name }}
    </a>
@else
    <a href="{{ route('admin.guest.order.details', $user->orders->first()->id) }}">
        {{ $user->name ?? 'Guest' }}
    </a>
    <span class="text-muted ms-1">Direct pay</span>
@endif

                    </div>
                </td>

                <td class="{{ $user->last_order_total >= 0 ? 'text-danger' : '' }}">
                    {{ $user->last_order_total ?? 0 }} {{ __('ლარი') }}
                    <!-- Show 0 if last_order_total is null -->
                </td>

                <td>{{ $user->orders->sum('total') }} {{ __('ლარი') }}</td>
                <td>
                    @if ($user->orders->isNotEmpty())
                    @php
                    $lastOrder = $user->orders->first();
                    $statusMap = array_change_key_case(\App\Models\Order::$statusesMap, CASE_LOWER);
                    $translatedStatus =
                    $statusMap[strtolower($lastOrder->status)] ?? $lastOrder->status;
                    @endphp

                    @if ($lastOrder->payment_method === 'courier')
                    <span style="color: red"> გადახდა კურიერთან </span>
                    @elseif ($lastOrder->payment_method === 'bank_transfer' && $lastOrder->status === 'paid')
                    გადახდილია (ბანკით)
                    @else
                    {{ $translatedStatus  }}
                    @endif
                    @else
                    არ არის
                    @endif
                </td>

                <td>
                    @if ($user->orders->isNotEmpty())
                    @php
                    $lastOrder = $user->orders->first();
                    @endphp

                    @if ($lastOrder->status !== 'delivered')
                    <form action="{{ route('admin.markAsDelivered', $lastOrder->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit"
                            class="btn btn-sm btn-danger d-flex align-items-center gap-2">
                            <i class="bi bi-x"></i> დაუსრულებელი
                        </button>
                    </form>
                    @else
                    <div class="d-flex align-items-center">

                        <form action="{{ route('admin.undoDelivered', $lastOrder->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit"
                                class="btn btn-sm btn-warning d-flex align-items-center gap-2">
                                <i class="bi bi-check-lg text-success"></i> დასრულებული
                            </button>
                        </form>



                    </div>
                    @endif
                    @else
                    {{ __('არ არის შეკვეთა') }}
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
                          placeholder="მაგ: წიგნის აღება სურს 17:00-ის შემდეგ"></textarea>
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