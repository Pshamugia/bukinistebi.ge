@extends('admin.layouts.app')

@section('title', 'Categories')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h1>კატეგორიები</h1>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary"><i class="bi bi-folder"></i> დაამატე კატეგორია</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($categories->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>დასახელება</th>
                    <th width="200px">ქმედება</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-info">Edit</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this category?');">
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
        <p>No categories available.</p>
    @endif
@endsection
