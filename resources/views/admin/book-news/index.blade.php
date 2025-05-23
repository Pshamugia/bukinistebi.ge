@extends('admin.layouts.app')

@section('title', 'Manage Book News')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h1>ბუკინისტური ამბები</h1>
    <a href="{{ route('admin.book-news.create') }}" class="btn btn-primary"><i class="bi bi-megaphone"></i> დაამატე ამბავი</a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>სათაური</th>
                <th>სურათი</th>
                <th>ქმედება</th>
            </tr>
        </thead>
        <tbody>
            @foreach($news as $item)
            <tr>
                <td>{{ $item->title }}</td>
                <td>
                    @if (isset($item->image))
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="img-thumbnail" width="150">
                @endif
                </td>
                <td>
                    <a href="{{ route('admin.book-news.edit', $item->id) }}" class="btn btn-info btn-sm">Edit</a>
                    <form action="{{ route('admin.book-news.destroy', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $news->links() }}
</div>
@endsection
