@extends('admin.layouts.app')

@section('title', 'ბუკინისტური შეკვეთები')

@section('content')
    <div class="container">
        <h4>მომხმარებელთა ბუკინისტური შეკვეთები</h4>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>წიგნი</th>
                    <th>ავტორი</th>
                    <th>გამოცემის წელი</th>
                    <th>კომენტარი</th>
                    <th>ელ. ფოსტა</th>
                    <th>სტატუსი</th>
                    <th>თარიღი</th>
                    {{-- <th>სტატუსი</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->title }}</td>
                        <td>{{ $order->author }}</td>
                        <td>{{ $order->publishing_year }}</td>
                        <td>{{ $order->comment }}</td>
                        <td>{{ $order->email }}</td>
                        <td>
                            <form action="{{ route('admin.book_orders.done', $order) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    ✓ დავასრულოთ?
                                </button>
                            </form>
                        </td>
                        <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                        {{-- @php
                            $statusMap = array_change_key_case(\App\Models\Order::$statusesMap, CASE_LOWER);
                            $translatedStatus = $statusMap[strtolower($order->status)] ?? $order->status;
                        @endphp
                        <td>{{ $translatedStatus }}</td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $orders->links('pagination.custom-pagination') }}
    </div>
@endsection
