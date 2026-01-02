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

<style>

    /* FORCE load more button style on mobile */
@media (max-width: 768px) {
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
                <option value="">{{ __('messages.all') ?? 'ყველა' }}</option>

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

<div id="books-wrapper">
    <div class="row" id="book-results">
            @include('partials.book-cards', ['books' => $books, 'cartItemIds' => $cartItemIds])
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
    

function applyFilters(resetPage = true) {
    const url = new URL(window.location.href);

    const sort      = $('#sortBooks').val();
    const condition = $('#conditionFilter').val();
    const exclude   = $('#excludeSoldOut').is(':checked');

    sort ? url.searchParams.set('sort', sort) : url.searchParams.delete('sort');
    condition ? url.searchParams.set('condition', condition) : url.searchParams.delete('condition');
    exclude ? url.searchParams.set('exclude_sold', 1) : url.searchParams.delete('exclude_sold');

    if (resetPage) url.searchParams.delete('page');

    history.pushState({}, '', url);

    $('#book-results').addClass('opacity-50');

    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
        .then(res => res.text())
        .then(html => {
            $('#book-results').html(html).removeClass('opacity-50');

            if (typeof initCartButtons === 'function') {
                initCartButtons(document.getElementById('book-results'));
            }

            // Reset Load More
            const btn = document.getElementById('load-more');
            if (btn) btn.dataset.nextPage = 2;
        });
}

/* Bind events */
$('#sortBooks, #conditionFilter').on('change', () => applyFilters());
$('#excludeSoldOut').on('change', () => applyFilters());

    </script>
    

<script>
    document.getElementById('load-more')?.addEventListener('click', function () {
    const btn = this;
    const page = btn.dataset.nextPage;

    if (!btn.dataset.label) {
        btn.dataset.label = btn.innerHTML;
    }

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

        const container = document.getElementById('book-results'); // ← CORRECT ONE
        container.insertAdjacentHTML('beforeend', html);

        if (typeof initCartButtons === 'function') {
            initCartButtons(container);
        }

        btn.dataset.nextPage = parseInt(page) + 1;

        btn.disabled = false;
        btn.innerHTML = btn.dataset.label;

        if (html.trim() === '') {
            btn.remove();
        }
    });
});


</script>


@endsection
