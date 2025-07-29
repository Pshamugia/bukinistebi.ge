@extends('admin.layouts.app')

@section('title', 'Edit Author')

@section('content')
    <h1>Edit Author</h1>

    <form action="{{ route('admin.authors.update', $author) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.authors.partials.form')
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('admin.authors.index') }}" class="btn btn-secondary">Back</a>
    </form>
@endsection
