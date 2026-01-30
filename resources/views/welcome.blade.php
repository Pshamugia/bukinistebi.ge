@extends('layouts.app')

@section('title', 'Bukinistebi.ge - ონლაინ მაღაზია')

@section('content')

<style>
    /* NEWS LIST */
    .news-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
        width: 100%;
    }

    /* NEWS ITEM */
    .news-item {
        display: flex;
        align-items: center;
        gap: 14px;
        text-decoration: none;
        padding: 14px 0;
        border-bottom: 1px solid #e6e6e6;
        transition: background 0.2s ease;
    }

    .news-item:hover {
        background: rgba(0, 0, 0, 0.02);
    }

    /* THUMB */
    .news-thumb {
        width: 80px;
        height: 120px;
        flex-shrink: 0;
        border-radius: 6px;
        overflow: hidden;
        background: #f4f4f4;
    }

    .news-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    /* CONTENT */
    .news-content {
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-width: 0;
        /* VERY IMPORTANT for mobile */
    }

    .news-title {
        font-size: 16px;
        font-weight: 600;
        color: #111;
        margin: 0 0 6px 0;
        line-height: 1.35;

        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .news-meta {
        font-size: 13px;
        color: #777;
    }

    /* MOBILE POLISH */
    @media (max-width: 575.98px) {
        .news-thumb {
            width: 70px;
            height: 105px;
        }

        .news-title {
            font-size: 15px;
        }
    }
</style>

<!-- Hero Section -->
<div class="hero-section lazybg"
    data-bg="{{ asset('uploads/book9.webp') }}"
    style="
        background-attachment: fixed;
        background-size: cover;
        background-position: center center;
     ">
    <div class="hero-content" style="position: relative; padding-top: 15px;">



        <h1>{{ __('messages.bookstore') }}</h1>
        <h5><a href="{{ route('books') }}{{ request('lang') ? '?lang=' . request('lang') : '' }}" class="btn btn-outline-light"
                style="font-size: 18px">{{ __('messages.searchfor') }}</a></h5>

    </div>
</div>

<!-- Featured Books -->
<div class="container mt-5">

    <div class="hr-with-text">
        <h2 style="position: relative; font-size: 26px; ">

            {{ __('messages.recently') }} 
        </h2>
    </div>




    <div class="row">
        @foreach ($books as $index => $book)
        <div class="col-lg-3 col-md-4 col-sm-6 col-12" style="position: relative; padding-bottom: 25px;">
            <div class="card book-card shadow-sm" style="border: 1px solid #f0f0f0; border-radius: 8px;">
                <a href="{{ route('full', ['title' => Str::slug($book->title), 'id' => $book->id]) }}{{ request('lang') ? '?lang=' . request('lang') : '' }}"
                    class="card-link">
                    <div class="image-container">
                        <img
                            src="{{ asset('storage/' . ($book->thumb_image ?: $book->photo)) }}?v={{ $book->updated_at->timestamp }}"
                            alt="{{ $book->title }}"
                            class="cover img-fluid"
                            style="border-radius: 8px 8px 0 0; object-fit: cover;"
                            @if($index < 4)
                            loading="eager"
                            fetchpriority="high"
                            @else
                            loading="lazy"
                            decoding="async"
                            @endif
                            width="265"
                            height="360"
                            sizes="(max-width: 768px) 50vw, 265px"
                            onerror="this.onerror=null;this.src='{{ asset('images/default_image.png') }}'; this.alt='Default book image';">




                    </div>
                </a>
                <div class="card-body">
                    <h4 class="font-weight-bold">{{ \Illuminate\Support\Str::limit($book->title, 18) }}</h4>
                    {{-- Author --}}
                    <p class="text-muted mb-2" style="font-size: 14px;">
                        <i class="bi bi-person"></i>
                        <a href="{{ route('full_author', ['id' => $book->author_id, 'name' => Str::slug($book->author->name)]) }}"
                            class="text-decoration-none text-primary">
                            {{ $book->author->getLocalizedName()  }}

                        </a>
                    </p>

                    {{-- PRICE --}}

                    <p style="font-size: 18px; color: #333;">
                        @if ($book->new_price)
                        {{-- New (discounted) price first --}}
                        <em style="position: relative; font-style: normal; font-size: 20px; top:3px;">&#8382;</em>
                        <span class="text-dark fw-semibold" style="position: relative; top:3px;">
                            {{ number_format($book->new_price) }}
                        </span>
                        &nbsp;
                        {{-- Old price after (with strikethrough) --}}

                        <em class="text-secondary" style="text-decoration: line-through;  font-style: normal;  font-size: 16px; position: relative; top:3px;"> &#8382;
                            {{ number_format($book->price) }}
                        </em>
                        @else
                        {{-- Normal price --}}
                        <em style="position: relative; font-style: normal; font-size: 20px; top:3px;">&#8382;</em>
                        <span class="text-dark fw-semibold" style="position: relative; top:3px;">
                            {{ number_format($book->price) }}
                        </span>
                        @endif

                        {{-- Availability badge --}}
                        <span style="position: relative; top:5px;">
                            @if ($book->quantity == 0)
                            <span class="badge bg-danger" style="font-weight: 100; float: right;">
                                {{ __('messages.outofstock') }}
                            </span>
                            @elseif($book->quantity >= 1)
                            <span class="badge bg-success"
                                style="font-size: 13px; font-weight: 100; float: right;">
                                {{ __('messages.available') }}
                            </span>
                            @endif
                        </span>
                    </p>



                    {{-- Cart Buttons --}}
                    @if($book->quantity >= 1)
                    @if (!auth()->check() || auth()->user()->role !== 'publisher')
                    @if (in_array($book->id, $cartItemIds))
                    <button class="btn btn-success toggle-cart-btn w-100"
                        data-product-id="{{ $book->id }}" data-in-cart="true">
                        <i class="bi bi-check-circle"></i> <span class="cart-btn-text"
                            data-state="added"></span>
                    </button>
                    @else
                    <button class="btn btn-primary toggle-cart-btn w-100"
                        data-product-id="{{ $book->id }}" data-in-cart="false">
                        <i class="bi bi-cart-plus"></i> <span class="cart-btn-text" data-state="add"></span>
                    </button>
                    @endif
                    @endif

                    @endif
                    @if ($book->quantity == 0)
                    <button class="btn btn-light w-100" style="color:#b9b9b9 !important"
                        data-product-id="{{ $book->id }}" data-in-cart="false">
                        <i class="bi bi-cart-plus"></i> <span class="cart-btn-text" data-state="add"></span>
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach


    </div>

</div>

<!-- Overlay Section -->

<div class="overlay-section">
    <div class="fixed-background lazybg"
        data-bg="{{ asset('uploads/book1.webp') }}"
        style="
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        ">
    </div>
    <div class="overlay-content">
        <h2>{{ __('messages.becomepartner') }}</h2>
        <p><span style="font-size: 20px"> {{ __('messages.sellfrom') }} </span></p>
        <h2>



            @if (Auth::check() && Auth::user()->role === 'publisher')
            <a href="{{ route('publisher.dashboard') }}" class="btn btn-outline-light" style="font-size: 2vw">
                {{ __('messages.booksellersRoom') }}
            </a>
            @else
            <p> <a href="{{ route('login.publisher') }}" class="btn btn-outline-light">
                    <span style="font-size: 20px"> {{ __('messages.register') }} </span>
                </a></p>
            @endif
        </h2>
    </div>
</div>


<!-- New Sections: Book News and Popular Books -->
<div class="container mt-5">
    <div class="row">
        <!-- Book News -->
        <div class="col-md-8">
            <div class="hr-with-text" style="position: relative; top:-12px">
                <h2 style="position: relative; font-size: 26px;">
                    {{ __('messages.bookstories') }}
                </h2>
            </div>

            <div class="news-list">
                @foreach ($news as $item)
                <a href="{{ route('full_news', [
                        'title' => Str::slug(app()->getLocale() === 'en' && $item->title_en ? $item->title_en : $item->title),
                        'id' => $item->id
                    ]) }}" class="news-item">

                    <div class="news-thumb">
                        @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}"
                            alt="{{ $item->title }}"
                            loading="lazy">
                        @endif
                    </div>

                    <div class="news-content">
                        <h4 class="news-title">
                            {{ app()->getLocale() === 'en' && $item->title_en ? $item->title_en : $item->title }}
                        </h4>

                        <div class="news-meta">
                            <i class="bi bi-book"></i> {{ __('messages.bookstories') }}
                        </div>
                    </div>
                </a>
                @endforeach
            </div>


            <div class="text-center mt-3 ">
                <a href="{{ route('allbooksnews') }}"
                    class="btn btn-light btn-sm w-100" style="border:1px solid #d3d6d8">
                    <span> <i class="bi bi-newspaper"></i> {{ __('messages.readmore') }} → </span>
                </a>
            </div>
        </div>



        <!-- Popular Books (One Block) -->
        <div class="col-md-4">

            @if($showAuctionSidebar)

            {{-- AUCTIONS SIDEBAR --}}
            <h5 class="section-title"
                style="position: relative; margin-bottom: 20px; padding-bottom:20px; align-items: left;
    justify-content: left;">
                <span class="auction-gold-icon">
                    <i class="bi bi-coin"></i>
                </span>
                მიმდინარე აუქციონები
            </h5>

            <div class="card p-3" style="background-color: #f8f9fa;">

                @foreach($activeAuctions as $auction)
                <a href="{{ route('auction.show', $auction->id) }}"
                    class="news-item">

                    @php
                    $images = $auction->book?->images ?? collect();
                    $mainImage = $images->first()->path ?? $auction->book?->photo;
                    @endphp
                    <div class="news-thumb">
                        <img
                            src="{{ $mainImage
            ? asset('storage/' . $mainImage)
            : asset('images/default-book.jpg') }}"
                            alt="{{ $auction->book?->title }}"
                            loading="lazy">
                    </div>

                    <div class="news-content">
                        <h4 class="news-title">
                            {{ $auction->book?->title }}
                        </h4>

                        <div class="news-meta">
                            💰 {{ number_format($auction->current_price) }} ₾
                            · ⏳ {{ \Carbon\Carbon::parse($auction->end_time)->diffForHumans() }}
                        </div>
                    </div>

                </a>

                @if(!$loop->last)

                @endif
                @endforeach

                <div class="text-center mt-3">
                    <a href="{{ route('auction.index') }}"
                        class="btn btn-outline-secondary btn-sm w-100">
                        ყველა აუქციონი →
                    </a>
                </div>

            </div>

            @else

            {{-- FREQUENTLY VIEWED (UNCHANGED) --}}
            <h5 class="section-title mb-3">
                <i class="bi bi-fire"></i> {{ __('messages.viewed') }}
            </h5>



            <div class="card mb-3 p-3">

                @foreach ($topRatedArticle as $book)
                <div class="popular-book-item mb-2">
                    <div class="book-details">
                        <a href="{{ route('full', ['title' => Str::slug($book->title), 'id' => $book->id]) }}{{ request('lang') ? '?lang=' . request('lang') : '' }}"
                            class="card-link" style="text-decoration: none">

                            @if (isset($book->photo))
                            <img src="{{ asset('storage/' . $book->photo) }}" alt="წიგნის სურათი"
                                class="cover img-fluid"
                                style="border-radius: 4px; object-fit: cover; height: 50px; width: 50px; float:left; padding-right: 10px">
                            @endif

                            <span class="book-title" style="color:black">{{ $book->title }}</span> <br>
                            <span>
                                {{ $book->author->getLocalizedName() }} </span>
                        </a>
                    </div>
                </div>

                @if (!$loop->last)
                <hr>
                @endif
                @endforeach
            </div>

            @endif

        </div>


    </div>
</div>
</div>
@php
$partners = [
[
'url' => 'https://intelekti.ge/',
'image' =>
app()->getLocale() === 'ka'
? asset('images/partners_logo/Intelekti Publishing_logo-geo.png')
: asset('images/partners_logo/Intelekti_Publishing_logo_Eng.png'),
'alt' => 'Intelekti',
],
[
'url' => 'https://sulakauri.ge/',
'image' =>
app()->getLocale() === 'en'
? asset('images/partners_logo/sulakauri.png')
: asset('images/partners_logo/sulakauri_logo.svg'),
'alt' => 'Sulakauri',
],
[
'url' => 'https://www.artanuji.ge/',
'image' => app()->getLocale() === 'en'
? asset('images/partners_logo/artanuji_logo_en.png')
: asset('images/partners_logo/artanuji_logo.png'),
'alt' => 'Artanuji'
],
[
'url' => 'https://www.palitral.ge/',
'image' => app()->getLocale() === 'en'
? asset('images/partners_logo/palitra.png')
: asset('images/partners_logo/palitra.png'),
'alt' => 'Palitra L'
],
];

shuffle($partners);
@endphp

<!-- Partners Section -->
<div class="container my-5">
    <div class="hr-with-text text-center mb-4">
        <h2 style="font-size: 26px;">{{ __('messages.ourpartners') }}</h2>
    </div>
    <div class="row justify-content-center align-items-center">

        @foreach ($partners as $partner)
        <div class="col-6 col-md-3 mb-4">
            <a href="{{ $partner['url'] }}" target="blank">
                <img src="{{ $partner['image'] }}" alt="{{ $partner['alt'] }}" class="img-fluid partners"
                    style="max-height: 60px;">
            </a>
        </div>
        @endforeach

    </div>
</div>



<!-- Cookie Consent Notification -->

<div id="cookie-consent" class="cookie-bar shadow-lg" style="display: none;">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center py-2">
        <div class="text-start text-dark">
            {{ __('messages.cookie') }} <a href="{{ route('terms_conditions') }}"
                class="text-primary text-decoration-underline textForCookie">{{ __('messages.seeRules') }}</a>.
        </div>
        <div class="mt-3 mt-md-0">
            <button id="accept-cookies"
                class="btn btn-success btn-sm me-2">{{ __('messages.acceptCookie') }}</button>
            <button id="reject-cookies"
                class="btn btn-outline-secondary btn-sm">{{ __('messages.declineCookie') }}</button>
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


<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.lazybg').forEach(div => {
            const bg = div.dataset.bg;
            if (bg) {
                div.style.backgroundImage = `url('${bg}')`;
                div.style.backgroundRepeat = 'no-repeat';
                div.style.backgroundPosition = 'center center';
                div.style.backgroundSize = 'cover';
                div.style.backgroundAttachment = 'fixed'; // keep your old effect
            }
        });
    });
</script>



@endsection