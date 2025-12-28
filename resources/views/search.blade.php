@extends('layouts.app')
@section('title', 'ბუკინისტები | ძიება')
@section('content')

<div class="container" style="position: relative;top:50px; ">
    
    <style>.filter-modern .form-control,
.filter-modern .form-select {
    height: 48px;
    border-radius: 10px;
    border: 1px solid #ced4da;
    background:white !important;
    transition: all .2s ease-in-out;
    box-shadow: none;
}

/* Make Chosen & Inputs same height */
.filter-modern .chosen-container-single .chosen-single{
    height: 48px !important;
    line-height: 30px !important;
    border-radius: 10px !important;
    background:white !important;
    border:1px solid #d1d3d4 !important;
    box-shadow: inset 0 1px 2px rgba(0,0,0,.05);
}

/* Improve chosen arrow alignment */
.filter-modern .chosen-container-single .chosen-single div b{
    margin-top: 0px;
}


.filter-modern .form-control:focus,
.filter-modern .form-select:focus {
    border-color: #9ea4aeff;
    box-shadow: 0 0 0 .2rem rgba(13,110,253,.2);
}

.filter-modern label {
    font-weight: 600;
    margin-bottom: 4px;
}

.filter-modern .chosen-container-single .chosen-single {
    height: 48px !important;
    border-radius: 10px !important;
    padding-top: 8px !important;
}

.filter-modern .btn-filter {
    height: 48px;
    border-radius: 10px;
    padding-left: 20px;
    padding-right: 20px;
}

.filter-modern .form-check-label {
    font-weight: 600;
}
.search-status-box{
    background:#ffffff;
    border:1px solid #ddd;
    border-radius:10px;
    padding:16px 18px;
    margin-bottom: 15px;
    margin-top: -12px;
 }

.search-status-text{
    font-size:18px;
    font-weight:600;
    margin:0;
    display:flex;
    align-items:center;
    gap:8px;
}

.search-badge{
    background:#dc3545;
    color:#fff;
    padding:5px 10px;
    border-radius:8px;
    font-size:16px;
    font-weight:700;
}

.search-term{
    background:#b11414;
    color:#fff;
    padding:5px 10px;
    border-radius:8px;
    font-weight:600;
}

.material-field{
    position: relative;
    margin-bottom: 18px;
}

.material-field input,
.material-field select{
    height: 52px;
    width: 100%;
    padding: 12px 12px 8px 12px;
    font-size: 16px;
    border-radius: 10px;
    border: 1px solid #d1d3d4;
    background: #f9fafb;
    transition: all .25s ease;
    box-shadow: inset 0 1px 2px rgba(0,0,0,.05);
}

/* Focus Animation */
.material-field input:focus,
.material-field select:focus{
    border-color:#3f51b5;
    background:white;
    box-shadow: 0 0 0 3px rgba(63,81,181,.25);
    outline:none;
}

/* =========================
   FLOATING LABEL
========================= */
.material-field label{
    position: absolute;
    top: 11px;
    left: 14px;
    font-size: 15px;
    color:#777;
    pointer-events:none;
    transition: .25s ease;
    background: transparent;
}

/* When typing or focused → FLOAT */
.material-field input:focus + label,
.material-field input:not(:placeholder-shown) + label,
.material-field select:focus + label{
    top:-8px;
    left:10px;
    font-size:12px;
    background:white;
    padding:0 6px;
    color:#3f51b5;
}
.material-field select {
    opacity: 0;
    position: absolute;
    z-index: -1;
}
/* Position chosen inside material field */
.material-field .chosen-container {
    width: 100% !important;
}

/* Chosen looks like other fields */
.material-field .chosen-single {
    height: 52px !important;
    border-radius: 10px !important;
    padding: 14px 12px !important;
}

/* Align arrow */
.material-field .chosen-single div b {
    margin-top: 6px;
}

/* FLOAT LABEL ABOVE CHOSEN */
.material-field.chosen-active label,
.material-field.has-value label{
    top:-8px;
    left:10px;
    font-size:12px;
    background:white;
    padding:0 6px;
    color:#3f51b5;
}


/* ALWAYS FLOAT LABEL FOR CHOSEN FIELD */
.material-field.chosen-field label{
    top:-8px !important;
    left:10px !important;
    font-size:12px !important;
    background:white !important;
    padding:0 6px !important;
    color:#777 !important;
    z-index: 5;
}

/* When focused or value selected → blue like Material UI */
.material-field.chosen-field.chosen-active label,
.material-field.chosen-field.has-value label{
    color:#3f51b5 !important;
}
/* Selected value text color */
.chosen-container-single .chosen-single span {
    color:#777 !important;  /* change to your desired color */
}



</style>
    <div class="filter filter-modern" 
     style="background-color: #f8f9fa; border-radius: 8px; border:1px solid rgb(202, 200, 200);border-radius:12px;border:1px solid #ddd;padding:25px 25px 0px 25px;margin-bottom:26px;">
    
    <h4 style="margin-bottom:18px;">{{ __('messages.filtersearch')}}</h4>

    <form action="{{ route('search') }}" method="GET" class="row g-3 mb-4">

   <div class="col-md-2 material-field">
    <input name="title" class="form-control"
           value="{{ request('title') }}"
           placeholder=" ">
    <label>{{ __('messages.searchword') }}</label>
</div>

        <div class="col-md-2 material-field">
            <input type="number" name="price_from" class="form-control" value="{{ request('price_from') }}"  placeholder=" ">
                        <label>{{ __('messages.pricefrom')}}</label>

        </div>

        <div class="col-md-2 material-field">
            
            <input type="number" name="price_to" class="form-control" value="{{ request('price_to') }}" placeholder=" ">
            <label>{{ __('messages.priceto')}}</label>
        </div>

        <div class="col-md-2 material-field">
            <input type="number" name="publishing_date" class="form-control"
                   min="1800" max="{{ date('Y') }}" value="{{ request('publishing_date') }}" placeholder=" ">
                   <label>{{ __('messages.yearofpublicaion')}}</label>
 </div>

        <div class="col-md-4 material-field chosen-field">
    <select name="genre_id" class="form-select categoria" id="genre_id">
<option value="">
    {{ app()->getLocale() === 'ka' ? 'აირჩიე' : 'Select' }}
</option>
        @foreach ($genres as $genre)
            @php
                $genreName = app()->getLocale() === 'en' && $genre->name_en ? $genre->name_en : $genre->name;
            @endphp
            <option value="{{ $genre->id }}" {{ request('genre_id') == $genre->id ? 'selected' : '' }}>
                {{ $genreName }}
            </option>
        @endforeach
     </select>
    <label>{{ __('messages.category') }}</label>
</div>


        <div class="col-md-12">
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="exclude_sold"
                       value="1" id="excludeSoldOut" {{ request('exclude_sold') ? 'checked' : '' }}>
                <label class="form-check-label" for="excludeSoldOut">
                    {{ __('messages.instock') }}
                </label>
            </div>
        </div>

        <div class="col-md-12 mt-2">
            <button type="submit" class="btn btn-danger btn-filter">
                <i class="bi bi-filter"></i> {{ __('messages.filter') }}
            </button>
        </div>

    </form>
</div>

    <!-- Displaying Search Results -->
    <div class="search-status-box">
    <p class="search-status-text">
        @if ($search_count > 0)

            <i class="bi bi-check-circle-fill text-success"></i>
            {{ __('messages.found') }}

            <span class="search-badge">{{ $search_count }}</span>

            @if ($search_count == 1)
                {{ __('messages.item') }}
            @else
                {{ __('messages.items') }}
            @endif

        @else

            <i class="bi bi-x-circle-fill text-danger"></i>
            {{ __('messages.notfound') }}

            <span class="search-term">
                {{ $searchTerm }}
            </span>

            {{ __('messages.spelling')}}

        @endif
    </p>
</div>

 

<div id="search-results" class="row">
    @include('partials.search-results', ['books' => $books])
</div>

@if ($books->hasMorePages())
<div class="text-center mt-4">
    <button id="load-more"
            class="btn btn-outline-danger"
            data-next-page="{{ $books->currentPage() + 1 }}">
                                            {{ __('messages.seemore') }}
    </button>
</div>
@endif
</div>




    <br>
  
<script>
document.getElementById('load-more')?.addEventListener('click', function () {
    const btn = this;
    const page = btn.dataset.nextPage;

    btn.disabled = true;
btn.innerText = @json(__('messages.loading'));

    const url = new URL(window.location.href);
    url.searchParams.set('page', page);

    fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.text())
    .then(html => {
    const container = document.getElementById('search-results');

    container.insertAdjacentHTML('beforeend', html);

    // 🔥 THIS LINE FIXES YOUR PROBLEM
    initCartButtons(container);

    btn.dataset.nextPage = parseInt(page) + 1;
    btn.disabled = false;
    btn.innerText = @json(__('messages.seemore'));

    if (html.trim() === '') {
        btn.remove();
    }
});

});
</script>

 

<script>
$(function() {

    let chosen = $('#genre_id').chosen({
        width:"100%",
        no_results_text:"Oops, nothing found!"
    });

    function updateChosenLabel(){
        let field = $('#genre_id').closest('.material-field');
        if($('#genre_id').val()){
            field.addClass('has-value');
        } else {
            field.removeClass('has-value');
        }
    }

    updateChosenLabel();

    $('#genre_id').on('change', function(){
        updateChosenLabel();
    });

    $('.chosen-container').on('mousedown', function(){
        $(this).closest('.material-field').addClass('chosen-active');
    });

});
</script>



@endsection
 
   
