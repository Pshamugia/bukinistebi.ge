@extends('layouts.app')

@section('title', 'Bukinistebi.ge - ონლაინ მაღაზია')

@section('content')

    <!-- Hero Section -->
    <div class="hero-section"
        style="background: url('{{ asset('uploads/book9.webp') }}') no-repeat center center; background-size: cover; background-attachment: fixed; top:-74px">
        <div class="hero-content" style="position: relative; padding-top: 15px;">



            <h1>{{ __('messages.bookstore') }}</h1>
            <h5><a href="{{ route('books') }}" class="btn btn-outline-light"
                    style="font-size: 18px">{{ __('messages.searchfor') }}</a></h5>

        </div>
    </div>

    <!-- Featured Books -->
    <div class="container mt-5">

        <div class="hr-with-text" style="position: relative; margin-top:-54px; top:-10px">
            <h2 style="position: relative; font-size: 26px; ">

                {{ __('messages.recently') }} </h2>
        </div>




        <div class="row">
            @foreach ($books as $book)
                <div class="col-md-3" style="position: relative; padding-bottom: 25px;">
                    <div class="card book-card shadow-sm" style="border: 1px solid #f0f0f0; border-radius: 8px;">
                        <a href="{{ route('full', ['title' => Str::slug($book->title), 'id' => $book->id]) }}"
                            class="card-link">
                            <div class="image-container"
                                style="background-image: url('{{ asset('images/default_image.png') }}');">
                                <img src="{{ asset('storage/' . $book->photo) }}" alt="{{ $book->title }}"
                                    class="cover img-fluid" style="border-radius: 8px 8px 0 0; object-fit: cover;"
                                    onerror="this.onerror=null;this.src='{{ asset('images/default_image.png') }}';">
                            </div>
                        </a>
                        <div class="card-body">
                            <h4 class="font-weight-bold">{{ \Illuminate\Support\Str::limit($book->title, 18) }}</h4>
                            {{-- Author --}}
                            <p class="text-muted mb-2" style="font-size: 14px;">
                                <i class="bi bi-person"></i>
                                <a href="{{ route('full_author', ['id' => $book->author_id, 'name' => Str::slug($book->author->name)]) }}"
                                    class="text-decoration-none text-primary">
                                    {{ app()->getLocale() === 'en' ? $book->author->name_en : $book->author->name }}

                                </a>
                            </p>
                            <p style="font-size: 18px; color: #333;">
                                <img src="{{ asset('images/GEL.png') }}" width="23px"> <span
                                    class="text-dark fw-semibold" style="position: relative; top:3px;">
                                    {{ number_format($book->price) }}
                                </span>
                                <span style="position: relative; top:5px; ">
                                    @if ($book->quantity == 0)
                                        <span class="badge bg-danger" style="font-weight: 100; float: right;">
                                            {{ __('messages.outofstock') }} </span>
                                    @elseif($book->quantity == 1)
                                        <span class="badge bg-warning text-dark"
                                            style="font-size: 13px; font-weight: 100; float: right;">{{ __('messages.available') }} </span>
                                    @else
                                        <span class="badge bg-success"
                                            style="font-size: 13px; font-weight: 100; float: right;">
                                            {{ __('messages.available') }} {{ $book->quantity }} {{ __('messages.items') }}</span>
                                    @endif
                                </span>
                            </p>

                            {{-- Cart Buttons --}}
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

                        </div>
                    </div>
                </div>
            @endforeach


        </div>

    </div>

    <!-- Overlay Section -->

    <div class="overlay-section">
        <div class="fixed-background"
            style="background: url('{{ asset('uploads/book1.webp') }}') no-repeat center center; background-size: cover; background-attachment: fixed;">
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
                    <a href="{{ route('login.publisher') }}" class="btn btn-outline-light" style="font-size: 2vw">
                        {{ __('messages.register') }}
                    </a>
                @endif
            </h2>
        </div>
    </div>


    <!-- New Sections: Book News and Popular Books -->
    <div class="container mt-5">
        <div class="row">
            <!-- Book News (Two Columns) -->
            <div class="col-md-8">
                <div class="hr-with-text" style="position: relative; top:-12px">
                    <h2 style="position: relative; font-size: 26px;">
                        {{ __('messages.bookstories') }}
                    </h2>
                </div>

                <div class="row">
                    @foreach ($news as $item)
                        <div class="col-md-6 col-lg-6"> <!-- Adjusted columns for responsiveness -->
                            <div class="card mb-4 shadow-sm border-0"> <!-- Added shadow and border styling -->
                                <a href="{{ route('full_news', ['title' => Str::slug(app()->getLocale() === 'en' && $item->title_en ? $item->title_en : $item->title), 'id' => $item->id]) }}"
                                    class="card-link text-decoration-none">
                                    @if (isset($item->image))
                                        <div class="image-container">
                                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}"
                                                loading="lazy" class="card-img-top rounded-top img-fluid cover_news"
                                                id="im_news">
                                        </div>
                                    @endif
                                    <div class="card-body">
                                        <h4 class="card-title text-dark">    {{ app()->getLocale() === 'en' && $item->title_en ? $item->title_en : $item->title }}
                                        </h4>
                                        <!-- Limit title length -->
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach

                </div>


                <!-- "Read All Book News" Button -->
                <div class="text-center mt-4" style="position: relative; top:-22px">
                    <a href="{{ route('allbooksnews') }}" class="btn  btn-outline-secondary btn-lg"
                        style="font-size: 18px; ">
                        <span> <i class="bi bi-newspaper"></i> {{ __('messages.readmore') }} </span>
                    </a>
                </div>
            </div>


            <!-- Popular Books (One Block) -->
            <div class="col-md-4">
                <h5 class="section-title"
                    style="position: relative; margin-bottom: 20px; padding-bottom:20px; align-items: left;
    justify-content: left;">
                    <i class="bi bi-fire"></i> {{ __('messages.viewed') }}
                </h5>
                <div class="card mb-3 p-3">
                    @if ($topRatedArticle->isEmpty())
    <p style="color:red;">No English books found in top-rated.</p>
@endif
                    @foreach ($topRatedArticle as $book)
                    
                        <div class="popular-book-item mb-2">
                            <div class="book-details">
                                <a href="{{ route('full', ['title' => Str::slug($book->title), 'id' => $book->id]) }}"
                                    class="card-link" style="text-decoration: none">

                                    @if (isset($book->photo))
                                        <img src="{{ asset('storage/' . $book->photo) }}" alt="{{ $book->title }}"
                                            class="cover img-fluid"
                                            style="border-radius: 4px; object-fit: cover; height: 50px; width: 50px; float:left; padding-right: 10px">
                                    @endif

                                    <span class="book-title" style="color:black">{{ $book->title }}</span> <br>
                                    <span>
                                        {{ app()->getLocale() === 'en' && $book->author->name_en ? $book->author->name_en : $book->author->name }}
                                    </span>
                                </a>
                            </div>
                        </div>

                        @if (!$loop->last)
                            <hr>
                        @endif
                    @endforeach
                </div>
            </div>

        </div>
    </div>
    </div>


    <!-- Cookie Consent Notification -->

    <div id="cookie-consent" class="cookie-bar shadow-lg" style="display: none;">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center py-2">
            <div class="text-start text-dark">
                ჩვენ ვიყენებთ ქუქიებს სერვისის გასაუმჯობესებლად. <a href="{{ route('terms_conditions') }}"
                    class="text-primary text-decoration-underline">ნახეთ წესები</a>.
            </div>
            <div class="mt-3 mt-md-0">
                <button id="accept-cookies" class="btn btn-success btn-sm me-2">თანხმობა</button>
                <button id="reject-cookies" class="btn btn-outline-secondary btn-sm">უარყოფა</button>
            </div>
        </div>
    </div>


@endsection


@section('scripts')



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>


    <script>
        $(document).ready(function() {
            const translations = {
    added: @json(__('messages.added')),
    addToCart: @json(__('messages.addtocart'))
};
            $('.toggle-cart-btn').click(function() {
                var button = $(this);
                var bookId = button.data('product-id');

                $.ajax({
                    url: '{{ route('cart.toggle') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        book_id: bookId
                    },
                    success: function(response) {
                        if (response.success) {
                            if (response.action === 'added') {
                                button.removeClass('btn-primary').addClass('btn-success');
                                button.find('i').removeClass('bi-cart-plus').addClass(
                                    'bi-check-circle');
                                button.find('.cart-btn-text').text(translations.added);
                                button.data('in-cart', true);
                            } else if (response.action === 'removed') {
                                button.removeClass('btn-success').addClass('btn-primary');
                                button.find('i').removeClass('bi-check-circle').addClass(
                                    'bi-cart-plus');
                                button.find('.cart-btn-text').text(translations.addToCart);
                                button.data('in-cart', false);
                            }

                            // ✅ Update the cart count in the navbar
                            $('#cart-count').text(response.cart_count);

                            // ✅ Manage the abandoned cart cookie
                            if (response.cart_count > 0) {
                                document.cookie = "abandoned_cart=true; max-age=86400; path=/";
                            } else {
                                document.cookie =
                                    "abandoned_cart=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        alert(translations.alert);
                    }

                });
            });
        });
    </script>

    @if (Auth::check())
        @php
            $cartItemCount = Auth::user()->cart?->cartItems()->count() ?? 0;
        @endphp

        @if ($cartItemCount > 0)
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    console.log("✅ Authenticated and cart has {{ $cartItemCount }} items");

                    // Check if cookie exists
                    const cookies = document.cookie.split(';').map(c => c.trim());
                    const alreadyShown = cookies.find(c => c.startsWith('abandoned_cart_shown='));

                    if (!alreadyShown) {
                        // Set cookie to prevent showing again for 1 day
                        document.cookie = "abandoned_cart_shown=true; path=/; max-age=86400";

                        const banner = document.createElement('div');
                        banner.className = 'alert alert-warning alert-dismissible fade show text-center';
                        banner.style.position = 'fixed';
                        banner.style.bottom = '-17px';
                        banner.style.left = '10px';
                        banner.style.right = '10px';
                        banner.style.zIndex = '1000';

                        banner.innerHTML = `
                    <i class="bi bi-cart-fill me-2"></i> თქვენ გაქვთ კალათაში {{ $cartItemCount }} წიგნი
                    <a href="{{ route('cart.index') }}" class="btn btn-sm btn-primary ms-2">ნახეთ კალათა</a>
                    <button type="button" class="btn-close" aria-label="Close" onclick="this.parentElement.remove()"></button>
                `;

                        document.body.appendChild(banner);
                    } else {
                        console.log("⏳ Banner already shown within 1 day — skipping");
                    }
                });
            </script>
        @endif
    @endif

    <script>
        const translations = {
            added: @json(__('messages.added')),
            addToCart: @json(__('messages.addtocart'))
        };

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.cart-btn-text').forEach(function(el) {
                const state = el.getAttribute('data-state');
                if (state === 'added') {
                    el.textContent = translations.added;
                } else {
                    el.textContent = translations.addToCart;
                }
            });
        });
    </script>

@endsection
