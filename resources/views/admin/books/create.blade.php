@extends('admin.layouts.app')

@section('title', 'Add Book')

@section('content')
    <h1>დაამატე წიგნი</h1>
    @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif
  
  @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  
    <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.books.partials.form')
        <input type="hidden" name="lang" id="langInput" value="{{ $locale }}">

        <button class="btn btn-primary">მასალის ატვირთვა</button>
        <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">უკან</a>
    </form>
@endsection
