@extends('layouts.app')

@section('title', 'ბუკინისტები | პოდკასტი') 
 
@section('content')

     
<h5 class="section-title" style="position: relative;  top:50px;   align-items: left;
    justify-content: left;">     <strong>
        <i class="bi bi-tv"></i>ინტერნეტგადაცემა "ბუკინისტები"
    </strong>
</h5>

    <!-- Featured Books -->
<div class="container mt-5" style="position:relative;">
  
    <div class="row">
        <div>
        <img src="{{ asset('images/coming_soon.jpg') }}" width="400px">
        </div>
    </div>

</div>
@endsection


@section('scripts')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

 


 

@endsection
