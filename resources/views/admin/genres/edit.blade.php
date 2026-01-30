@extends('admin.layouts.app')

@section('title', 'ჟანრის რედაქტირება')

@section('content')
    <div class="mb-3">
        <h1>ჟანრის რედაქტირება</h1>
    </div>

    <form action="{{ route('admin.genres.update', $genre) }}" method="POST">
        @csrf
        @method('PUT')

       <!-- Georgian -->
<div class="mb-3">
    <label for="name" class="form-label">სახელი (ქართული)</label>
    <input type="text" class="form-control" name="name" value="{{ old('name', $genre->name) }}">
</div>

<!-- English -->
<div class="mb-3">
    <label for="name_en" class="form-label">Name (English)</label>
    <input type="text" name="name_en" class="form-control" value="{{ old('name_en', $genre->name_en ?? '') }}">
</div>


<!-- Russian -->
    <div class="mb-3">
        <label>Название (Русский)</label>
        <input type="text" name="name_ru"
               class="form-control"
               value="{{ old('name_ru', $genre->name_ru) }}">
    </div>

        <button type="submit" class="btn btn-primary">განახლება</button>
        <a href="{{ route('admin.genres.index') }}" class="btn btn-secondary">დაბრუნება</a>
    </form>
@endsection
