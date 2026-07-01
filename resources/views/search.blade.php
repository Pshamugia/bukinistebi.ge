@extends('layouts.app')
@section('title', 'ბუკინისტები | ძიება')
@push('head')
<link rel="preload" href="/css/chosen.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="/css/chosen.min.css"></noscript>
@endpush

@section('content')

@if(request('lang'))
    <input type="hidden" name="lang" value="{{ request('lang') }}">
@endif


<div class="container search-page-wrap">
    
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

.search-page-wrap {
    position: relative;
    top: 50px;
}

.search-filter-panel {
    background: #fff;
    border: 1px solid #e4e7ec;
    border-radius: 16px;
    padding: 28px;
    margin-bottom: 22px;
    box-shadow: 0 12px 30px rgba(16, 24, 40, 0.06);
}

.search-filter-title {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0 0 22px;
    color: #101828;
    font-size: 22px;
    font-weight: 800;
    letter-spacing: 0;
}

.search-filter-title::before {
    content: "\F3E1";
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border-radius: 10px;
    background: #f2f4f7;
    color: #d92d20;
    font-family: "bootstrap-icons";
    font-size: 18px;
    font-weight: 400;
}

.search-filter-form {
    align-items: end;
}

.search-filter-panel .material-field {
    margin-bottom: 10px;
}

.search-filter-panel .material-field input,
.search-filter-panel .material-field select,
.search-filter-panel .material-field .chosen-single {
    height: 56px !important;
    border: 1px solid #d9dee7 !important;
    border-radius: 12px !important;
    background: #fff !important;
    box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04) !important;
}

.search-filter-panel .material-field input {
    padding: 18px 15px 8px;
    color: #101828;
    font-weight: 700;
}

.search-filter-panel .material-field label {
    color: #667085;
    font-size: 14px;
    font-weight: 700;
}

.search-filter-panel .material-field input:focus,
.search-filter-panel .material-field select:focus {
    border-color: #d92d20 !important;
    box-shadow: 0 0 0 4px rgba(217, 45, 32, 0.12) !important;
}

.search-filter-panel .material-field input:focus + label,
.search-filter-panel .material-field input:not(:placeholder-shown) + label,
.search-filter-panel .material-field select:focus + label,
.search-filter-panel .material-field.chosen-field.chosen-active label,
.search-filter-panel .material-field.chosen-field.has-value label {
    color: #d92d20 !important;
}

.search-filter-panel .chosen-container-single .chosen-single {
    padding: 15px 16px !important;
    line-height: 24px !important;
}

.search-filter-panel .chosen-container-single .chosen-single span {
    color: #475467 !important;
    font-weight: 700;
}

.inventory-toggle {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    min-height: 44px;
    padding: 9px 14px;
    border: 1px solid #e4e7ec;
    border-radius: 12px;
    background: #f9fafb;
}

.inventory-toggle .form-check-input {
    width: 18px;
    height: 18px;
    margin: 0;
    border-color: #cbd5e1;
    box-shadow: none;
}

.inventory-toggle .form-check-input:checked {
    background-color: #12b76a;
    border-color: #12b76a;
}

.search-filter-panel .btn-filter {
    min-width: 168px;
    height: 52px;
    border: 0;
    border-radius: 12px;
    background: #d92d20;
    color: #fff;
    font-size: 17px;
    font-weight: 800;
    box-shadow: 0 10px 22px rgba(217, 45, 32, 0.22);
    transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
}

.search-filter-panel .btn-filter:hover,
.search-filter-panel .btn-filter:focus {
    background: #b42318;
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 14px 26px rgba(217, 45, 32, 0.28);
}

.search-status-box{
    background:#fff;
    border:1px solid #e4e7ec;
    border-radius:16px;
    padding:18px 22px;
    margin-bottom: 22px;
    box-shadow: 0 8px 22px rgba(16, 24, 40, 0.045);
 }

.search-status-text{
    font-size:20px;
    font-weight:800;
    margin:0;
    display:flex;
    align-items:center;
    gap:10px;
    color:#101828;
    flex-wrap: wrap;
}

.search-badge{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-width: 38px;
    height: 38px;
    background:#d92d20;
    color:#fff;
    padding:0 12px;
    border-radius:12px;
    font-size:18px;
    font-weight:900;
}

.search-term{
    background:#fff1f0;
    border:1px solid #ffd5d2;
    color:#b42318;
    padding:6px 12px;
    border-radius:10px;
    font-weight:800;
}

.search-load-btn {
    min-width: 180px;
    min-height: 52px;
    border: 0;
    border-radius: 12px;
    background: #101828;
    color:#fff;
    font-size: 17px;
    font-weight: 800;
    box-shadow: 0 10px 24px rgba(16, 24, 40, 0.18);
}

.search-load-btn:hover,
.search-load-btn:focus {
    background: #1d2939;
    color: #fff;
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
 

    /* FORCE load more button style on mobile */
@media (max-width: 768px) {
    .search-page-wrap {
        top: 25px;
    }

    .search-filter-panel {
        padding: 20px 16px;
        border-radius: 14px;
    }

    .search-filter-title {
        font-size: 19px;
    }

    .search-filter-panel .btn-filter {
        width: 100%;
    }

    .search-status-text {
        font-size: 17px;
    }

    button.search-load-btn,
    button.load-more-btn,
    #load-more,
    #load-more-books {
        -webkit-appearance: none !important;
        appearance: none !important;

        background: linear-gradient(135deg,#e63946,#d7263d) !important;
        background-color:#d7263d !important;

        color:#fff !important;
        font-size:16px !important;
        font-weight:700 !important;

        padding:14px 40px !important;
        border-radius:40px !important;

        border:none !important;
        outline:none !important;

        display:-webkit-inline-flex !important;
        display:inline-flex !important;
        align-items:center !important;
        justify-content:center !important;
        gap:8px !important;

        box-shadow:0 12px 30px rgba(215,38,61,.35) !important;
    }
}


</style>
    <div class="filter filter-modern search-filter-panel">
    
    <h4 class="search-filter-title">{{ __('messages.filtersearch')}}</h4>

    <form action="@langurl(route('search'))" method="GET" class="row g-3 mb-0 search-filter-form">

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
            <div class="form-check mt-2 inventory-toggle">
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
<div class="text-center mt-4 mb-4">
    <button id="load-more"
            class="btn search-load-btn"
            data-next-page="{{ $books->currentPage() + 1 }}">
        📚 {{ __('messages.seemore') }}
    </button>
</div>
@endif

</div>




    <br>
  
<script>
    document.getElementById('load-more')?.addEventListener('click', function () {
    const btn = this;
    const page = btn.dataset.nextPage;

    // Save original label if not saved yet
    if (!btn.dataset.label) {
        btn.dataset.label = btn.innerHTML;
    }

    // Show spinner + loading text
    btn.disabled = true;
    btn.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
        {{ app()->getLocale() == 'ka' ? 'იტვირთება...' : 'Loading...' }}
    `;

    const url = new URL(window.location.href);
    url.searchParams.set('page', page);

    fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.text())
    .then(html => {

        const container = document.getElementById('search-results');
        container.insertAdjacentHTML('beforeend', html);

        // Init cart again
        initCartButtons(container);
        if (typeof window.initBookHoverTitles === 'function') {
            window.initBookHoverTitles(container);
        }

        btn.dataset.nextPage = parseInt(page) + 1;

        // Restore icon text
        btn.disabled = false;
        btn.innerHTML = btn.dataset.label;

        if (html.trim() === '') {
            btn.remove();
        }
    });
});

</script>

 

<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
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
 
   
