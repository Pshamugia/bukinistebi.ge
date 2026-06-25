@extends('admin.layouts.app')

@section('title', 'Publishing')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Publishing</h2>
        <a href="{{ route('admin.publishing.create') }}" class="btn btn-primary">+ Add item</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Title</th>
                <th>Category</th>
                <th>Shop URL</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td style="width: 110px;">
                        @if($item->image_1)
                            <img src="{{ asset('storage/' . $item->image_1) }}" alt="{{ $item->title }}" style="width: 90px; height: 70px; object-fit: cover;" class="rounded">
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->category }}</td>
                    <td>
                        @if($item->shop_url)
                            <a href="{{ $item->shop_url }}" target="_blank" rel="noopener">Open</a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.publishing.edit', $item->id) }}" class="btn btn-sm btn-info">Edit</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No items found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
