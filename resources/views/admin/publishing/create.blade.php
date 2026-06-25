@extends('admin.layouts.app')

@section('title', 'Create Publishing Item')

@section('content')
<div class="container mt-4" style="max-width: 900px;">
    <h2 class="mb-4">Create Publishing Item</h2>

    @include('admin.publishing.partials.form', [
        'action' => route('admin.publishing.store'),
        'method' => 'POST',
        'item' => null,
    ])
</div>
@endsection
