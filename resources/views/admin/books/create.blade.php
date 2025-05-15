@extends('admin.layouts.app')

@section('title', 'Add Book')

@section('content')
    <h1>დაამატე წიგნი</h1>

    <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.books.partials.form')
        <button class="btn btn-primary">მასალის ატვირთვა</button>
        <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">უკან</a>
    </form>
@endsection
