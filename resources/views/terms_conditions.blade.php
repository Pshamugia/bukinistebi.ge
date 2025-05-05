@extends('layouts.app')
@section('title', 'ბუკინისტები | წესები და პირობები')
@section('content')

     
<h5 class="section-title" style="position: relative; margin-bottom:25px; padding-bottom:25px; align-items: left;
    justify-content: left;">     <strong>
        <i class="bi bi-journal-text"></i>
        წესები და პირობები
    </strong>
</h5>

<div class="container mt-5" style="position: relative; top: -44px">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="books-tab" data-bs-toggle="tab" data-bs-target="#books" type="button" role="tab" aria-controls="books" aria-selected="true">მომხმარებლებისთვის</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pencils-tab" data-bs-toggle="tab" data-bs-target="#pencils" type="button" role="tab" aria-controls="pencils" aria-selected="false">ბუკინისტებისთვის</button>
        </li>
    </ul>
    <div class="tab-content mt-3" id="myTabContent">
        <!-- Users Tab Content -->
        <div class="tab-pane fade show active" id="books" role="tabpanel" aria-labelledby="books-tab">
            
            <p><h4 style="padding-left: 20px">მომხმარებლების წესები და პირობები</h4>
                
                @if ($terms)
                <div class="col-md-12" style="position: relative; padding:0 20px 25px 20px">
                    <div>
                        <div class="card-body">
                             <p>{!! $terms->full !!}</p>
                        </div>
                    </div>
                </div>
            @else
                <p>Terms and conditions not found.</p>
            @endif</p>
        </div>
        <!-- Bukinistebi Tab Content -->
        <div class="tab-pane fade" id="pencils" role="tabpanel" aria-labelledby="pencils-tab">
            <p><h4 style="padding-left: 20px">ბუკინისტების წესები და პირობები</h4>
                @if ($bukinistebisatvis)
                <div class="col-md-12" style="position: relative; padding:0 20px 25px 20px">
                    <div>
                        <div class="card-body">
                             <p>{!! $bukinistebisatvis->full !!} </p>
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

 
 
