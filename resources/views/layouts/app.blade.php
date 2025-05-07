@php $isHomePage = $isHomePage ?? false; @endphp

<!DOCTYPE html>
<html lang="ka">
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

</head>

<body>
    <style>
        .genre-scroll {
            max-height: 300px;
            overflow-y: auto;
            overflow-x: hidden;
            padding-right: 5px;
        }

        .genre-scroll::-webkit-scrollbar-thumb {
            background-color: #ccc;
            border-radius: 3px;
        }
    </style>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}"><img
                    src="{{ asset('uploads/logo/bukinistebi.ge.png') }}" width="130px"
                    style="position:relative; top:8px" loading="lazy" alt="bukinstebi_logo"></a>
            <button style="position: relative; top: 9px;" class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto" style="position: relative; top: 10px">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">საწყისი</a>
                    </li>


                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="genreDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            კატეგორიები
                        </a>
                        <ul class="dropdown-menu genre-scroll" aria-labelledby="genreDropdown"
                            style="max-height: 300px; overflow-y: auto; min-width: 250px;">
                            <li class="px-3 py-2">
                                <input type="text" class="form-control" id="genreSearchInput"
                                    placeholder="მოძებნე კატეგორიით...">
                            </li>


                            <li class="genre-item all-item" data-name="ყველა">
                                <a class="dropdown-item" href="{{ route('books') }}">ყველა</a>
                            </li>

                            <li id="noResultsMessage" class="text-muted px-3 py-2" style="display: none;">
                                შედეგი ვერ მოიძებნა.
                            </li>

                            @foreach ($genres as $genre)
                                <li class="genre-item" data-name="{{ $genre->name }}">
                                    <a class="dropdown-item"
                                        href="{{ route('genre.books', ['id' => $genre->id, 'slug' => Str::slug($genre->name)]) }}"">{{ $genre->name }}</a>
                                </li>
                            @endforeach
                        </ul>

                    </li>


                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('order_us') }}">შეგვიკვეთე</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('podcast') }}">პოდკასტი</a>
                    </li>


                    @if (!auth()->check() || auth()->user()->role !== 'publisher')
                        <li class="nav-item">
                            @php
                                $cartCount = 0;
                                if (Auth::check() && Auth::user()->cart) {
                                    $cartCount = Auth::user()->cart->cartItems->count();
                                }
                            @endphp
                            <!-- Cart Link in the Navbar -->
                            <a class="nav-link" href="{{ route('cart.index') }}" style="position: relative;">
                                კალათა
                                <div class="custom-bubble">
                                    <span id="cart-count"
                                        style="position: relative; top: 1px;">{{ $cartCount }}</span>
                                </div>
                            </a>

                        </li>
                    @endif


                    <!-- Right Side of Navbar -->
                    @guest
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false" v-pre>
                                <i class="bi bi-file-earmark-person" style="position: relative; font-size: 14px"></i>
                                შესვლა
                            </a>

                            <!-- Dropdown with Tabs -->
                            <ul class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="navbarDropdown"
                                style="width: 300px;" id="dropdown-menu">
                                <!-- Tab Navigation -->
                                <ul class="nav nav-tabs" id="authTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="login-tab" data-bs-toggle="tab"
                                            data-bs-target="#login" type="button" role="tab" aria-controls="login"
                                            aria-selected="true">
                                            მომხმარებელი
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="register-tab" data-bs-toggle="tab"
                                            data-bs-target="#register" type="button" role="tab"
                                            aria-controls="register" aria-selected="false">
                                            ბუკინისტი
                                        </button>
                                    </li>
                                </ul>

                                <!-- Tab Content -->
                                <div class="tab-content" id="authTabsContent">
                                    <!-- Users Login Tab -->

                                    <div class="tab-pane fade show active" id="login" role="tabpanel"
                                        aria-labelledby="login-tab">

                                        <a class="nav-link mt-3" href="{{ route('login') }}">
                                            <i class="bi bi-key"></i> {{ __('ავტორიზაცია') }}
                                        </a>
                                        @if (Route::has('register'))
                                            <a class="nav-link mt-3" href="{{ route('register') }}">
                                                <i class="bi bi-person-fill-add"></i> {{ __('რეგისტრაცია') }}</a>
                                        @endif
                                    </div>

                                    <!-- Bukinist login Tab -->
                                    <div class="tab-pane fade" id="register" role="tabpanel"
                                        aria-labelledby="register-tab">


                                        <a class="nav-link mt-3" href="{{ route('login.publisher') }}">
                                            <i class="bi bi-box-arrow-in-right"></i> {{ __('ბუკინისტის ავტორიზაცია') }}
                                        </a>
                                        @if (Route::has('register'))
                                            <a class="nav-link mt-3" href="{{ route('register.publisher') }}">
                                                <i class="bi bi-person-plus"></i> {{ __('ბუკინისტის რეგისტრაცია') }}</a>
                                        @endif
                                    </div>
                                </div>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false" v-pre>
                                <i class="bi bi-file-earmark-person" style="position: relative; font-size: 14px"></i>
                                {{ Auth::user()->name }}
                            </a>

                            <!-- Dropdown Menu for Logged-In Users -->
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if (Auth::user()->role === 'publisher')
                                    <li style="margin-top:15px;"><a class="dropdown-item"
                                            href="{{ route('publisher.dashboard') }}">
                                            <i class="bi bi-door-open"></i> &nbsp;{{ __('ბუკინისტის ოთახი') }}
                                        </a></li>
                                    <li><a class="dropdown-item" href="{{ route('publisher.my_books') }}">
                                            <i class="bi bi-book"></i> &nbsp;ჩემი ატვირთული წიგნები
                                        </a></li>
                                    <li><a class="dropdown-item" href="{{ route('publisher.account.edit') }}">
                                            <i class="bi bi-pencil"></i> &nbsp;პროფილის რედაქტირება
                                        </a></li>
                                @else
                                    <li style="margin-top:15px;">
                                        <a class="dropdown-item" href="{{ route('purchase.history') }}">
                                            <i class="bi bi-credit-card-2-front"></i> &nbsp;{{ __('შენაძენის ისტორია') }}
                                        </a>
                                    </li>
                                    <li style="margin-top:15px; padding-bottom:10px;">
                                        <a class="dropdown-item" href="{{ route('account.edit') }}">
                                            <i class="bi bi-pencil"></i> &nbsp;{{ __('პროფილის რედაქტირება') }}
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                             this.closest('form').submit();">
                                            <i class="bi bi-box-arrow-right"></i> &nbsp;{{ __('გამოსვლა') }}
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest

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




                    <form class="d-flex" role="search" action="{{ route('search') }}" method="GET"
                        onsubmit="return validateSearch()" style="position: relative; top:-3px;">
                        <input class="form-control me-2 styled-input" name="title" type="search"
                            value="{{ request()->get('title') }}" placeholder="წიგნების ძიება..."
                            aria-label="Search" id="searchInput">
                        <button class="btn btn-outline-success submit-search" type="submit"
                            style="border-bottom-right-radius:0px; border-top-left-radius:0px; border:0px; "><i
                                class="bi bi-search" style="position: relative; top: 2px"></i></button>
                    </form>

                    <script>
                        function validateSearch() {
                            var searchInput = document.getElementById('searchInput').value.trim();
                            if (searchInput === "") {
                                return false; // Prevent form submission
                            }
                            return true;
                        }
                    </script>


                </ul>


            </div>
        </div>
    </nav>

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
                    <p><span style="padding-right:33px">#1 ბუკინისტური ონლაინ მაღაზია საქართველოში</span></p>
                </div>

                <!-- Terms and Conditions Column -->
                <div class="col-lg-3 offset-lg-1 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase">მოხმარებლებისთვის</h5>
                    <p>
                        <a href="{{ route('terms_conditions') }}" class="text-white text-decoration-none">
                            <span>წესები და პირობები</span>
                        </a>
                    </p>
                </div>

                <!-- Column 3 -->
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase">კონტაქტი</h5>
                    <ul class="list-unstyled">
                        <span>bukinistebishop@gmail.com</span>
                    </ul>
                </div>

                <!-- Column 4 -->
                <div class="col-lg-2 col-md-5 mb-4 mb-md-0">
                    <h5>სიახლეების გამოწერა</h5>
                    <form id="subscriptionForm" method="POST" action="{{ route('subscribe') }}">
                        @csrf
                        <input type="email" name="email" class="form-control mb-2" placeholder="ჩაწერე ელფოსტა"
                            required>
                        <button type="submit" class="btn btn-primary w-100">გამოწერა</button>
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
                        <h5 class="text-uppercase" style="position: relative; left:-15px">გვალაიქე</h5>
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
            const navbar = document.querySelector('.navbar');

            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
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
</body>

</html>
