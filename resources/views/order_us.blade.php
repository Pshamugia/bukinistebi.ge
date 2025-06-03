@extends('layouts.app')
@section('title', 'ბუკინისტები | შეგვიკვეთე') 
@section('content')

     
<h5 class="section-title" style="position: relative; margin-bottom:25px; padding-bottom:25px; align-items: left;
    justify-content: left;">     <strong>
       <i class="bi bi-box-fill"></i> {{ __('messages.order')}}
    </strong>
</h5>

<div class="container d-flex ">
    <div class="col-md-6" style="border: 1px solid rgb(165, 158, 158); border-radius:3px; font-weight: 100"> 
        <button type="button" class="btn btn-light text-start w-100">
 {{ __('messages.orderUs')}}                     
            <div class="text-center mt-2">
                <i class="bi bi-arrow-down-square"></i>
            </div>
        </button>
    </div>
</div>


    <!-- Featured Books -->
<div class="container mt-5" style="position:relative; margin-top: -15px">
  
    <div class="row">
        @if(session('success'))
        <div class="alert alert-success" style="position: relative; margin-bottom:25px">
            {!! session('success') !!}
        </div>
    @endif

@if(Auth::check() && Auth::user()->role === 'user' || (Auth::check() && Auth::user()->role === 'admin'))
<form action="{{ route('order.request.book') }}" method="POST">
    @csrf

    <!-- Book Title -->
    <div class="form-group mb-3">
        <label for="title">{{ __('messages.bookTitle')}}</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="bi bi-book"></i> <!-- Book icon -->
                </span>
            </div>
            <input type="text" class="form-control" id="title" name="title" placeholder="{{ __('messages.titleExample')}}" required>
        </div>
    </div>

    <!-- Book Author -->
    <div class="form-group mb-3">
        <label for="author">{{ __('bookAuthor')}}</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="bi bi-person"></i> <!-- Author icon -->
                </span>
            </div>
            <input type="text" class="form-control" id="author" name="author" placeholder="{{ __('messages.authorExample')}}" required>
        </div>
    </div>

    <!-- Publishing Year (Optional) -->
    <div class="form-group mb-3">
        <label for="publishing_year">{{ __('messages.publication')}}</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="bi bi-calendar"></i> <!-- Calendar icon -->
                </span>
            </div>
            <input type="text" class="form-control" id="publishing_year" name="publishing_year" placeholder="{{ __('messages.bookYear')}}">
        </div>
    </div>

    <!-- Additional Comment (Optional) -->
    <div class="form-group mb-4">
        <label for="comment"> {{ __('messages.extraComment')}}</label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="bi bi-chat-left-text"></i> <!-- Comment icon -->
                </span>
            </div>
            <textarea class="form-control" id="comment" name="comment" rows="3" placeholder="{{ __('messages.yourComment')}}..." required></textarea>
        </div>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn btn-primary btn-block">
        <i class="bi bi-send"></i> {{ __('messages.sendOrder')}}
    </button>
</form>

@else


<div class="container d-flex ">
    <div class="col-md-6" style="border: 1px solid rgb(165, 158, 158); border-radius:3px;"> 
    <div >
    <button type="button" class="btn btn-warning text-center">
        <span>  {{ __('messages.whoCanOrder')}}</span>
    </button>
 
    </div> </div>
</div>


@endif


        </div>
        </div>
        </div>

 

 


 

@endsection
