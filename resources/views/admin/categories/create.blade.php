@extends('admin.layouts.app')

@section('title', 'Add Category')

@section('content')
    <h1>Add Category</h1>

    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        @include('admin.categories.partials.form')
        <button class="btn btn-primary">Save</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Back</a>
    </form>
@endsection
