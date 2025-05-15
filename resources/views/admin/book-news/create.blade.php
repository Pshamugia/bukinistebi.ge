@extends('admin.layouts.app')

@section('title', 'Add Book News')

@section('content')
<div class="container mt-5">
    <h2>Add New Book News</h2>

    {{-- Error and success messages --}}
    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.book-news.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group mt-3">
            <label for="title">Title</label>
            <input type="text" class="form-control" name="title" required value="{{ old('title') }}">
        </div>

        <div class="form-group mt-3">
            <label for="description">აღწერა</label>
            <textarea class="form-control" name="description" rows="5" required>{{ old('description') }}</textarea>
        </div>

        <div class="form-group mt-3">
            <label for="full">Full</label>
            <textarea class="form-control" name="full" id="full-editor" rows="5">{{ old('full') }}</textarea>
        </div>

        <div class="form-group mt-3">
            <label for="image">Image</label>
            <input type="file" class="form-control" name="image" id="imageInput" accept="image/*">
        
            <div id="imagePreview" class="mt-3">
                <img src="#" alt="Preview" style="display: none; max-width: 300px; border: 1px solid #ccc; padding: 5px;">
            </div>
        </div>

        <button type="submit" class="btn btn-success mt-3">Create</button>
    </form>
</div>

{{-- Load CKEditor --}}
<script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('full-editor', {
        on: {
            instanceReady: function () {
                // Sync content for required validation
                this.updateElement();
            },
            change: function () {
                this.updateElement();
            }
        }
    });
</script>
<script>
    CKEDITOR.replace('full-editor', {
        on: {
            instanceReady: function () {
                this.updateElement();
            },
            change: function () {
                this.updateElement();
            }
        }
    });

    // Image preview
    document.getElementById('imageInput').addEventListener('change', function(event) {
        const reader = new FileReader();
        const file = event.target.files[0];
        const previewImg = document.querySelector('#imagePreview img');

        if (file) {
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            previewImg.src = '#';
            previewImg.style.display = 'none';
        }
    });
</script>

@endsection
