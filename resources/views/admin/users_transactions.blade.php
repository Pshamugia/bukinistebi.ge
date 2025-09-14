<!-- resources/views/admin/users_transactions.blade.php -->

@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

    <div class="container">
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
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>  @if(is_object($user) && isset($user->id) && $user->id)
                            {{-- ✅ Logged user – link to user details --}}
                            <a href="{{ route('admin.user.details', $user->id) }}">
                                {{ $user->name }}
                            </a>
                        @elseif(isset($user->orders) && $user->orders->isNotEmpty())
                            {{-- ✅ Guest order – link to guest order details --}}
                            <a href="{{ route('admin.guest.order.details', $user->orders->first()->id) }}">
                                {{ $user->name }} 
                            </a> Direct pay 
                        @else
                            {{-- Fallback --}}
                            {{ $user->name ?? 'Guest' }}
                        @endif</td>
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
                    </tr>
                @endforeach
            </tbody>
        </table>

        

        <!-- Pagination links -->
        {{ $users->links('pagination.custom-pagination') }} <!-- This will generate the pagination links -->

    </div>
@endsection
