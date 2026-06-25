@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">

<h2 class="mb-4">Edit Publishing Article</h2>

<form method="POST" action="{{ route('admin.publishing.update',$item->id) }}" enctype="multipart/form-data">

@csrf

<div class="form-group mb-3">
<label>Title</label>
<input type="text" name="title" value="{{ $item->title }}" class="form-control">
</div>

<div class="form-group mb-3">
<label>Category</label>
<input type="text" name="category" value="{{ $item->category }}" class="form-control">
</div>

<div class="form-group mb-3">
<label>Description</label>
<textarea name="description" class="form-control" rows="6">{{ $item->description }}</textarea>
</div>

<div class="row">

<div class="col-md-3">
<label>Image 1</label>
<input type="file" name="image_1" class="form-control">

@if($item->image_1)
<img src="{{ asset('storage/'.$item->image_1) }}" width="100" class="mt-2">
@endif
</div>

<div class="col-md-3">
<label>Image 2</label>
<input type="file" name="image_2" class="form-control">

@if($item->image_2)
<img src="{{ asset('storage/'.$item->image_2) }}" width="100" class="mt-2">
@endif
</div>

<div class="col-md-3">
<label>Image 3</label>
<input type="file" name="image_3" class="form-control">

@if($item->image_3)
<img src="{{ asset('storage/'.$item->image_3) }}" width="100" class="mt-2">
@endif
</div>

<div class="col-md-3">
<label>Image 4</label>
<input type="file" name="image_4" class="form-control">

@if($item->image_4)
<img src="{{ asset('storage/'.$item->image_4) }}" width="100" class="mt-2">
@endif
</div>

</div>

<button class="btn btn-primary mt-3">
Update Article
</button>

</form>

</div>

@endsection