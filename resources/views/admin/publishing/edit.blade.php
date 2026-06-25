@extends('admin.layouts.app')

@section('title', 'Edit Publishing Item')

@section('content')
<div class="container mt-4" style="max-width: 900px;">
    <h2 class="mb-4">Edit Publishing Item</h2>

    @include('admin.publishing.partials.form', [
        'action' => route('admin.publishing.update', $item->id),
        'method' => 'PUT',
        'item' => $item,
    ])
</div>
@endsection
