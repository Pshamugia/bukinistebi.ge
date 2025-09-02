{{-- resources/views/admin/bundles/edit.blade.php --}}
@extends('admin.layouts.app')

@section('content')
<div class="container">
  <h4>Edit Bundle</h4>

  <form action="{{ route('admin.bundles.update', $bundle) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    @include('admin.bundles._form', ['bundle' => $bundle])

    <button class="btn btn-primary mt-3">Update Bundle</button>
    <a href="{{ route('admin.bundles.index') }}" class="btn btn-link">Cancel</a>
  </form>
</div>
@endsection
