<!-- resources/views/admin/users_transactions.blade.php -->

@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
 
<div class="container">
    <h2>{{ __('მომხმარებლების აქტივობები') }}</h2>
     <!-- Add the download button -->
     <a href="{{ route('admin.users.transactions.export') }}" class="btn btn-success mb-3">
        {{ __('გადმოწერე ტრანზაქციები Excel ფორმატში') }}
    </a>
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
            @foreach($users as $user)
                <tr>
                    <td><a href="{{ route('admin.user.details', $user->id) }}">{{ $user->name }}</a></td>
                    <td class="{{ $user->last_order_total >= 0 ? 'text-danger' : '' }}">
                        {{ $user->last_order_total ?? 0 }} {{ __('ლარი') }} <!-- Show 0 if last_order_total is null -->
                    </td>
                   
                    <td>{{ $user->orders->sum('total') }} {{ __('ლარი') }}</td>
                    <td>
                        @if ($user->orders->isNotEmpty())
                            @php
                                $lastOrder = $user->orders->last();
                            @endphp
                    
                            @if ($lastOrder->payment_method === 'courier')
                               <span style="color: red"> გადახდა კურიერთან </span>
                            @elseif ($lastOrder->payment_method === 'bank_transfer' && $lastOrder->status === 'paid')
                                გადახდილია (ბანკით)
                            @else
                                {{ $lastOrder->status }}
                            @endif
                        @else
                            არ არის
                        @endif
                    </td>
                    
                    <td>
                        @if ($user->orders->isNotEmpty())
                            @php
                                $lastOrder = $user->orders->last();
                            @endphp
                            
                            @if ($lastOrder->status !== 'delivered')
                                <form action="{{ route('admin.markAsDelivered', $lastOrder->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-danger d-flex align-items-center gap-2">
                                        <i class="bi bi-x"></i> დაუსრულებელი
                                    </button>
                                </form>
                            @else
                            <div class="d-flex align-items-center">
                                
                                <form action="{{ route('admin.undoDelivered', $lastOrder->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-warning d-flex align-items-center gap-2">
                                        <i class="bi bi-check-lg text-success"></i> დასრულებული
                                    </button>
                                </form>

                             
                                
                            </div>                            @endif
                        @else
                            {{ __('არ არის შეკვეთა') }}
                        @endif
                    </td>
                                        <td>{{ $user->orders->isNotEmpty() ? $user->orders->last()->created_at->format('Y-m-d') : __('არ არის') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination links -->
    {{ $users->links('pagination.custom-pagination') }} <!-- This will generate the pagination links -->

</div> 
@endsection
