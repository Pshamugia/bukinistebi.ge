@extends('admin.layouts.app')

@section('title', 'Edit Book News')

@section('content')
<div class="container mt-5">
    <h2>Edit Book News</h2>

    <form action="{{ route('admin.book-news.update', $bookNews->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Georgian Title --}}
        <div class="form-group">
            <label for="title">Title (Georgian)</label>
            <input type="text" class="form-control" name="title" value="{{ $bookNews->title }}" required>
        </div>

        {{-- English Title --}}
        <div class="form-group mt-3">
            <label for="title_en">Title (English)</label>
            <input type="text" class="form-control" name="title_en" value="{{ $bookNews->title_en }}">
        </div>

        {{-- Georgian Description --}}
        <div class="form-group mt-3">
            <label for="description">Description (Georgian)</label>
            <textarea class="form-control" name="description" rows="5" required>{{ $bookNews->description }}</textarea>
        </div>

        {{-- English Description --}}
        <div class="form-group mt-3">
            <label for="description_en">Description (English)</label>
            <textarea class="form-control" name="description_en" rows="5">{{ $bookNews->description_en }}</textarea>
        </div>

        {{-- Georgian Full Text --}}
        <div class="form-group mt-3">
            <label for="full">Full Text (Georgian)</label>
            <textarea class="form-control" name="full" rows="5" id="full" required>{{ $bookNews->full }}</textarea>
        </div>

        {{-- English Full Text --}}
        <div class="form-group mt-3">
            <label for="full_en">Full Text (English)</label>
            <textarea class="form-control" name="full_en" rows="5" id="full_en">{{ $bookNews->full_en }}</textarea>
        </div>

        {{-- Image --}}
        <div class="form-group mt-3">
            <label for="image">Image</label><br>
            @if($bookNews->image)
                <img src="{{ asset('storage/' . $bookNews->image) }}" alt="{{ $bookNews->title }}" width="100" class="mb-2">
            @endif
            <input type="file" class="form-control" name="image">
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update</button>
    </form>
</div>

{{-- CKEditor Scripts --}}
<script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('full');
    CKEDITOR.replace('full_en');
</script>
@endsection
