@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">

<h2 class="mb-4">Publishing Articles</h2>

<a href="{{ route('admin.publishing.create') }}" class="btn btn-success mb-3">
+ Add New Article
</a>

<table class="table table-bordered table-hover">

<thead>
<tr>
<th>ID</th>
<th>Image</th>
<th>Title</th>
<th>Category</th>
<th>Created</th>
<th>Actions</th>
</tr>
</thead>

<tbody>

@foreach($items as $item)

<tr>

<td>{{ $item->id }}</td>

<td>
@if($item->image_1)
<img src="{{ asset('storage/'.$item->image_1) }}" width="60">
@endif
</td>

<td>{{ $item->title }}</td>

<td>{{ $item->category }}</td>

<td>{{ $item->created_at }}</td>

<td>

<a href="{{ route('admin.publishing.edit',$item->id) }}" class="btn btn-primary btn-sm">
Edit
</a>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

@endsection