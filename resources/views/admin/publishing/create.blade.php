@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">

<h2 class="mb-4">Create Publishing Article</h2>

<form method="POST" action="{{ route('admin.publishing.store') }}" enctype="multipart/form-data">

@csrf

<div class="form-group mb-3">
<label>Title</label>
<input type="text" name="title" class="form-control" required>
</div>

<div class="form-group mb-3">
<label>Category</label>
<input type="text" name="category" class="form-control">
</div>

<div class="form-group mb-3">
<label>Description</label>
<textarea name="description" class="form-control" rows="6"></textarea>
</div>

<div class="form-group mb-3">
<label>Image 1</label>
<input type="file" name="image_1" class="form-control">
</div>

<div class="form-group mb-3">
<label>Image 2</label>
<input type="file" name="image_2" class="form-control">
</div>

<div class="form-group mb-3">
<label>Image 3</label>
<input type="file" name="image_3" class="form-control">
</div>

<div class="form-group mb-3">
<label>Image 4</label>
<input type="file" name="image_4" class="form-control">
</div>

<button type="submit" class="btn btn-success">
Save Article
</button>

</form>

</div>

@endsection