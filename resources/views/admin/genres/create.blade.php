@extends('admin.layouts.app')

@section('title', 'ახალი ჟანრი')

@section('content')
    <div class="mb-3">
        <h1>დაამატე ჟანრი</h1>
    </div>

    <form action="{{ route('admin.genres.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>დასახელება</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">შენახვა</button>
        <a href="{{ route('admin.genres.index') }}" class="btn btn-secondary">დაბრუნება</a>
    </form>
@endsection
