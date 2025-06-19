@extends('admin.layouts.app')

@section('title', 'Auctions')

@section('content')
<div class="container mt-4">
    <h2>ყველა აუქციონი</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif


    <a href="{{ route('admin.auction.participants') }}" class="btn btn-success mb-3">📚 აუქციონების რეზულტატები</a>


    <a href="{{ route('admin.auctions.create') }}" class="btn btn-primary mb-3">+ შექმენი ახალი აუქციონი</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>პროდუქტი</th>
                <th>საწყისი ფასი</th>
                <th>მიმდინარე ფასი</th>
                <th>დაწყების დრო</th>
                <th>დასრულების დრო</th>
                <th>სტატუსი</th>
                <th> ქმედება </th>
            </tr>
        </thead>
        <tbody>
            @forelse ($auctions as $auction)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $auction->book->title }}</td>
                    <td>{{ number_format($auction->start_price, 2) }} GEL</td>
                    <td>{{ number_format($auction->current_price, 2) }} GEL</td>
                    <td>{{ $auction->start_time }}</td>
                    <td>{{ $auction->end_time }}</td>
                    <td>{{ $auction->is_active ? 'აქტიური' : 'დასრულებული' }}</td>
                    <td><a href="{{ route('admin.auctions.edit', $auction) }}" class="btn btn-sm btn-info">Edit</a></td>

                </tr>
            @empty
                <tr>
                    <td colspan="7">აუქციონი არ მოიძებნა.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $auctions->links() }}
</div>
@endsection
