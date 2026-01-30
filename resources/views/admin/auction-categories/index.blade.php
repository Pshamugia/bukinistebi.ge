@extends('admin.layouts.app')

@section('title', 'Auction Categories')

@section('content')
<div class="container">
    <h3 class="mb-4">🏷️ Auction Categories</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- ADD --}}
    <form method="POST" action="{{ route('admin.auction-categories.store') }}" class="mb-4">
        @csrf
        <div class="input-group">
            <input type="text"
                   name="name"
                   class="form-control"
                   placeholder="კატეგორიის სახელი"
                   required>
            <button class="btn btn-primary">დამატება</button>
        </div>
    </form>

    {{-- LIST --}}
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>სახელი</th>
            <th width="200">ქმედება</th>
        </tr>
        </thead>
        <tbody>
        @foreach($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>
                    <form method="POST"
                          action="{{ route('admin.auction-categories.update', $category) }}"
                          class="d-flex gap-2">
                        @csrf
                        @method('PUT')
                        <input type="text"
                               name="name"
                               value="{{ $category->name }}"
                               class="form-control">
                        <button class="btn btn-sm btn-success">შენახვა</button>
                    </form>
                </td>
                <td>
                    <form method="POST"
                          action="{{ route('admin.auction-categories.destroy', $category) }}"
                          onsubmit="return confirm('წავშალოთ?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">წაშლა</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
