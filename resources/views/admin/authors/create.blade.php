@extends('admin.layouts.app')

@section('title', 'Add Author')

@section('content')
    <h1>Add Author</h1>

    <form action="{{ route('admin.authors.store') }}" method="POST">
        @csrf
        @include('admin.authors.partials.form')
        <button class="btn btn-primary">Save</button>
        <a href="{{ route('admin.authors.index') }}" class="btn btn-secondary">Back</a>
    </form>
@endsection
