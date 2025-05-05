@extends('admin.layouts.app')

@section('title', 'Add Book News')

@section('content')
<div class="container mt-5">
    <h2>Add New Book News</h2>

    <form action="{{ route('admin.book-news.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" name="title" required>
        </div>
        <div class="form-group mt-3">
            <label for="content">აღწერა</label>
            <textarea class="form-control" name="description" rows="5" required></textarea>
        </div>
        <div class="form-group mt-3">
            <label for="content">full</label>
            <textarea class="form-control" name="full"  id="full" rows="5" required></textarea>
        </div>
        <div class="form-group mt-3">
            <label for="image">Image</label>
            <input type="file" class="form-control" name="image">
        </div>
        <button type="submit" class="btn btn-success mt-3">Create</button>
    </form>
</div>

<script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
<script>
    // Replace the <textarea> with a CKEditor instance
    CKEDITOR.replace('full');
</script>
 
@endsection
