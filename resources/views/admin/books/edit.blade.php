@extends('admin.layouts.app')

@section('title', 'Edit Book')

@section('content')
    <h1>რედაქტირება</h1>

    <form action="{{ route('admin.books.update', $book) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.books.partials.form')
        <input type="hidden" name="lang" id="langInput" value="{{ $locale }}">

        <button class="btn btn-primary">მასალის განახლება</button>
        <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">უკან</a>
    </form>
@endsection
