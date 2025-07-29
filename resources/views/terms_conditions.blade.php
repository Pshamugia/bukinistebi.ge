@extends('layouts.app')
@section('title', 'ბუკინისტები | წესები და პირობები')
@section('content')

     
<h5 class="section-title" style="position: relative; top:44px; margin-bottom:25px; padding-bottom:25px; align-items: left;
    justify-content: left;">     <strong>
        <i class="bi bi-journal-text"></i>
       {{ __('messages.terms')}}
    </strong>
</h5>

<div class="container mt-5" style="position: relative;">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="books-tab" data-bs-toggle="tab" data-bs-target="#books" type="button" role="tab" aria-controls="books" aria-selected="true">{{ __('messages.forUsers')}}</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pencils-tab" data-bs-toggle="tab" data-bs-target="#pencils" type="button" role="tab" aria-controls="pencils" aria-selected="false">{{ __('messages.forBooksellers')}}</button>
        </li>
    </ul>
    <div class="tab-content mt-3" id="myTabContent">
        <!-- Users Tab Content -->
        <div class="tab-pane fade show active" id="books" role="tabpanel" aria-labelledby="books-tab">
            
            <p><h4 style="padding-left: 20px">{{ __('messages.terms')}} {{ __('messages.forUsers')}}</h4>
                
                @if ($terms)
                <div class="col-md-12" style="position: relative; padding:0 20px 25px 20px">
                    <div>
                        <div class="card-body">
                            <p>
                                {!! app()->getLocale() === 'en' && $terms->full_en
                                    ? $terms->full_en
                                    : $terms->full !!}
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <p>Terms and conditions not found.</p>
            @endif</p>
        </div>
        <!-- Bukinistebi Tab Content -->
        <div class="tab-pane fade" id="pencils" role="tabpanel" aria-labelledby="pencils-tab">
            <p><h4 style="padding-left: 20px">{{ __('messages.terms')}} {{ __('messages.forBooksellers')}}</h4>
                @if ($bukinistebisatvis)
                <div class="col-md-12" style="position: relative; padding:0 20px 25px 20px">
                    <div>
                        <div class="card-body">
                            <p>
                                {!! app()->getLocale() === 'en' && $bukinistebisatvis->full_en
                                    ? $bukinistebisatvis->full_en
                                    : $bukinistebisatvis->full !!}
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <p>Terms and conditions not found.</p>
            @endif</p>
        </div>
    </div>
</div>

  
@endsection

 
 
