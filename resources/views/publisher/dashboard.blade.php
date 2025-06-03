@extends('layouts.app')

@section('content')
<div class="container" style="height: max-content; min-height:400px ;">
    <h5 class="section-title" style="position: relative; margin-bottom:25px; padding-top:55px; padding-bottom:25px; align-items: left;
    justify-content: left;">     <strong><h1>{{ __('messages.booksellersRoom') }}</h1> </strong></h5>
    <img src="{{ asset('images/bookseller.png') }}" alt="Bookseller Illustration" class="img-fluid col-md-4">

     <!-- Display success message if present -->
     @if (session('success'))
     <div class="alert alert-success">
         {{ session('success') }}
     </div>
 @endif
 
 
 <p>{{ __('messages.hello') }}, {{ Auth::user()->name }}! {{ __('messages.canUpload') }}</p>


    
    
    <a href="{{ route('publisher.books.create') }}" class="btn btn-primary">{{ __('messages.uploadBook') }}</a>
    <!-- Add any other features and links you'd like here for the publisher dashboard -->
</div>
@endsection
