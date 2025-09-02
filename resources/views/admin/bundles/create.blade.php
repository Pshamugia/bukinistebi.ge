{{-- resources/views/admin/bundles/create.blade.php --}}
@extends('admin.layouts.app')
@section('content')
<div class="container">
  <h4>Create Bundle</h4>
  <form action="{{ route('admin.bundles.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    @include('admin.bundles._form')
    <button class="btn btn-primary mt-3">Save Bundle</button>
    <a href="{{ route('admin.bundles.index') }}" class="btn btn-link">Cancel</a>
  </form>
</div>
@endsection
