@extends('admin.layouts.app')

@section('title', 'ახალი ჟანრი')

@section('content')
    <div class="mb-3">
        <h1>დაამატე ჟანრი</h1>
    </div>

    <form action="{{ route('admin.genres.store') }}" method="POST">
        @csrf

          <!-- Georgian Name -->
          <div class="mb-3">
            <label for="name" class="form-label">სახელი (ქართული)</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <!-- English Name -->
        <div class="mb-3">
            <label for="name_en" class="form-label">Name (English)</label>
            <input type="text" class="form-control" id="name_en" name="name_en">
        </div>

        <button type="submit" class="btn btn-success">შენახვა</button>
        <a href="{{ route('admin.genres.index') }}" class="btn btn-secondary">დაბრუნება</a>
    </form>
@endsection
