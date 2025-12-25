@extends('layouts.app')

@section('title', 'ბუკინისტები | წიგნები')
@section('og')
  <meta property="og:type" content="website">
  <meta property="og:title" content="{{ __('messages.books') }}">
  <meta property="og:description" content="{{ __('messages.searchfor') }}">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:image" content="{{ asset('images/og/books.svg') }}">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta name="twitter:card" content="summary_large_image">
@endsection
@section('content')

    <h5 class="section-title"
        style="position: relative; margin-bottom:25px; top:30px; padding-bottom:25px; align-items: left;
    justify-content: left;">
        <strong>
            <i class="bi bi-bookmarks-fill"></i> {{ __('messages.books') }}
        </strong>
    </h5>



    <!-- Featured Books -->
    <div class="container mt-5" style="position:relative; margin-top: -15px !important">
        <!-- Controls row: side-by-side on md+, stacked on mobile -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">

  <!-- Left: Exclude sold-out -->
  <label class="m-0">
    <h6 class="m-0">
      <input type="checkbox" id="excludeSoldOut" {{ request('exclude_sold') ? 'checked' : '' }}>
      {{ __('messages.instock') }}
    </h6>
  </label>

  <!-- Right: Sort by Price -->
<div class="d-flex flex-column flex-md-row align-items-md-center gap-2">
    <!-- Condition dropdown -->
    <select id="conditionFilter" class="form-select form-select-sm w-auto">
        <option value="">{{ __('messages.sortStatus') ?? 'ყველა' }}</option>
                <option value="All">{{ __('messages.all') ?? 'ყველა' }}</option>

        <option value="new" {{ request('condition') === 'new' ? 'selected' : '' }}>
            {{ __('messages.newBooks') ?? 'ახალი წიგნები' }}
        </option>
        <option value="used" {{ request('condition') === 'used' ? 'selected' : '' }}>
            {{ __('messages.usedBooks') ?? 'მეორადი წიგნები' }}
        </option>
    </select>

    <!-- Sort dropdown -->
    <select id="sortBooks" class="form-select form-select-sm w-auto">
        <option value="">{{ __('messages.sortPriceDefault') ?? 'Default' }}</option>
        <option value="price_asc"  {{ request(key: 'sort') === 'price_asc' ? 'selected' : '' }}>
            {{ __('messages.sorPriceAsc') ?? 'Price: Low to High' }}
        </option>
        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>
            {{ __('messages.sorPriceDesc') ?? 'Price: High to Low' }}
        </option>
    </select>

    
  </div>

</div>

       <div class="row" id="book-results">
    @include('partials.book-cards', ['books' => $books, 'cartItemIds' => $cartItemIds])
</div>



      @if ($books->hasMorePages())
    <div class="text-center mt-4">
        <button id="load-more-books"
                class="btn btn-outline-danger"
                data-next-page="{{ $books->currentPage() + 1 }}">
            {{ __('messages.seemore') }}
        </button>

        <div id="load-spinner" class="mt-3" style="display:none;">
            <div class="spinner-border text-danger" role="status"></div>
        </div>
    </div>
@endif


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






    <script>
        $('#excludeSoldOut').change(function() {
            const url = new URL(window.location.href);
            if ($(this).is(':checked')) {
                url.searchParams.set('exclude_sold', 1);
            } else {
                url.searchParams.delete('exclude_sold');
            }
            window.location.href = url.toString();
        });
    </script>

<script>
    $('#sortBooks').change(function(){
        const url = new URL(window.location.href);
        const sort = $(this).val();
        if (sort) {
            url.searchParams.set('sort', sort);
        } else {
            url.searchParams.delete('sort');
        }
        window.location.href = url.toString();
    });


    $('#conditionFilter').change(function(){
    const url = new URL(window.location.href);
    const value = $(this).val();

    if (value) {
        url.searchParams.set('condition', value);
    } else {
        url.searchParams.delete('condition');
    }

    window.location.href = url.toString();
});

    </script>
    
<script>
document.getElementById('load-more-books')?.addEventListener('click', function () {
    const btn = this;
    const page = btn.dataset.nextPage;

    btn.style.display = 'none';
document.getElementById('load-spinner').style.display = 'block';


    const url = new URL(window.location.href);
    url.searchParams.set('page', page);

    fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.text())
    .then(html => {
        const container = document.getElementById('book-results');

        container.insertAdjacentHTML('beforeend', html);

        // rebind cart buttons
        if (typeof initCartButtons === 'function') {
            initCartButtons(container);
        }

        btn.dataset.nextPage = parseInt(page) + 1;
btn.style.display = 'inline-block';
document.getElementById('load-spinner').style.display = 'none';


        if (html.trim() === '') {
            btn.remove();
        }
    });
});
</script>

@endsection
