@extends('layouts.app')
@section('title', 'ბუკინისტები | ძიება')
@section('content')

<div class="container" style="position: relative;top:50px; ">
    
    
    <div class="filter" style="background-color: #f8f9fa; border-radius: 8px; border:1px solid rgb(202, 200, 200);  padding:20px 20px 1px 20px; margin-bottom: 26px;">
    <h4> {{ __('messages.filtersearch')}} </h4>
    
    <!-- Filter Form -->
    <form action="{{ route('search') }}" method="GET" class="row g-3 mb-4">

        <div class="col-md-2">
            <input class="form-control me-2 styled-input" name="title" type="search" value="{{ request()->get('title') }}" placeholder="{{ __('messages.searchword')}} ..." aria-label="Search" id="searchInput">
        </div>

        <div class="col-md-2">
             <input type="number" name="price_from" class="form-control" id="price_from" placeholder="{{ __('messages.pricefrom')}}" value="{{ request('price_from') }}">
        </div>
        <div class="col-md-2">
             <input type="number" name="price_to" class="form-control" id="price_to" placeholder="{{ __('messages.priceto')}}" value="{{ request('price_to') }}">
        </div>
        <div class="col-md-3">
            <input type="number" name="publishing_date" class="form-control" id="publishing_date" placeholder="{{ __('messages.yearofpublicaion')}}" min="1800" max="{{ date('Y') }}" value="{{ request('publishing_date') }}">
        </div>
        <div class="col-md-3">

            <select name="genre_id" class="form-select categoria" id="genre_id">
                <option value="" style="color: #ccc"><span>{{ __('messages.category')}}</span></option>
                @foreach ($genres as $genre)
                @php
                $genreName = app()->getLocale() === 'en' && $genre->name_en ? $genre->name_en : $genre->name;
            @endphp
                    <option value="{{ $genre->id }}" {{ request('genre_id') == $genre->id ? 'selected' : '' }}>
                        {{ $genreName }}
                    </option>
                @endforeach


                 
            </select>

            <script>
                 $('.form-select').chosen({
            no_results_text: "Oops, nothing found!"
        });</script>


       
            
        </div>
        <div class="col-md-12">
            <div class="form-check" style="margin-top: 10px;">
                <input class="form-check-input" type="checkbox" name="exclude_sold" id="excludeSoldOut" value="1" {{ request('exclude_sold') ? 'checked' : '' }}>
                <label class="form-check-label" for="excludeSoldOut">
                    {{ __('messages.instock')}} 
                </label>
            </div>
        </div>

        

        <div class="col-md-12">
            <button type="submit" class="btn btn-danger"><span style="top: 3px !important; position: relative;">
                <i class="bi bi-filter"></i>    {{ __('messages.filter')}}  </span>
            </button>
        </div>
    </form>
</div>
    <!-- Displaying Search Results -->
    <h4><p> 
        @if ($search_count>0)
        <i class="bi bi-check-square-fill"></i> 
        {{ __('messages.found') }} 
        <span style="background-color: #dc3545; color:white; padding:4px 0px 0px 3px; border-radius: 3px; margin-right:5px">
            {{ $search_count }} 
        </span> 
            @if ($search_count == 1)
            {{ __('messages.item') }}
            @else
            {{ __('messages.items') }}
            @endif
        @else
        <i class="bi bi-dash-circle-fill"></i> {{ __('messages.notfound')}} 
            <span style="background-color: rgb(177, 20, 20) !important; color:white; padding:6px;">
                 {{ $searchTerm }} 
            </span>  &nbsp; {{ __('messages.spelling')}}
        @endif
    </p></h4>
 

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

@endsection
 
   
