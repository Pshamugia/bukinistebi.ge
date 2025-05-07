@extends('admin.layouts.app')

@section('title', 'Authors')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h1>ავტორები</h1>
        <a href="{{ route('admin.authors.create') }}" class="btn btn-primary"><i class="bi bi-person"></i> დაამატე ავტორი</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($authors->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>სახელი გვარი</th>
                    <th width="200px">ქმედება</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($authors as $author)
                    <tr>
                        <td>{{ $author->name }}</td>
                        <td>
                            <a href="{{ route('admin.authors.edit', $author) }}" class="btn btn-sm btn-info">Edit</a>
                            <form action="{{ route('admin.authors.destroy', $author) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this author?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No authors available.</p>
    @endif
@endsection
