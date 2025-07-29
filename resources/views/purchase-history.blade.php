<!-- resources/views/purchase-history.blade.php -->

@extends('layouts.app')

@section('title', 'ბუკინისტები | შენაძენის ისტორია') 
 
@section('content')

     
<h5 class="section-title" style="position: relative; top:30px; margin-bottom:25px; padding-bottom:25px; align-items: left;
    justify-content: left;">     <strong>
        <i class="bi bi-bookmarks-fill"></i> {{ __('messages.purchaseHistory')}}
    </strong>
</h5>
<div class="container mt-5" style="position:relative; margin-top: -15px !important">

     @if($orders->isEmpty())
        <p>{{ __('messages.nothingPuchased') }}</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('ID') }}</th>
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
                        <td>{{ $order->created_at->format('Y-m-d') }}</td>
                        <td>{{ $order->total }} {{ __('ლარი') }}</td>
                        <td>{{ $order->status }}</td>
                        <td>
                            <ul>
                                @foreach($order->orderItems as $item) <!-- Assuming you have a relationship for order items -->
                                    <li>{{ $item->book->title }} ({{ $item->quantity }})</li> <!-- Adjust to match your data structure -->
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
         <!-- Add pagination links -->
         {{ $orders->links('pagination.custom-pagination') }} <!-- This will generate the pagination links -->
    @endif
</div>
@endsection
