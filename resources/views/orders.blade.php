@extends('layouts.app')

@section('content')
    <h1>Your Orders</h1>

    @if (session('info'))
        <p>{{ session('info') }}</p>
    @endif

    @if ($orders && $orders->isNotEmpty())
        <ul>
            @foreach ($orders as $order)
                <li>Order #{{ $order->id }} - Total: ${{ $order->total }}</li>
            @endforeach
        </ul>

        <!-- Pagination links -->
        {{ $orders->links() }}
    @else
        <p>You have no orders yet.</p>
    @endif
@endsection
