@php $isHomePage = $isHomePage ?? false; @endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<script src="{{ asset('js/cookieConsent.js') }}"></script>
<script>
    window.cookieConsentConfig = {
        csrf: '{{ csrf_token() }}',
        user_name: '{{ Auth::check() ? Auth::user()->name : 'Guest' }}',
        storeUrl: '{{ route('store-user-behavior') }}'
    };
</script>

<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-D4Q2EZ7SGK"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-D4Q2EZ7SGK');
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Language" content="ka">
    <meta name="language" content="ka">
    <meta name="description"
        content="{{ $isHomePage ? 'პირველი ბუკინისტური ონლაინ მაღაზია საქართველოში' : (isset($book) ? $book->description : (isset($booknews) ? $booknews->description : 'პირველი ბუკინისტური ონლაინ მაღაზია საქართველოში')) }}">
    <meta property="og:title"
        content="{{ $isHomePage ? 'ბუკინისტები' : (isset($book) ? $book->title : (isset($booknews) ? $booknews->title : 'ბუკინისტები')) }}">
    <meta property="og:description"
        content="{{ $isHomePage ? 'პირველი ბუკინისტური ონლაინ მაღაზია საქართველოში' : (isset($book) ? $book->description : (isset($booknews) ? $booknews->description : 'პირველი ბუკინისტური ონლაინ მაღაზია საქართველოში')) }}">
    <meta property="og:url" content="{{ Request::fullUrl() }}">
    <meta property="og:image"
        content="{{ $isHomePage ? asset('default.webp') : (isset($book) ? asset('storage/' . $book->photo) : (isset($booknews) ? asset('storage/' . $booknews->image) : asset('default.webp'))) }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="bukinistebi.ge">
    <meta property="og:type" content="website" />
    <meta name="keywords"
        content="ბუკინისტური წიგნები, ბუკინისტები, ძველი წიგნები, ბუკინისტური მაღაზია, წიგნების ყიდვა-გაყიდვა, წიგნები, books, rare books, used books, antique books">



    <!-- Additional Meta Tags -->
    <meta name="keywords"
        content="ბუკინისტური წიგნები, ბუკინისტები, ძველი წიგნები, ბუკინისტური მაღაზია, წიგნების ყიდვა-გაყიდვა, წიგნები, books, rare books, used books, antique books">
    <meta name="author" content="{{ $book->author->name ?? 'Unknown Author' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (app()->getLocale() === 'en')
        <link href="https://fonts.googleapis.com/css2?family=Noto+Serif&display=swap" rel="stylesheet">
    @endif
    <title>@yield('title', 'Bukinistebi.ge')</title>

    <!-- Twitter Card Meta Tags (Optional) -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $book->title ?? 'bukinistebi - ბუკინისტური მაღაზია' }}">
    <meta name="twitter:description"
        content="{{ $book->description ?? 'პირველი ბუკინისტური ონლაინ მაღაზია საქართველოში' }}">
    <meta name="twitter:image"
        content="{{ isset($book) && $book->photo
            ? asset('storage/' . $book->photo)
            : (isset($booknews) && $booknews->image
                ? asset('storage/' . $booknews->image)
                : asset('default.webp')) }}">
    <meta name="robots" content="index, follow">





    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>

    <!-- Custom CSS -->
    <link rel="icon" href="{{ asset('uploads/favicon/favicon.png') }}" type="image/x-icon">

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    @if (app()->getLocale() === 'en')
        <style>
            .navbar-nav .nav-link,
            .dropdown-menu .dropdown-item,
            .btn,
            body {
                font-family: 'Noto Serif', serif;
            }
        </style>
    @endif
    @if (isset($book))
        <script type="application/ld+json">
        {
          "@context": "https://schema.org/",
          "@type": "Product",
          "name": "{{ $book->title }}",
          "image": "{{ asset('storage/' . $book->photo) }}",
          "description": "{{ $book->description }}",
          "sku": "{{ $book->id }}",
          "brand": {
            "@type": "Brand",
            "name": "{{ $book->author->name ?? 'Unknown Author' }}"
          },
          "offers": {
            "@type": "Offer",
            "url": "{{ Request::fullUrl() }}",
            "priceCurrency": "GEL",
            "price": "{{ $book->price }}",
            "itemCondition": "https://schema.org/UsedCondition",
            "availability": "https://schema.org/InStock"
          }
        }
        </script>
    @endif

    <!-- Meta Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '1049503350038938');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=1049503350038938&ev=PageView&noscript=1" /></noscript>
    <!-- End Meta Pixel Code -->
</head>

<body>

    <!-- ✅ Top Navbar: right -->
    <nav id="topStickyNavbar" class="navbar navbar-light bg-dark border-bottom py-2">
        <div class="container d-flex justify-content-between align-items-center">


            <!-- Right Side: Cart, Login, Language, Search -->
            <div class="d-flex align-items-center gap-3 flex-wrap d-none d-md-flex">
                <div class="col text-center">

                    <a href="https://www.facebook.com/bukinistebi.georgia" class="fb-icon-top" target="blank"><i
                            class="bi bi-facebook fs-5"></i></a>
                    <a href="https://www.instagram.com/bukinistebi.ge/" class="insta-icon-top" target="blank"><i
                            class="bi bi-instagram fs-5"></i></a>

                </div>
            </div>

            <!-- Right Side: Cart, Login, Language -->
            <ul class="navbar-nav flex-row align-items-center gap-3 flex-wrap ms-auto">

            <!-- Cart -->
            @if (!auth()->check() || auth()->user()->role !== 'publisher')
            <li class="nav-item kalata">
                @php
                    $cartCount = 0;
                    if (Auth::check() && Auth::user()->cart) {
                        $cartCount = Auth::user()->cart->cartItems->count();
                    }
                @endphp
                <!-- Cart Link in the Navbar -->
                <a class="nav-link" href="{{ route('cart.index') }}" style="position: relative;">
                    <i class="bi bi-cart-fill" style="position: relative; top:2px;"></i> {{ __('messages.cart') }}
                    <div id="cart-bubble" class="custom-bubble" style="display: {{ $cartCount > 0 ? 'inline-block' : 'none' }};">
                        <span id="cart-count">{{ $cartCount }}</span>
                    </div>
                </a>

                    </li>
                @endif


        <!-- Right Side of Navbar -->
        @guest
            <li class="nav-item dropdown kalata" style="z-index: 1000000">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false" v-pre>
                    <i class="bi bi-file-earmark-person" style="position: relative; font-size: 14px"></i>
                    {{ __('messages.login') }}
                </a>

                        <!-- Dropdown with Tabs -->
                        <ul class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="navbarDropdown"
                            style="width: 350px; z-index: 1000000;" id="dropdown-menu">
                            <!-- Tab Navigation -->
                            <ul class="nav nav-tabs" id="authTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="login-tab" data-bs-toggle="tab"
                                        data-bs-target="#login" type="button" role="tab" aria-controls="login"
                                        aria-selected="true">
                                        {{ __('messages.user') }}
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="register-tab" data-bs-toggle="tab"
                                        data-bs-target="#register" type="button" role="tab"
                                        aria-controls="register" aria-selected="false">
                                        {{ __('messages.bookseller') }}
                                    </button>
                                </li>
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content" id="authTabsContent">
                                <!-- Users Login Tab -->

                                <div class="tab-pane fade show active" id="login" role="tabpanel"
                                    aria-labelledby="login-tab">

                                    <a class="nav-link mt-2" href="{{ route('login') }}">
                                        <i class="bi bi-key"></i> {{ __('messages.authorization') }}
                                    </a>
                                    @if (Route::has('register'))
                                        <a class="nav-link mt-3" href="{{ route('register') }}">
                                            <i class="bi bi-person-fill-add"></i> {{ __('messages.registration') }}</a>
                                    @endif
                                </div>

                                <!-- Bukinist login Tab -->
                                <div class="tab-pane fade" id="register" role="tabpanel"
                                    aria-labelledby="register-tab">


                            <a class="nav-link mt-3" href="{{ route('login.publisher') }}">
                                <i class="bi bi-box-arrow-in-right"></i> {{__('messages.booksellerauth')}}  
                            </a>
                            @if (Route::has('register'))
                                <a class="nav-link mt-3" href="{{ route('register.publisher') }}">
                                    <i class="bi bi-person-plus"></i> {{__('messages.booksellerreg')}}</a>
                            @endif
                        </div>
                    </div>
                </ul>
            </li>
        @else
            <li class="nav-item dropdown kalata" style="z-index: 1000000">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false" v-pre>
                    <i class="bi bi-file-earmark-person" style="position: relative; font-size: 14px"></i>
                    {{ Auth::user()->name }}
                </a>

                        <!-- Dropdown Menu for Logged-In Users -->
                        <ul class="dropdown-menu dropdown-menu-end" style="z-index: 1000000">
                            @if (Auth::user()->role === 'publisher')
                                <li style="margin-top:15px;"><a class="dropdown-item"
                                        href="{{ route('publisher.dashboard') }}">
                                        <i class="bi bi-door-open"></i>&nbsp;{{ __('messages.booksellersRoom') }}
                                    </a></li>
                                <li><a class="dropdown-item" href="{{ route('publisher.my_books') }}">
                                        <i class="bi bi-book"></i> &nbsp;{{ __('messages.myUploadedBooks') }}
                                    </a></li>
                                <li><a class="dropdown-item" href="{{ route('publisher.account.edit') }}">
                                        <i class="bi bi-pencil"></i> &nbsp;{{ __('messages.editProfile') }}
                                    </a></li>
                            @else
                                <li style="margin-top:15px;">
                                    <a class="dropdown-item" href="{{ route('purchase.history') }}">
                                        <i class="bi bi-credit-card-2-front"></i>
                                        &nbsp;{{ __('messages.purchaseHistory') }}
                                    </a>
                                </li>
                                <li style="margin-top:15px; padding-bottom:10px;">
                                    <a class="dropdown-item" href="{{ route('account.edit') }}">
                                        <i class="bi bi-pencil"></i> &nbsp;{{ __('messages.editProfile') }}
                                    </a>
                                </li>

                                <li style="padding-bottom:10px;">
                                    <a class="dropdown-item" href="{{ route('my.bids') }}">
                                        <i class="bi bi-hammer"></i> &nbsp;{{ __('messages.myAuctions') }}
                                    </a>
                                </li>
                            @endif
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                 this.closest('form').submit();">
                                        <i class="bi bi-box-arrow-right"></i> &nbsp;{{ __('messages.logout') }}
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest

            <!-- Language -->
            <li class="nav-item dropdown kalata">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                    <img src="{{ asset('images/flags/' . app()->getLocale() . '.svg') }}" width="20" class="me-1">
                    {{ strtoupper(app()->getLocale()) }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end" style="z-index: 50000 !Important">
                    <li><a class="dropdown-item" href="#" onclick="switchLanguage('ka')">ქართული</a></li>
                    <li><a class="dropdown-item" href="#" onclick="switchLanguage('en')">English</a></li>
                </ul>
            </li>

            </ul>
        </div>
    </nav>



    <div style="position: relative; z-index: 10050; ">
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-2" style=" position: relative;">
            <div class="container" style="position: relative;  ">




                <a class="navbar-brand" href="{{ url('/') }}"><img
                        src="{{ asset('uploads/logo/bukinistebi.ge.png') }}" width="130px"
                        style="position:relative;  " loading="lazy" alt="bukinstebi_logo"></a>


                <!-- ✅ Mobile Language Switcher Floating Top-Right -->


                <button style="position: relative;  " class="navbar-toggler" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>


                <div class="collapse navbar-collapse" id="navbarNav">


                    <ul class="navbar-nav ms-auto" style="position: relative;   z-index: 100000;">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}">{{ __('messages.home') }}</a>
                        </li>


                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="genreDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('messages.categories') }}
                            </a>
                            <ul class="dropdown-menu genre-scroll" aria-labelledby="genreDropdown"
                                style="max-height: 300px; overflow-y: auto; min-width: 250px;">
                                <li class="px-3 py-2">
                                    <input type="text" class="form-control" id="genreSearchInput"
                                        placeholder="{{ __('messages.searchcategory') }}...">
                                </li>


                                <li class="genre-item all-item" data-name="{{ __('messages.all') }}">
                                    <a class="dropdown-item"
                                        href="{{ route('books') }}">{{ __('messages.all') }}</a>
                                </li>


                                <li id="noResultsMessage" class="text-muted px-3 py-2" style="display: none;">
                                    {{ __('messages.noresult') }}
                                </li>

                                @foreach ($genres as $genre)
                                    @php
                                        $genreName =
                                            app()->getLocale() === 'en' && $genre->name_en
                                                ? $genre->name_en
                                                : $genre->name;
                                    @endphp
                                    <li class="genre-item" data-name="{{ $genreName }}">
                                        <a class="dropdown-item"
                                            href="{{ route('genre.books', ['id' => $genre->id, 'slug' => Str::slug($genreName)]) }}">
                                            {{ $genreName }}
                                        </a>
                                    </li>
                                @endforeach

                            </ul>

                        </li>

                        <li class="nav-item">
                            <a class="nav-link"
                                href="{{ route('auction.index') }}">{{ __('messages.auctions') }}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('podcast') }}">{{ __('messages.podcast') }}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('order_us') }}">{{ __('messages.order') }}</a>
                        </li>


                        <form class="d-flex" role="search" action="{{ route('search') }}" method="GET"
                            onsubmit="return validateSearch()" style="position: relative;  ">
                            <input class="form-control me-2 styled-input" name="title" type="search"
                                value="{{ request()->get('title') }}"
                                placeholder="{{ __('messages.booksearch') }}..." aria-label="Search"
                                id="searchInput">
                            <button class="btn btn-outline-success submit-search" type="submit"
                                style="border-bottom-right-radius:0px; border-top-left-radius:0px; border:0px; "><i
                                    class="bi bi-search" style="position: relative;  "></i></button>
                        </form>


                    </ul>


                </div>
            </div>
        </nav>
    </div>
    <!-- Main Content -->
    <div class="container mt-5">
        @yield('content') <!-- Page-specific content goes here -->
    </div>


    <!-- Footer -->
    <footer class="bg-dark text-white text-center text-lg-start mt-5">
        <div class="container p-4">
            <div class="row">
                <!-- Column 1 -->
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase">Bukinistebi.ge</h5>
                    <p><span style="padding-right:33px">{{ __('messages.numberone') }}</span></p>
                </div>

                <!-- Terms and Conditions Column -->
                <div class="col-lg-3 offset-lg-1 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase">{{ __('messages.forcustomers') }}</h5>
                    <p>
                        <a href="{{ route('terms_conditions') }}" class="text-white text-decoration-none">
                            <span>{{ __('messages.terms') }}</span>
                        </a>
                    </p>
                </div>

                <!-- Column 3 -->
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase">{{ __('messages.contact') }}</h5>
                    <ul class="list-unstyled">
                        <span>bukinistebishop@gmail.com</span>
                    </ul>
                </div>

                <!-- Column 4 -->
                <div class="col-lg-2 col-md-5 mb-4 mb-md-0">
                    <h5>{{ __('messages.newsletter') }}</h5>
                    <form id="subscriptionForm" method="POST" action="{{ route('subscribe') }}">
                        @csrf
                        <input type="email" name="email" class="form-control mb-2"
                            placeholder="{{ __('messages.entermail') }}" required>
                        <button type="submit" class="btn btn-primary w-100">{{ __('messages.subscribe') }}</button>
                    </form>

                    <!-- Hidden input for success -->
                    @if (session('subscription_success'))
                        <input type="hidden" id="subscriptionSuccessFlag" value="true">
                    @endif

                    <!-- Hidden input for errors -->
                    @if ($errors->any())
                        <input type="hidden" id="subscriptionErrorFlag" value="true">
                        <input type="hidden" id="subscriptionErrorMessages"
                            value="{{ implode('|', $errors->all()) }}">
                    @endif

                    <br>

                    <!-- TOP.GE ASYNC COUNTER CODE -->
                    <div id="top-ge-counter-container" data-site-id="117729" style="float: right"></div>
                    <script async src="//counter.top.ge/counter.js"></script>
                    <!-- / END OF TOP.GE COUNTER CODE -->
                </div>

                <!-- Success Modal -->
                <div class="modal fade" id="subscriptionSuccessModal" tabindex="-1" role="dialog"
                    aria-labelledby="subscriptionSuccessModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="subscriptionSuccessModalLabel" style="color: black">
                                    გზავნილი</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="color: black">
                                მადლობა გამოწერისთვის!
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Error Modal -->
                <div class="modal fade" id="subscriptionErrorModalPageSpecific" tabindex="-1" role="dialog"
                    aria-labelledby="subscriptionErrorModalLabelPageSpecific" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="subscriptionErrorModalLabelPageSpecific"
                                    style="color: black">Warning</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="color: black">
                                <ul id="subscriptionErrorListPageSpecific"></ul>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row mt-4">
                    <!-- Social Media -->
                    <div class="col text-center">
                        <h5 class="text-uppercase" style="position: relative; left:-15px">{{ __('messages.follow') }}
                        </h5>
                        <a href="https://www.facebook.com/bukinistebi.georgia" class="fb-icon" target="blank"><i
                                class="bi bi-facebook fs-5"></i></a>
                        <a href="https://www.instagram.com/bukinistebi.ge/" class="insta-icon" target="blank"><i
                                class="bi bi-instagram fs-5"></i></a>
                        <a href="https://www.youtube.com/channel/UCrXyA0hq0gDJME5wgRGTbbA" class="youtube-icon"
                            target="blank"><i class="bi bi-youtube fs-3"></i></a>
                        <a href="#" class="tiktok-icon"><i class="bi bi-tiktok fs-5"></i></a>
                    </div>
                </div>


            </div>

            <div class="text-center p-3 bg-dark">
                <!-- Additional footer content if needed -->

            </div>
    </footer><!-- Script -->





    <script>
        function switchLanguage(locale) {
            fetch(`/lang/${locale}`)
                .then(() => {
                    location.reload(true); // force full reload
                });
        }
    </script>




    <script>
        function validateSearch() {
            var searchInput = document.getElementById('searchInput').value.trim();
            if (searchInput === "") {
                return false; // Prevent form submission
            }
            return true;
        }
    </script>

    <!-- Add this JavaScript at the bottom of your Blade layout -->
    <script>
        // Prevent the dropdown from closing when interacting with tabs or dropdown content
        document.querySelectorAll('.dropdown-menu').forEach(function(dropdown) {
            dropdown.addEventListener('click', function(e) {
                e.stopPropagation(); // Prevent the default dropdown close behavior
            });
        });

        const input = document.getElementById('genreSearchInput');
        const items = document.querySelectorAll('.genre-item');
        const noResults = document.getElementById('noResultsMessage');

        input.addEventListener('keyup', function() {
            const filter = input.value.trim().toLowerCase();
            let visibleCount = 0;

            items.forEach(function(item) {
                const name = item.getAttribute('data-name').toLowerCase();

                // Hide "ყველა" when searching
                if (item.classList.contains('all-item') && filter !== '') {
                    item.style.display = 'none';
                } else if (name.includes(filter)) {
                    item.style.display = '';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Show/hide the "no results" message
            noResults.style.display = visibleCount === 0 ? '' : 'none';
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle subscription success modal
            const subscriptionSuccessFlag = document.getElementById('subscriptionSuccessFlag');
            if (subscriptionSuccessFlag && subscriptionSuccessFlag.value === 'true') {
                const subscriptionSuccessModal = new bootstrap.Modal(document.getElementById(
                    'subscriptionSuccessModal'));
                subscriptionSuccessModal.show();
            }

            // Handle subscription error modal
            const subscriptionErrorFlag = document.getElementById('subscriptionErrorFlag');
            if (subscriptionErrorFlag && subscriptionErrorFlag.value === 'true') {
                const subscriptionErrorModal = new bootstrap.Modal(document.getElementById(
                    'subscriptionErrorModalPageSpecific'));
                const errorMessages = document.getElementById('subscriptionErrorMessages').value.split('|');

                // Populate error messages in the modal
                const errorList = document.getElementById('subscriptionErrorListPageSpecific');
                errorList.innerHTML = ''; // Clear previous errors if any
                errorMessages.forEach(message => {
                    const li = document.createElement('li');
                    li.textContent = message;
                    errorList.appendChild(li);
                });

                subscriptionErrorModal.show();
            }
        });
    </script>


    <!-- Include jQuery -->

    <!-- Include Bootstrap JS if needed -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" defer></script>





    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const topNavbar = document.getElementById('topStickyNavbar');

            window.addEventListener('scroll', function() {
                if (window.scrollY > 10) {
                    topNavbar.classList.add('scrolled');
                } else {
                    topNavbar.classList.remove('scrolled');
                }
            });
        });
    </script>


    <script>
        // Lazy load images and videos using IntersectionObserver

        document.addEventListener("DOMContentLoaded", function() {
            let lazyImages = [].slice.call(document.querySelectorAll("img.lazyload"));
            let lazyVideos = [].slice.call(document.querySelectorAll("video.lazy-video"));

            if ("IntersectionObserver" in window) {
                let lazyLoadObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            let element = entry.target;
                            if (element.tagName === "IMG") {
                                element.src = element.dataset.src;
                            } else if (element.tagName === "VIDEO") {
                                element.src = element.dataset.src;
                            }
                            observer.unobserve(element);
                        }
                    });
                });

                lazyImages.forEach((img) => {
                    lazyLoadObserver.observe(img);
                });

                lazyVideos.forEach((video) => {
                    lazyLoadObserver.observe(video);
                });
            }
        });
    </script>

    <script>
        @auth
        // Only run this if the user is logged in
        var userId = @json(Auth::id()); // Pass the logged-in user's ID to JavaScript
        @else
            // Set userId to null if not logged in
            var userId = null;
        @endauth
    </script>











    <!-- Include cookieConsent.js -->




    @yield('scripts')



    <!--Start of Tawk.to Script
<script type="text/javascript">
    var Tawk_API = Tawk_API || {},
        Tawk_LoadStart = new Date();
    (function() {
        var s1 = document.createElement("script"),
            s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/6746c7b82480f5b4f5a48c56/1idm7oaai';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
</script>
   End of Tawk.to Script-->


   @if(Auth::check() && Auth::user()->cart && Auth::user()->cart->cartItems()->count() > 0)
    <div class="sticky-cart-summary d-block d-md-none">
        <a href="{{ route('cart.index') }}" class="btn btn-primary w-100 d-flex justify-content-between align-items-center">
            <span><i class="bi bi-cart-fill"></i> კალათაში {{ Auth::user()->cart->cartItems()->count() }} წიგნი გაქვს </span>
            <span>ნახე კალათა</span>
        </a>
    </div>
@endif
@stack('scripts'):
 

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
    function updateCartCount(count) {
        const countElement = document.getElementById('cart-count');
        const bubble = document.getElementById('cart-bubble');

        if (countElement && bubble) {
            countElement.textContent = count;

            if (parseInt(count) > 0) {
                bubble.style.display = 'inline-block';
            } else {
                bubble.style.display = 'none';
            }
        }
    }

    $(document).ready(function() {
        const translations = {
            added: @json(__('messages.added')),
            addToCart: @json(__('messages.addtocart'))
        };

        $('.toggle-cart-btn').click(function() {
            var button = $(this);
            var bookId = button.data('product-id');
            var quantity = parseInt($('#quantity').val()) || 1;


            $.ajax({
                url: '{{ route('cart.toggle') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    book_id: bookId,
                    quantity: quantity
                },
                success: function(response) {
                    if (response.success) {
                        if (response.action === 'added') {
                            button.removeClass('btn-primary').addClass('btn-success');
                            button.find('i').removeClass('bi-cart-plus').addClass('bi-check-circle');
                            button.find('.cart-btn-text').text(translations.added);
                        } else if (response.action === 'removed') {
                            button.removeClass('btn-success').addClass('btn-primary');
                            button.find('i').removeClass('bi-check-circle').addClass('bi-cart-plus');
                            button.find('.cart-btn-text').text(translations.addToCart);
                        }

                        updateCartCount(response.cart_count);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    alert('{{ __('messages.loginrequired') }}');
                }
            });
        });
    });

    function updateCartCount(count) {
        const countElement = document.getElementById('cart-count');
        const bubble = document.getElementById('cart-bubble');

        if (countElement && bubble) {
            countElement.textContent = count;
            if (parseInt(count) > 0) {
                bubble.style.display = 'inline-block';
            } else {
                bubble.style.display = 'none';
            }
        }
    }
</script>
</body>

</html>
