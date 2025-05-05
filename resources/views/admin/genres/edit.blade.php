@extends('admin.layouts.app')

@section('title', 'ჟანრის რედაქტირება')

@section('content')
    <div class="mb-3">
        <h1>ჟანრის რედაქტირება</h1>
    </div>

    <form action="{{ route('admin.genres.update', $genre) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>დასახელება</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $genre->name) }}" required>
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">განახლება</button>
        <a href="{{ route('admin.genres.index') }}" class="btn btn-secondary">დაბრუნება</a>
    </form>
@endsection
