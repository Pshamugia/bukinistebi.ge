@php use Illuminate\Support\Str; @endphp
@php $isHomePage = $isHomePage ?? false; @endphp
@php
    $defaultDescription = 'პირველი ბუკინისტური ონლაინ მაღაზია საქართველოში';
    $pageDescription = $isHomePage
        ? $defaultDescription
        : (isset($book) ? $book->description : (isset($booknews) ? $booknews->description : $defaultDescription));
    $plainPageDescription = Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($pageDescription))), 160, '');
@endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    window.gtag = gtag;

    gtag('js', new Date());
    gtag('config', 'G-D4Q2EZ7SGK');

    window.addEventListener('load', function () {
        const loadGtag = function () {
            const script = document.createElement('script');
            script.async = true;
            script.src = 'https://www.googletagmanager.com/gtag/js?id=G-D4Q2EZ7SGK';
            document.head.appendChild(script);
        };

        if ('requestIdleCallback' in window) {
            requestIdleCallback(loadGtag, { timeout: 2000 });
        } else {
            setTimeout(loadGtag, 1200);
        }
    });
</script>



  <!-- Meta Pixel Code -->
<script>
!function(f,n)
{
    if(f.fbq)return;
    n=f.fbq=function(){
        n.callMethod ?
        n.callMethod.apply(n,arguments) :
        n.queue.push(arguments)
    };

    if(!f._fbq)f._fbq=n;

    n.push=n;
    n.loaded=!0;
    n.version='2.0';
    n.queue=[];

}(window);

fbq('init', '1716189809797389');
fbq('track', 'PageView');

window.addEventListener('load', function () {
    const loadMetaPixel = function () {
        const script = document.createElement('script');
        script.async = true;
        script.src = 'https://connect.facebook.net/en_US/fbevents.js';
        document.head.appendChild(script);
    };

    if ('requestIdleCallback' in window) {
        requestIdleCallback(loadMetaPixel, { timeout: 2500 });
    } else {
        setTimeout(loadMetaPixel, 1500);
    }
});
</script>

<noscript>
<img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1716189809797389&ev=PageView&noscript=1"/>
</noscript>
<!-- End Meta Pixel Code -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">



    {{-- Canonical + OG defaults --}}
    <link rel="canonical" href="@yield('canonical', url()->current())">

    @if (View::hasSection('og'))
        @yield('og')
    @else 

        <meta http-equiv="Content-Language" content="{{ app()->getLocale() }}">
<meta name="language" content="{{ app()->getLocale() }}">

        <meta name="description"
            content="{{ $plainPageDescription }}">
        <meta property="og:title"
            content="{{ $isHomePage ? 'ბუკინისტები' : (isset($book) ? $book->title : (isset($booknews) ? $booknews->title : 'ბუკინისტები')) }}">
        <meta property="og:description"
            content="{{ $plainPageDescription }}">
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
        
       @if(app()->getLocale() === 'en')
<link rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Noto+Sans&display=swap">
@endif

    @endif
    <title>@yield('title', 'Bukinistebi.ge')</title>

    <!-- Twitter Card Meta Tags (Optional) -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $book->title ?? 'bukinistebi - ბუკინისტური მაღაზია' }}">
    <meta name="twitter:description"
        content="{{ $plainPageDescription }}">
    <meta name="twitter:image"
        content="{{ isset($book) && $book->photo
            ? asset('storage/' . $book->photo)
            : (isset($booknews) && $booknews->image
                ? asset('storage/' . $booknews->image)
                : asset('default.webp')) }}">
    <meta name="robots" content="index, follow">




 
<script>
(function () {
    const theme = localStorage.getItem('theme');
    if (theme === 'dark') {
        document.body.classList.add('dark-mode');
    }
})();
</script>


 
<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
<link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
<link rel="stylesheet" href="https://bukinistebi.ge/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<link rel="preload" href="/fonts/bpg_boxo-boxo.ttf" as="font" type="font/ttf" crossorigin>
<link rel="preload" href="/fonts/alk-tommaso-webfont.ttf" as="font" type="font/ttf" crossorigin>
<link rel="preload" href="/fonts/bpg-glaho-web-caps-webfont.ttf" as="font" type="font/ttf" crossorigin>

    <!-- Custom CSS -->
    <link rel="icon" href="https://bukinistebi.ge/uploads/favicon/favicon.png" type="image/x-icon">

<link href="/css/style.min.css" rel="stylesheet">
@stack('head')


    @if (app()->getLocale() === 'en')
        <style>
            
            .navbar-nav .nav-link,
            .dropdown-menu .dropdown-item,
            .btn,
            body {
                font-family: 'Noto Serif', serif;
            }

            .suggest-box {
    padding: 6px 0;                /* top/bottom breathing */
}
            .suggest-didyoumean {
    padding-left: 12px important;            
    font-size: 12px;
    background: #fff8e1;
    border-bottom: 1px solid #eee;
}

.suggest-didyoumean a {
    font-weight: 700;
    text-decoration: underline;
}


.book-card {
    position: relative;
}

.book-hover-title {
    min-height: 28px;
    line-height: 1.25;
    cursor: pointer;
}

@media (max-width: 768px) {
    .book-hover-title {
        min-height: auto !important;
        white-space: normal !important;
        overflow: visible !important;
    }
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
          "description": @json($plainPageDescription),
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

    <style>
        .auction-top-item {
            margin: 0 12px 0 4px;
        }

        .auction-top-link {
            display: inline-flex !important;
            align-items: center;
            gap: 8px;
            min-height: 38px;
            padding: 5px 12px !important;
            border: 1px solid rgba(212, 175, 55, .58);
            border-radius: 999px;
            background: linear-gradient(135deg, rgba(255, 249, 222, .98), rgba(255, 255, 255, .86));
            box-shadow: 0 5px 16px rgba(128, 98, 19, .13);
            color: #473919 !important;
            font-weight: 800;
            line-height: 1;
            white-space: nowrap;
        }

        .auction-top-link:hover {
            border-color: rgba(212, 175, 55, .95);
            background: linear-gradient(135deg, #fff3b8, #fffdf2);
            color: #2f260f !important;
            transform: translateY(-1px);
        }

        .auction-top-link .auction-gold-icon {
            width: 26px;
            height: 26px;
            margin-right: 0;
        }

        .auction-top-link .auction-gold-icon i {
            font-size: 24px;
        }

        .auction-top-copy {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .auction-top-label {
            color: inherit !important;
        }

        .auction-top-badge {
            font-size: 10px;
            line-height: 1;
            padding: 4px 6px;
            border-radius: 999px;
        }

        @media (min-width: 768px) {
            #topStickyNavbar,
            #topStickyNavbar.scrolled {
                background: #f4f5f7 !important;
                border-bottom: 1px solid #e4e6ea !important;
            }

            #topStickyNavbar > .container {
                min-height: 54px;
            }

            #topStickyNavbar .navbar-nav {
                align-items: center;
                gap: 6px !important;
                min-width: 0;
            }

            #topStickyNavbar .forum-highlight,
            #topStickyNavbar .kalata,
            #topStickyNavbar .kalata3,
            #topStickyNavbar .kalata2 {
                margin: 0 !important;
                padding: 0 !important;
                border-radius: 999px;
                transition: transform .18s ease, box-shadow .18s ease, background-color .18s ease;
            }

            #topStickyNavbar .forum-highlight {
                padding-right: 0 !important;
            }

            #topStickyNavbar > .container > .navbar-nav > .nav-item > .nav-link:not(.auction-top-link) {
                min-height: 40px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                padding: 8px 12px !important;
                border: 1px solid transparent;
                border-radius: 999px;
                color: #555a61 !important;
                font-weight: 500;
                line-height: 1;
                transition: color .18s ease, border-color .18s ease, background-color .18s ease, box-shadow .18s ease, transform .18s ease;
            }

            #topStickyNavbar > .container > .navbar-nav > .nav-item > .nav-link:not(.auction-top-link):hover,
            #topStickyNavbar > .container > .navbar-nav > .dropdown.show > .nav-link:not(.auction-top-link) {
                background: #fff;
                border-color: #e1e4e8;
                color: #25282d !important;
                box-shadow: 0 8px 22px rgba(31, 35, 40, .08);
                transform: translateY(-1px);
            }

            #topStickyNavbar > .container > .navbar-nav > .nav-item > .nav-link i {
                font-size: 17px;
                color: #62676f;
            }

            #topStickyNavbar > .container > .navbar-nav > .dropdown > .dropdown-toggle::after {
                margin-left: 6px;
                opacity: .72;
            }

            #topStickyNavbar .cart-nav-link {
                min-width: 112px;
                padding: 8px 14px !important;
                border-color: #e2e5ea !important;
                background: linear-gradient(180deg, #ffffff 0%, #f8f9fb 100%);
                box-shadow: 0 8px 20px rgba(31, 35, 40, .07);
                position: relative;
            }

            #topStickyNavbar .cart-nav-link:hover {
                border-color: #d1d6df !important;
                background: #fff;
                box-shadow: 0 10px 24px rgba(31, 35, 40, .1);
            }

            #topStickyNavbar .cart-nav-link i {
                top: 0 !important;
                color: #4f5965 !important;
            }

            #topStickyNavbar .cart-icon-wrap {
                position: relative;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 20px;
                height: 20px;
                line-height: 1;
            }

            #topStickyNavbar .cart-nav-text {
                color: #4f5965 !important;
                font-weight: 500;
            }

            #topStickyNavbar .cart-nav-link .custom-bubble {
                position: absolute !important;
                top: -5px !important;
                right: -6px !important;
                left: auto;
                width: 15px !important;
                min-width: 15px !important;
                max-width: 15px !important;
                height: 15px !important;
                min-height: 15px !important;
                max-height: 15px !important;
                aspect-ratio: 1 / 1;
                display: inline-grid;
                place-items: center;
                align-items: center;
                justify-content: center;
                padding: 0 !important;
                border: 0 !important;
                border-radius: 50% !important;
                background: #f25057 !important;
                color: #fff !important;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 9px !important;
                font-weight: 800;
                line-height: 1 !important;
                text-align: center;
                text-shadow: none;
                box-shadow: 0 2px 6px rgba(242, 80, 87, .35);
                z-index: 2;
            }

            #topStickyNavbar .cart-nav-link #cart-count {
                display: block;
                line-height: 1;
                transform: translateY(1px);
            }

            #topStickyNavbar .cart-nav-link .custom-bubble::after {
                display: none;
            }

            #topStickyNavbar .auth-dropdown-menu {
                width: 350px;
                z-index: 1000000;
            }

            #topStickyNavbar .auth-dropdown-menu .nav-tabs {
                display: flex;
                flex-wrap: nowrap;
                gap: 6px;
            }

            #topStickyNavbar .auth-dropdown-menu .nav-tabs .nav-item {
                flex: 1 1 0;
                min-width: 0;
            }

            #topStickyNavbar .auth-dropdown-menu .nav-tabs .nav-link {
                width: 100%;
                min-height: 42px;
                white-space: nowrap;
            }

            #topStickyNavbar .auth-dropdown-menu .auth-tabs-user-only {
                display: block;
            }

            #topStickyNavbar .auth-nav-item {
                min-width: 0;
                max-width: min(34vw, 360px);
            }

            #topStickyNavbar .auth-nav-link {
                min-width: 0;
                width: 100%;
                max-width: 100%;
            }

            #topStickyNavbar .auth-nav-name {
                display: inline-block;
                flex: 1 1 auto;
                min-width: 0;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .auction-top-item {
                margin: 0 10px 0 4px !important;
            }

            .auction-top-link {
                min-height: 42px;
                padding: 6px 14px !important;
                border-color: rgba(210, 171, 44, .72);
                background: linear-gradient(135deg, #fffdfa 0%, #fff3bd 100%);
                box-shadow: 0 10px 24px rgba(128, 98, 19, .16);
                color: #4a3a11 !important;
            }

            .auction-top-link:hover {
                border-color: rgba(188, 143, 18, .95);
                background: linear-gradient(135deg, #fffaf0 0%, #ffe991 100%);
                box-shadow: 0 12px 28px rgba(128, 98, 19, .22);
            }

            .auction-top-link .auction-gold-icon {
                display: inline-grid;
                place-items: center;
                width: 28px;
                height: 28px;
                border: 1px solid rgba(207, 168, 42, .65);
                border-radius: 50%;
                background: rgba(255, 255, 255, .62);
            }

            .auction-top-link .auction-gold-icon i {
                font-size: 18px;
                color: #b98c14;
            }

            .auction-top-badge {
                padding: 4px 7px;
                font-weight: 800;
                letter-spacing: .02em;
            }

            #topStickyNavbar .kalata2 {
                background: transparent !important;
                margin-left: 2px !important;
            }

            #modeToggle {
                width: 44px;
                height: 40px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0 !important;
                border: 1px solid #3f4146;
                border-radius: 12px;
                background: #494a4f;
                color: #f3f3f4 !important;
                box-shadow: 0 8px 20px rgba(31, 35, 40, .13);
                transition: background-color .18s ease, box-shadow .18s ease, transform .18s ease;
            }

            #modeToggle:hover {
                background: #32343a;
                box-shadow: 0 10px 24px rgba(31, 35, 40, .18);
                transform: translateY(-1px);
            }
        }

        @media (max-width: 767.98px) {
            #topStickyNavbar {
                min-height: 56px;
                padding-top: 6px !important;
                padding-bottom: 6px !important;
            }

            #topStickyNavbar > .container {
                justify-content: center !important;
                padding-left: 8px;
                padding-right: 8px;
            }

            #topStickyNavbar .navbar-nav {
                width: 100%;
                flex-wrap: nowrap !important;
                justify-content: center;
                gap: 7px;
                min-width: 0;
            }

            #topStickyNavbar .nav-link {
                width: 36px;
                height: 34px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0 !important;
                border: 1px solid transparent;
                border-radius: 12px;
                color: #565b63 !important;
                transition: background-color .18s ease, border-color .18s ease, box-shadow .18s ease, transform .18s ease;
            }

            #topStickyNavbar .auth-dropdown-menu {
                width: min(350px, calc(100vw - 20px));
                max-width: calc(100vw - 20px);
                position: fixed !important;
                top: 46px !important;
                left: 10px !important;
                right: 10px !important;
                margin: 0 auto !important;
                transform: none !important;
                z-index: 1000000;
                border-radius: 8px;
                overflow: hidden;
            }

            #topStickyNavbar .auth-dropdown-menu .nav-tabs {
                display: grid;
                grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
                gap: 0;
            }

            #topStickyNavbar .auth-dropdown-menu .auth-tabs-user-only {
                display: block;
            }

            #topStickyNavbar .auth-dropdown-menu .nav-tabs .nav-item,
            #topStickyNavbar .auth-dropdown-menu .nav-tabs .nav-link {
                min-width: 0;
                width: 100%;
            }

            #topStickyNavbar .auth-dropdown-menu .nav-tabs .nav-link {
                height: auto;
                min-height: 42px;
                padding: 9px 8px !important;
                border-radius: 6px 6px 0 0;
                font-size: 14px;
                font-weight: 700;
                line-height: 1.25;
                white-space: normal;
                overflow-wrap: anywhere;
                text-align: center;
            }

            #topStickyNavbar .auth-dropdown-menu .tab-content .nav-link {
                width: 100%;
                height: auto;
                min-height: 38px;
                justify-content: flex-start;
                gap: 8px;
                padding: 8px 0 !important;
                white-space: normal;
                overflow-wrap: anywhere;
            }

            #topStickyNavbar .auth-dropdown-menu .btn {
                min-height: 46px;
                white-space: normal;
                text-align: center;
            }

            #topStickyNavbar .nav-link:hover,
            #topStickyNavbar .dropdown.show > .nav-link {
                background: #fff;
                border-color: #e0e3e8;
                box-shadow: 0 6px 16px rgba(31, 35, 40, .08);
                transform: translateY(-1px);
            }

            #topStickyNavbar .nav-link i {
                font-size: 17px;
                line-height: 1;
            }

            #topStickyNavbar .kalata.dropdown > .nav-link {
                width: auto;
                min-width: 54px;
                padding: 0 7px !important;
                gap: 4px;
                white-space: nowrap;
            }

            #topStickyNavbar .cart-nav-item {
                padding-left: 2px !important;
                padding-right: 2px !important;
            }

            #topStickyNavbar .cart-nav-link {
                width: 36px;
                min-width: 36px !important;
                height: 34px;
                padding: 0 !important;
                position: relative;
                border-color: transparent !important;
                background: transparent !important;
                box-shadow: none !important;
            }

            #topStickyNavbar .cart-nav-link:hover {
                background: #fff !important;
                border-color: #e0e3e8 !important;
                box-shadow: 0 6px 16px rgba(31, 35, 40, .08) !important;
            }

            #topStickyNavbar .cart-nav-link i {
                top: 0 !important;
                color: #565b63 !important;
            }

            #topStickyNavbar .cart-icon-wrap {
                position: relative;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 20px;
                height: 20px;
                line-height: 1;
            }

            #topStickyNavbar .cart-nav-link .custom-bubble {
                position: absolute !important;
                top: -5px !important;
                right: -6px !important;
                left: auto;
                width: 15px !important;
                min-width: 15px !important;
                max-width: 15px !important;
                height: 15px !important;
                min-height: 15px !important;
                max-height: 15px !important;
                aspect-ratio: 1 / 1;
                display: inline-grid;
                place-items: center;
                align-items: center;
                justify-content: center;
                padding: 0 !important;
                border: 0 !important;
                border-radius: 50% !important;
                background: #f25057 !important;
                color: #fff !important;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 9px !important;
                font-weight: 800;
                line-height: 1 !important;
                text-align: center;
                text-shadow: none;
                box-shadow: 0 2px 6px rgba(242, 80, 87, .35);
                z-index: 2;
            }

            #topStickyNavbar .cart-nav-link #cart-count {
                display: block;
                line-height: 1;
                transform: translateY(1px);
            }

            #topStickyNavbar .cart-nav-link .custom-bubble::after {
                display: none;
            }

            #topStickyNavbar .kalata3 > .nav-link {
                width: 46px;
            }

            #topStickyNavbar .dropdown-toggle::after {
                margin-left: 4px;
                opacity: .7;
                transform: scale(.82);
            }

            #topStickyNavbar .nav-item {
                flex: 0 0 auto;
            }

            #topStickyNavbar .auth-nav-item {
                flex: 1 1 auto;
                min-width: 0;
            }

            #topStickyNavbar .auth-nav-link {
                min-width: 0;
                width: 100%;
                max-width: 100%;
            }

            #topStickyNavbar .auth-nav-name {
                display: inline-block;
                flex: 1 1 auto;
                min-width: 0;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            #topStickyNavbar .kalata,
            #topStickyNavbar .kalata:hover,
            #topStickyNavbar .kalata3,
            #topStickyNavbar .kalata2,
            #topStickyNavbar .kalata2:hover {
                background: transparent !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
                padding-left: 4px !important;
                padding-right: 4px !important;
            }

            #topStickyNavbar .forum-highlight {
                padding-right: 0 !important;
            }

            .auction-top-item {
                margin: 0 2px;
            }

            .auction-top-link {
                width: 36px;
                height: 34px;
                min-height: 34px;
                justify-content: center;
                padding: 0 !important;
                gap: 0;
                font-size: 12px;
                box-shadow: 0 4px 12px rgba(128, 98, 19, .1);
            }

            .auction-top-link .auction-gold-icon {
                width: auto;
                height: auto;
                border: 0;
                background: transparent;
            }

            .auction-top-link .auction-gold-icon i {
                font-size: 17px;
            }

            .auction-top-copy {
                display: none;
            }

            .auction-top-badge {
                display: none;
            }

            #modeToggle {
                width: 36px;
                height: 34px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0 !important;
                border: 1px solid #e0e3e8;
                border-radius: 12px;
                background: #fff;
                color: #565b63 !important;
                box-shadow: 0 6px 16px rgba(31, 35, 40, .08);
                transition: background-color .18s ease, border-color .18s ease, box-shadow .18s ease, transform .18s ease;
            }

            #modeToggle:hover {
                background: #fff;
                border-color: #d8dce3;
                box-shadow: 0 7px 18px rgba(31, 35, 40, .1);
                transform: translateY(-1px);
            }

            #modeToggle i {
                font-size: 17px;
                line-height: 1;
            }

            #mainNavbar {
                border-top: 1px solid #e9ebef;
                border-bottom: 1px solid #e4e7ec;
                box-shadow: 0 8px 22px rgba(31, 35, 40, .06) !important;
            }

            #mainNavbar > .container {
                min-height: 58px;
                align-items: center !important;
                align-content: flex-start;
                flex-wrap: wrap;
                padding-top: 8px;
                padding-bottom: 8px;
            }

            #mainNavbar .navbar-brand {
                order: 1;
                align-self: center;
            }

            #mobileSearchToggle {
                order: 2;
                align-self: center;
            }

            #mobileMenuToggle {
                order: 3;
                align-self: center;
                margin-top: 0 !important;
                margin-bottom: 0 !important;
            }

            #navbarNav {
                order: 4;
                flex-basis: 100%;
                width: 100%;
                margin-top: 12px;
            }

            #mobileSearchToggle,
            #mobileMenuToggle {
                width: 48px;
                height: 44px;
                display: inline-flex !important;
                align-items: center;
                justify-content: center;
                padding: 0 !important;
                border: 1px solid #d9dde4 !important;
                border-radius: 13px !important;
                background: #f8f9fb;
                color: #565b63 !important;
                box-shadow: 0 7px 18px rgba(31, 35, 40, .07), inset 0 1px 0 rgba(255,255,255,.7);
                transition: background-color .18s ease, border-color .18s ease, box-shadow .18s ease, transform .18s ease;
            }

            #mobileSearchToggle:hover,
            #mobileMenuToggle:hover {
                background: #fff;
                border-color: #cfd5dd !important;
                box-shadow: 0 9px 22px rgba(31, 35, 40, .11);
                transform: none;
            }

            #mobileSearchToggle i,
            #mobileMenuToggle i {
                position: static !important;
                color: currentColor !important;
                font-size: 23px !important;
                line-height: 1;
            }

            #mobileMenuToggle .hamburger-icon {
                position: relative;
                width: 22px;
                height: 16px;
                display: inline-flex;
                flex-direction: column;
                justify-content: space-between;
                align-items: stretch;
            }

            #mobileMenuToggle .hamburger-icon span {
                display: block;
                width: 100%;
                height: 2px;
                border-radius: 999px;
                background: currentColor;
                transform-origin: center;
                transition: transform .22s ease, opacity .16s ease, width .18s ease;
            }

            #mobileMenuToggle .hamburger-icon span:nth-child(2) {
                width: 72%;
                margin-left: auto;
            }

            #mobileMenuToggle:hover .hamburger-icon span:nth-child(2) {
                width: 100%;
            }

            #mobileMenuToggle[aria-expanded="false"] .hamburger-icon span:nth-child(2),
            #mobileMenuToggle.collapsed .hamburger-icon span:nth-child(2) {
                width: 72%;
            }

            #mobileMenuToggle[aria-expanded="true"] {
                background: #34363c;
                border-color: #34363c !important;
                color: #fff !important;
            }

            #mobileMenuToggle[aria-expanded="true"] .hamburger-icon {
                justify-content: center;
            }

            #mobileMenuToggle[aria-expanded="true"] .hamburger-icon span:nth-child(1) {
                transform: translateY(2px) rotate(45deg);
            }

            #mobileMenuToggle[aria-expanded="true"] .hamburger-icon span:nth-child(2) {
                opacity: 0;
                transform: scaleX(0);
            }

            #mobileMenuToggle[aria-expanded="true"] .hamburger-icon span:nth-child(3) {
                transform: translateY(-2px) rotate(-45deg);
            }

            #mobileSearchOverlay {
                display: block !important;
                top: calc(100% + 8px) !important;
                left: 12px !important;
                right: 12px !important;
                padding: 10px !important;
                border: 1px solid #dfe3e8;
                border-radius: 14px;
                background: #fff !important;
                box-shadow: 0 12px 28px rgba(31, 35, 40, .16) !important;
                opacity: 0;
                pointer-events: none;
                transform: translateY(-8px);
                transition: opacity .2s ease, transform .2s ease, visibility .2s ease;
                visibility: hidden;
            }

            #mobileSearchOverlay.is-open {
                opacity: 1;
                pointer-events: auto;
                transform: translateY(0);
                visibility: visible;
            }

            #mobileSearchOverlay form {
                gap: 8px;
            }

            .mobile-category-back-wrap {
                position: sticky;
                top: 0;
                z-index: 2;
                background: #fff;
                border-bottom: 1px solid #edf0f4;
            }

            .mobile-category-back {
                width: 100%;
                min-height: 42px;
                display: inline-flex;
                align-items: center;
                justify-content: flex-start;
                gap: 10px;
                padding: 8px 12px;
                border: 1px solid #dfe3e8;
                border-radius: 12px;
                background: #f8f9fb;
                color: #34383f;
                font-weight: 800;
                box-shadow: 0 5px 14px rgba(31, 35, 40, .06);
            }

            .mobile-category-back i {
                font-size: 18px;
                line-height: 1;
            }
        }

        @media (min-width: 992px) {
            #mainNavbar {
                top: 66px !important;
                background: rgba(255, 255, 255, 1) !important;
                border-bottom: 1px solid #e6e8ec;
                box-shadow: 0 8px 24px rgba(31, 35, 40, .06) !important;
            }

            #mainNavbar > .container {
                min-height: 52px;
                padding-top: 8px;
                padding-bottom: 8px;
            }

            #mainNavbar .navbar-nav {
                transform: translateY(5px);
            }

            #mainNavbar .navbar-brand {
                transform: translateY(3px);
            }

            #mainNavbar .desktop-search-form {
                transform: translateY(-2px);
            }

            #mainNavbar .navbar-nav {
                align-items: center;
                gap: 3px;
            }

            #mainNavbar .navbar-nav > .nav-item > .nav-link {
                min-height: 38px;
                display: inline-flex;
                align-items: center;
                padding: 8px 10px !important;
                border-radius: 999px;
                color: #555a61 !important;
                font-weight: 500;
                line-height: 1;
                transition: background-color .18s ease, color .18s ease, box-shadow .18s ease;
            }

            #mainNavbar .navbar-nav > .nav-item > .nav-link:hover,
            #mainNavbar .navbar-nav > .dropdown.show > .nav-link {
                background: #f2f4f7;
                color: #24272c !important;
            }

            #mainNavbar .desktop-search-form {
                width: clamp(230px, 23vw, 340px);
                height: 38px;
                align-items: center;
                margin-left: 12px;
                border: 1px solid #e0e3e8;
                border-radius: 999px;
                background: #f8f9fb;
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, .82), 0 8px 20px rgba(31, 35, 40, .05);
                transition: border-color .18s ease, background-color .18s ease, box-shadow .18s ease;
            }

            #mainNavbar .desktop-search-form:focus-within {
                border-color: rgba(212, 175, 55, .68);
                background: #fff;
                box-shadow: 0 0 0 4px rgba(212, 175, 55, .12), 0 10px 24px rgba(31, 35, 40, .08);
            }

            #mainNavbar .desktop-search-form .styled-input {
                height: 36px;
                min-width: 0;
                margin-right: 0 !important;
                padding: 0 8px 0 16px;
                border: 0;
                border-radius: 999px 0 0 999px;
                background: transparent;
                color: #2f3338;
                font-size: 14px;
                font-weight: 600;
                box-shadow: none;
            }

            #mainNavbar .desktop-search-form .styled-input::placeholder {
                color: #777d86;
                font-size: 14px;
                font-weight: 600;
            }

            #mainNavbar .desktop-search-form .styled-input:focus {
                box-shadow: none;
                background: transparent;
            }

            #mainNavbar .desktop-search-form .submit-search {
                width: 40px;
                height: 30px;
                margin-right: 4px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                flex: 0 0 40px;
                border: 0 !important;
                border-radius: 999px !important;
                background: #4b4d53;
                color: #fff;
                box-shadow: 0 6px 14px rgba(31, 35, 40, .16);
                transition: background-color .18s ease, box-shadow .18s ease, transform .18s ease;
            }

            #mainNavbar .desktop-search-form .submit-search:hover {
                background: #2f3338;
                color: #fff;
                box-shadow: 0 8px 18px rgba(31, 35, 40, .22);
                transform: translateY(-1px);
            }

            #mainNavbar .desktop-search-form .submit-search i {
                font-size: 15px;
                line-height: 1;
            }
        }

        body.dark-mode #topStickyNavbar,
        body.dark-mode #topStickyNavbar.scrolled {
            background: #2f3034 !important;
            border-bottom-color: #3d3f45 !important;
            box-shadow: 0 8px 22px rgba(0, 0, 0, .22) !important;
        }

        body.dark-mode #topStickyNavbar > .container > .navbar-nav > .nav-item > .nav-link:not(.auction-top-link) {
            color: #d7d9dd !important;
        }

        body.dark-mode #topStickyNavbar > .container > .navbar-nav > .nav-item > .nav-link:not(.auction-top-link):hover,
        body.dark-mode #topStickyNavbar > .container > .navbar-nav > .dropdown.show > .nav-link:not(.auction-top-link) {
            background: #3a3c42 !important;
            border-color: #4b4d54 !important;
            color: #fff !important;
            box-shadow: 0 8px 22px rgba(0, 0, 0, .22) !important;
        }

        body.dark-mode #topStickyNavbar > .container > .navbar-nav > .nav-item > .nav-link i {
            color: #c9ccd2 !important;
        }

        body.dark-mode .auction-top-link {
            border-color: rgba(198, 158, 35, .62) !important;
            background: linear-gradient(135deg, #4a3f22 0%, #2f3034 100%) !important;
            box-shadow: 0 10px 24px rgba(0, 0, 0, .24) !important;
            color: #f5df93 !important;
        }

        body.dark-mode .auction-top-link:hover {
            border-color: rgba(226, 185, 58, .86) !important;
            background: linear-gradient(135deg, #5a4a22 0%, #37383d 100%) !important;
        }

        body.dark-mode .auction-top-link .auction-gold-icon {
            border-color: rgba(226, 185, 58, .56) !important;
            background: rgba(255, 255, 255, .08) !important;
        }

        body.dark-mode .auction-top-link .auction-gold-icon i,
        body.dark-mode .auction-top-label {
            color: #f5df93 !important;
        }

        body.dark-mode #modeToggle {
            border-color: #5a5d66 !important;
            background: #1f2024 !important;
            color: #f4f5f7 !important;
            box-shadow: 0 8px 20px rgba(0, 0, 0, .28) !important;
        }

        body.dark-mode #modeToggle:hover {
            background: #111216 !important;
        }

        body.dark-mode #topStickyNavbar .cart-nav-link {
            border-color: #444750 !important;
            background: #303238 !important;
            color: #d9dbe0 !important;
            box-shadow: 0 8px 20px rgba(0, 0, 0, .22) !important;
        }

        body.dark-mode #topStickyNavbar .cart-nav-link:hover {
            background: #3a3c42 !important;
            border-color: #555963 !important;
            color: #fff !important;
        }

        body.dark-mode #topStickyNavbar .cart-nav-link i,
        body.dark-mode #topStickyNavbar .cart-nav-text {
            color: currentColor !important;
        }

        @media (min-width: 992px) {
            body.dark-mode #mainNavbar {
                background: rgba(38, 39, 43, 1) !important;
                border-bottom-color: #3c3e45 !important;
                box-shadow: 0 10px 26px rgba(0, 0, 0, .24) !important;
            }

            body.dark-mode #mainNavbar .navbar-nav > .nav-item > .nav-link {
                color: #d9dbe0 !important;
            }

            body.dark-mode #mainNavbar .navbar-nav > .nav-item > .nav-link:hover,
            body.dark-mode #mainNavbar .navbar-nav > .dropdown.show > .nav-link {
                background: #34363c !important;
                color: #fff !important;
            }

            body.dark-mode #mainNavbar .desktop-search-form {
                border-color: #444750 !important;
                background: #303238 !important;
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, .04), 0 8px 20px rgba(0, 0, 0, .22) !important;
            }

            body.dark-mode #mainNavbar .desktop-search-form:focus-within {
                border-color: rgba(226, 185, 58, .62) !important;
                background: #27292f !important;
                box-shadow: 0 0 0 4px rgba(226, 185, 58, .12), 0 10px 24px rgba(0, 0, 0, .28) !important;
            }

            body.dark-mode #mainNavbar .desktop-search-form .styled-input {
                background: transparent !important;
                color: #f0f1f3 !important;
                border-color: transparent !important;
            }

            body.dark-mode #mainNavbar .desktop-search-form .styled-input::placeholder {
                color: #aeb3bc !important;
            }

            body.dark-mode #mainNavbar .desktop-search-form .submit-search {
                background: #1f2024 !important;
                color: #fff !important;
                box-shadow: 0 6px 14px rgba(0, 0, 0, .28) !important;
            }

            body.dark-mode #mainNavbar .desktop-search-form .submit-search:hover {
                background: #111216 !important;
            }
        }

        @media (max-width: 767.98px) {
            body.dark-mode #topStickyNavbar .nav-link:hover,
            body.dark-mode #topStickyNavbar .dropdown.show > .nav-link {
                background: #3a3c42 !important;
                border-color: #4b4d54 !important;
                box-shadow: 0 7px 18px rgba(0, 0, 0, .22) !important;
            }

            body.dark-mode #mainNavbar {
                border-top-color: #3a3c42 !important;
                border-bottom-color: #3a3c42 !important;
                box-shadow: 0 8px 22px rgba(0, 0, 0, .22) !important;
            }

            body.dark-mode #mobileSearchToggle,
            body.dark-mode #mobileMenuToggle {
                background: #303238 !important;
                border-color: #4a4d55 !important;
                color: #d9dbe0 !important;
                box-shadow: 0 7px 18px rgba(0, 0, 0, .24), inset 0 1px 0 rgba(255,255,255,.04) !important;
            }

            body.dark-mode #mobileSearchToggle:hover,
            body.dark-mode #mobileMenuToggle:hover,
            body.dark-mode #mobileMenuToggle[aria-expanded="true"] {
                background: #1f2024 !important;
                border-color: #5a5d66 !important;
                color: #fff !important;
            }

            body.dark-mode #modeToggle {
                width: 36px;
                height: 34px;
                border-color: transparent !important;
                background: transparent !important;
                color: #d9dbe0 !important;
                box-shadow: none !important;
            }

            body.dark-mode #modeToggle:hover {
                background: #3a3c42 !important;
                border-color: #4b4d54 !important;
                color: #fff !important;
                box-shadow: 0 7px 18px rgba(0, 0, 0, .22) !important;
            }

            body.dark-mode .auction-top-link {
                border-color: transparent !important;
                background: transparent !important;
                color: #d9dbe0 !important;
                box-shadow: none !important;
            }

            body.dark-mode .auction-top-link:hover {
                background: #3a3c42 !important;
                border-color: #4b4d54 !important;
                color: #fff !important;
                box-shadow: 0 7px 18px rgba(0, 0, 0, .22) !important;
            }

            body.dark-mode .auction-top-link .auction-gold-icon {
                border-color: transparent !important;
                background: transparent !important;
            }

            body.dark-mode .auction-top-link .auction-gold-icon i {
                color: #d9dbe0 !important;
            }

            body.dark-mode #topStickyNavbar .cart-nav-link {
                border-color: transparent !important;
                background: transparent !important;
                box-shadow: none !important;
                color: #d9dbe0 !important;
            }

            body.dark-mode #topStickyNavbar .cart-nav-link:hover {
                background: #3a3c42 !important;
                border-color: #4b4d54 !important;
                color: #fff !important;
                box-shadow: 0 7px 18px rgba(0, 0, 0, .22) !important;
            }

            body.dark-mode #topStickyNavbar .cart-nav-link .custom-bubble {
                border: 0;
            }

            body.dark-mode #mobileSearchOverlay {
                background: #303238 !important;
                border-color: #4a4d55 !important;
                box-shadow: 0 12px 28px rgba(0, 0, 0, .34) !important;
            }

            body.dark-mode .mobile-category-back-wrap {
                background: #464343 !important;
                border-bottom-color: #55575d !important;
            }

            body.dark-mode .mobile-category-back {
                background: #303238 !important;
                border-color: #4a4d55 !important;
                color: #f1f2f4 !important;
                box-shadow: 0 6px 16px rgba(0, 0, 0, .24) !important;
            }
        }

        .site-content {
            padding-top: 24px;
        }

        body {
            padding-top: 62px !important;
        }

        @media (max-width: 767.98px) {
            body {
                padding-top: 96px !important;
            }

            .site-content {
                padding-top: 14px;
            }
        }
    </style>

 
</head>

<body>
 @php $lang = request('lang'); @endphp


    <!-- ✅ Top Navbar: right -->
    <nav id="topStickyNavbar" class="navbar navbar-light bg-dark border-bottom py-2">
        <div class="container d-flex justify-content-between align-items-center">

@php
    $isPublishing = request()->getHost() === 'publishing.bukinistebi.ge';
@endphp


            @if($isPublishing)

<div class="d-flex align-items-center gap-3 flex-wrap d-none d-md-flex">
                <div class="col text-center">

                    <a href="https://www.facebook.com/publishing.bukinistebi.ge" class="fb-icon-top" target="blank" aria-label="Visit Bukinistebi on Facebook"><i
                            class="bi bi-facebook fs-5"></i></a>
                    <a href="https://www.instagram.com/bukinistebi_publishing/" class="insta-icon-top" target="blank"  aria-label="Visit Bukinistebi on Instagram"><i
                            class="bi bi-instagram fs-5"></i></a>

                </div>
            </div>
            @else

            <!-- Right Side: Cart, Login, Language, Search -->
            <div class="d-flex align-items-center gap-3 flex-wrap d-none d-md-flex">
                <div class="col text-center">

                    <a href="https://www.facebook.com/bukinistebi.georgia" class="fb-icon-top" target="blank" aria-label="Visit Bukinistebi on Facebook"><i
                            class="bi bi-facebook fs-5"></i></a>
                    <a href="https://www.instagram.com/bukinistebi.ge/" class="insta-icon-top" target="blank"  aria-label="Visit Bukinistebi on Instagram"><i
                            class="bi bi-instagram fs-5"></i></a>

                </div>
            </div>

            @endif

            <!-- Right Side: Cart, Login, Language -->


            @if($isPublishing)

                        <ul class="navbar-nav flex-row align-items-center gap-0 flex-wrap ms-auto">
                            <li> <i class="bi bi-envelope-fill"></i> publishing@bukinistebi.ge </li>
</ul>


@else

            <ul class="navbar-nav flex-row align-items-center gap-0 flex-wrap ms-auto">
<!-- Forum -->
 
<li class="nav-item forum-highlight" style="padding-right: 15px;">
    <a class="nav-link" href="https://publishing.bukinistebi.ge/" target="_blank">
        <i class="bi bi-book"></i>

        <span class="d-none d-md-inline">
            გამომცემლობა
         </span>
    </a>
</li>

                <!-- Cart -->
                @if (!auth()->check() || auth()->user()->role !== 'publisher')
                    <li class="nav-item kalata cart-nav-item">
                        @php
                            $cartCount = 0;
                            if (Auth::check() && Auth::user()->cart) {
                                $cartCount = Auth::user()->cart->cartItems->count();
                            }
                        @endphp
                        <!-- Cart Link in the Navbar -->
                        <a class="nav-link cart-nav-link" href="@langurl(route('cart.index'))" aria-label="View cart">
                            <span class="cart-icon-wrap">
                                <i class="bi bi-cart-fill"></i>

                                <span id="cart-bubble" class="custom-bubble"
                                    style="display: {{ $cartCount > 0 ? 'inline-grid' : 'none' }};">
                                    <span id="cart-count">{{ $cartCount }}</span>
                                </span>
                            </span>

                            <span class="cart-nav-text d-none d-md-inline">
                                {{ __('messages.cart') }}
                            </span>
                        </a>


                    </li>
                @endif


                <!-- Right Side of Navbar -->
                @guest
                    <li class="nav-item dropdown kalata" style="z-index: 1000000; ">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false" v-pre>
                            <i class="bi bi-file-earmark-person" style="position: relative; font-size: 14px"></i>
                            {{ __('messages.login') }}
                        </a>



                        <!-- Dropdown with Tabs -->
                        <ul class="dropdown-menu dropdown-menu-end p-3 auth-dropdown-menu" aria-labelledby="navbarDropdown"
                            id="dropdown-menu">
                            <!-- Tab Navigation -->
                            <ul class="nav nav-tabs auth-tabs-user-only" id="authTabs" role="tablist">
                                <li class="nav-item tabhover" role="presentation">
                                    <button class="nav-link active" id="login-tab" data-bs-toggle="tab"
                                        data-bs-target="#login" type="button" role="tab" aria-controls="login"
                                        aria-selected="true">
                                        {{ __('messages.user') }}
                                    </button>
                                </li>
                                {{--
                                <li class="nav-item tabhover" role="presentation">
                                    <button class="nav-link" id="register-tab" data-bs-toggle="tab"
                                        data-bs-target="#register" type="button" role="tab"
                                        aria-controls="register" aria-selected="false">
                                        {{ __('messages.bookseller') }}
                                    </button>
                                </li>
                                --}}
                            </ul>


                            <!-- Tab Content -->
                            <div class="tab-content" id="authTabsContent">
                                <!-- Users Login Tab -->

                                <div class="tab-pane fade show active" id="login" role="tabpanel"
                                    aria-labelledby="login-tab">

                                    <a class="nav-link mt-2" href="@langurl(route('login'))">
                                        <i class="bi bi-key"></i> {{ __('messages.authorization') }}
                                    </a>
                                    @if (Route::has('register'))
                                        <a class="nav-link mt-3" href="@langurl(route('register'))">
                                            <i class="bi bi-person-fill-add"></i> {{ __('messages.registration') }}</a>
                                    @endif

                                    <!-- google auth-->
                                    <a href="@langurl(route('login.google'))"
                                        class="btn w-100 d-flex align-items-center justify-content-center shadow-sm"
                                        style="background-color: #fff; border: 1px solid #ddd; padding: 10px; border-radius: 6px; font-weight: 500; margin-top:15px;">
                                        <img src="https://developers.google.com/identity/images/g-logo.png"
                                            style="width: 20px; height: 20px; margin-right: 10px;" alt="Google Logo">
                                        <span style="color: #555;">{{ __('messages.googleLogin') }}</span>
                                    </a>


                                </div>

                                {{--
                                <!-- Bukinist login Tab -->
                                <div class="tab-pane fade" id="register" role="tabpanel"
                                    aria-labelledby="register-tab">


                                    <a class="nav-link mt-3" href="@langurl(route('login.publisher'))">
                                        <i class="bi bi-box-arrow-in-right"></i> {{ __('messages.booksellerauth') }}
                                    </a>
                                    @if (Route::has('register'))
                                        <a class="nav-link mt-3" href="@langurl(route('register.publisher'))">
                                            <i class="bi bi-person-plus"></i> {{ __('messages.booksellerreg') }}</a>
                                    @endif
                                </div>
                                --}}
                            </div>
                        </ul>
                    </li>
                @else
                    <li class="nav-item dropdown kalata auth-nav-item" style="z-index: 1000000">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle auth-nav-link" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false" v-pre title="{{ Auth::user()->name }}">
                            <i class="bi bi-file-earmark-person" style="position: relative; font-size: 14px"></i>
                            <span class="auth-nav-name">{{ Auth::user()->name }}</span>

                        </a>

                        <!-- Dropdown Menu for Logged-In Users -->
                        <ul class="dropdown-menu dropdown-menu-end" style="z-index: 1000000">
                            @if (Auth::user()->role === 'publisher')
                                <li style="margin-top:15px;"><a class="dropdown-item"
                                        href="@langurl(route('publisher.dashboard'))">
                                        <i class="bi bi-door-open"></i>&nbsp;{{ __('messages.booksellersRoom') }}
                                    </a></li>
                                <li><a class="dropdown-item" href="@langurl(route('publisher.my_books'))">
                                        <i class="bi bi-book"></i> &nbsp;{{ __('messages.myUploadedBooks') }}
                                    </a></li>
                                <li><a class="dropdown-item" href="@langurl(route('publisher.account.edit'))">
                                        <i class="bi bi-pencil"></i> &nbsp;{{ __('messages.editProfile') }}
                                    </a></li>
                            @else
                                <li style="margin-top:15px;">
                                    <a class="dropdown-item" href="@langurl(route('purchase.history'))">
                                        <i class="bi bi-credit-card-2-front"></i>
                                        &nbsp;{{ __('messages.purchaseHistory') }}
                                    </a>
                                </li>
                                <li style="margin-top:15px; padding-bottom:10px;">
                                    <a class="dropdown-item" href="@langurl(route('account.edit'))">
                                        <i class="bi bi-pencil"></i> &nbsp;{{ __('messages.editProfile') }}
                                    </a>
                                </li>

                                <li style="padding-bottom:10px;">
                                    <a class="dropdown-item" href="@langurl(route('my.bids'))">
                                        <i class="bi bi-hammer"></i> &nbsp;{{ __('messages.myAuctions') }}
                                    </a>
                                </li>
                            @endif
                            <li>
                                <form method="POST" action="@langurl(route('logout'))">
                                    @csrf
                                    <a class="dropdown-item" href="@langurl(route('logout'))"
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
                <li class="nav-item dropdown kalata3">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                        data-bs-toggle="dropdown">
                        <img src="{{ asset('images/flags/' . app()->getLocale() . '.svg') }}" width="20"
                            class="me-1" alt="flags">

                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" style="z-index: 50000 !Important">
                        <li>
    <a class="dropdown-item"
       href="{{ request()->fullUrlWithQuery(['lang' => 'ka']) }}">
        ქართული
    </a>
</li>

<li>
    <a class="dropdown-item"
       href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}">
        English
    </a>
</li>

<li>
    <a class="dropdown-item"
       href="{{ request()->fullUrlWithQuery(['lang' => 'ru']) }}">
        Русский
    </a>
</li>


                        
                    </ul>
                </li>


                <li class="nav-item auction-top-item">
                    <a class="nav-link auction-top-link" href="@langurl(route('auction.index'))" aria-label="{{ __('messages.auctions') }}">
                        <span class="auction-gold-icon">
                            <i class="bi bi-coin"></i>
                        </span>
                        <span class="auction-top-copy">
                            <span class="auction-top-label">{{ __('messages.auctions') }}</span>
                            <span class="badge bg-danger auction-top-badge">NEW</span>
                        </span>
                    </a>
                </li>


                <!-- DARK MODE -->
                <li class="nav-item kalata2">
                   <button id="modeToggle" class="btn btn-inline-secondary btn-sm"
        aria-label="Toggle dark mode">
    <i class="bi bi-moon-fill"></i>
</button>

                </li>

            </ul>

            @endif
        </div>
    </nav>



    <div style="position: relative; z-index: 10050; ">
        <nav id="mainNavbar" class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-2"
            style="position: fixed; top: 56px; width: 100%; z-index: 999;">
            <div class="container" style="position: relative; ">

 

 
                <a class="navbar-brand" href="@langurl(('/'))" aria-label="Bukinistebi Home"><img
                        src="https://bukinistebi.ge/uploads/logo/bukinistebi.ge.png"  width="130" 
                        height="31" style="position:relative;" loading="eager" decoding="async" alt="bukinstebi_logo"></a>

 
                <!-- 🔍 Mobile Search Icon (only visible on mobile) -->
             <button class="btn d-block d-lg-none mx-2"
        id="mobileSearchToggle"
        type="button"
        aria-label="Open search"
        aria-expanded="false">
    <i class="bi bi-search fs-4"
       style="position: relative; top:3px; color:#7e7c7c; font-size: 18px !important"></i>
</button>

                <!-- 🔍 Popup Search Box (mobile only) -->
                <div id="mobileSearchOverlay" class="d-lg-none mobileSearch"
                    style="position: absolute; top: 0; left: 0; right: 0; background: #F3F4F6; z-index: 9999; padding: 3px 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <form action="@langurl(route('search'))" method="GET" class="d-flex align-items-center"
                        style="width: 100%;">
                        <div id="searchSuggestMobile" class="suggest-box d-none"></div>

                        <input type="search" name="title" class="form-control me-2"
                            placeholder="{{ __('messages.booksearch') }}..." required autofocus>
                            <div id="searchSpinnerMobile" class="search-spinner"></div>

                        <button type="submit" class="btn btn-outline-success me-2"><i
                                class="bi bi-search down"></i></button>
                      <button type="button" class="btn btn-outline-secondary"
        id="closeMobileSearch"
        aria-label="Close search">
    <i class="bi bi-x-lg down"></i>
</button>


                    </form>
                </div>

                <!-- ✅ Mobile Language Switcher Floating Top-Right -->


              <button id="mobileMenuToggle"
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav"
        aria-controls="navbarNav"
        aria-expanded="false"
        aria-label="Toggle menu">
    <span class="hamburger-icon" aria-hidden="true">
        <span></span>
        <span></span>
        <span></span>
    </span>
</button>




                <div class="collapse navbar-collapse" id="navbarNav">
@php
    $isPublishing = request()->getHost() === 'publishing.bukinistebi.ge';
@endphp


@if($isPublishing)

<nav class="navbar navbar-expand-lg" style="background:#fff; border-bottom:1px solid #eee;">
    <div class="container">

        {{-- LOGO --}}
      

        {{-- SIMPLE MENU --}}
        <div class="ms-auto d-flex gap-3">

            <a href="https://bukinistebi.ge" class="btn btn-light">
                <span> 📚 ბუკინისტური მაღაზია </span>
            </a>

            <a href="https://forum.bukinistebi.ge" class="btn btn-dark">
               <span> 💬 ბუკინისტური ფორუმი </span>
            </a>

        </div>

    </div>
</nav>

@else

                    <ul class="navbar-nav ms-auto" style="position: relative;   z-index: 100000;">
                        <li class="nav-item">
                            <a class="nav-link" href="@langurl(url('/'))" aria-label="Bukinistebi Home">{{ __('messages.home') }}</a>
                        </li>


                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="genreDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('messages.categories') }}
                            </a>
                            <ul class="dropdown-menu genre-scroll" aria-labelledby="genreDropdown"
                                style="max-height: 300px; overflow-y: auto; min-width: 250px;">
                                <li class="d-lg-none px-3 py-2 mobile-category-back-wrap">
                                    <button type="button" class="btn mobile-category-back" id="mobileCategoryBack">
                                        <i class="bi bi-arrow-left"></i>
                                        <span>მენიუში დაბრუნება</span>
                                    </button>
                                </li>
                                <li class="px-3 py-2">
                                    <input type="text" class="form-control" id="genreSearchInput"
                                        placeholder="{{ __('messages.searchcategory') }}...">
                                </li>


                                <li class="genre-item all-item" data-name="{{ __('messages.all') }}">
                                    <a class="dropdown-item"
                                        href="@langurl(route('books'))">{{ __('messages.all') }}</a>
                                </li>


                                <li id="noResultsMessage" class="text-muted px-3 py-2" style="display: none;">
                                    {{ __('messages.noresult') }}
                                </li>

                               @foreach ($genres as $genre)
    @if (!$genre->isSouvenir())
        <li class="genre-item" data-name="{{ $genre->getLocalizedName() }}">
            <a class="dropdown-item"
               href="@langurl(route('genre.books', [
                   'id' => $genre->id,
                   'slug' => Str::slug($genre->getLocalizedName())
               ]))">
                {{ $genre->getLocalizedName() }}
            </a>
        </li>
    @endif
@endforeach



                            </ul>

                        </li>


                        <li class="nav-item">
                            <a class="nav-link" href="@langurl(route('bundles.index.public'))">
                                {{ __('messages.sets') }}
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link"
                                href="@langurl(route('souvenirs.index'))">{{ __('messages.souvenirs') }}</a>
                        </li>



<!-- <li class="nav-item">
    <a class="nav-link" href="https://publishing.bukinistebi.ge" target="_blank">
        გამომცემლობა
    </a>
</li> -->


                        <li class="nav-item">
                            <a class="nav-link"
                                href="@langurl(route('auction.index'))">{{ __('messages.auctions') }}</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="@langurl(route('order_us'))">{{ __('messages.order') }}</a>
                        </li>


                        <form class="desktop-search-form d-none d-lg-flex" role="search" action="@langurl(route('search'))" method="GET"
                            onsubmit="return validateSearch()" style="position: relative;  ">
                            <input class="form-control me-2 styled-input" name="title" type="search"
                                value="{{ request()->get('title') }}"
                                placeholder="{{ __('messages.booksearch') }}..." aria-label="Search"
                                id="searchInput">
                                @if(request('lang'))
    <input type="hidden" name="lang" value="{{ request('lang') }}">
@endif

                                <div id="searchSpinner" class="search-spinner"></div>

 <button class="btn submit-search" type="submit"><i
                                    class="bi bi-search" 
aria-label="Search" style="position: relative;  "></i></button>
                                                                <div id="searchSuggestBox" class="suggest-box d-none"></div>
                        </form>


                    </ul>

@endif
                </div>
            </div>
        </nav>
    </div>
    <!-- Main Content -->
    <div class="container mt-5 site-content">
        @yield('content') <!-- Page-specific content goes here -->
    </div>


    <!-- Footer -->
    @unless($isPublishing)
    <footer class="bg-dark text-white text-center text-lg-start mt-5" style="position: relative; padding-top: 30px;">
        <div class="container p-4">
            <div class="row">
                <!-- Column 1 -->

 @if($isPublishing)

<div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                     <p><img src="https://bukinistebi.ge/uploads/logo/logo Geo.png" width="120" height="29" loading="lazy" decoding="async" alt="Bukinistebi logo"> </p>
                </div>
 @else

                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase">Bukinistebi.ge</h5>
                    <p><span style="padding-right:33px">{{ __('messages.numberone') }}</span></p>
                </div>

                @endif

                <!-- Terms and Conditions Column -->
                  @if($isPublishing)

                  @else
                <div class="col-lg-3 offset-lg-1 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase">{{ __('messages.forcustomers') }}</h5>
                    <p>
                        <a href="@langurl(route('terms_conditions'))" class="text-white text-decoration-none">
                            <span>{{ __('messages.terms') }}</span>
                        </a>
                    </p>
                </div>
                @endif

                <!-- Column 3 -->
                  @if($isPublishing)
                    
 
        @else
        <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase">{{ __('messages.contact') }}</h5>
                  <ul class="list-unstyled">
    <li></li>
        <a href="mailto:info@bukinistebi.ge" style="text-decoration:none; color:inherit;">
            <i class="bi bi-envelope-fill"></i> info@bukinistebi.ge
        </a>
       
    </li>
</ul>

                </div>
                 @endif

                <!-- Column 4 -->

                @if($isPublishing)


                @else
                <div class="col-lg-2 col-md-5 mb-4 mb-md-0">
                    <h5>{{ __('messages.newsletter') }}</h5>
                    <form id="subscriptionForm" method="POST" action="@langurl(route('subscribe'))">
                        @csrf

                        <input type="email" name="email" class="form-control mb-2"
                            placeholder="{{ __('messages.entermail') }}" required>
                     @php
    $newsletterErrors = session()->get('errors')?->getBag('newsletter');
@endphp

@if ($newsletterErrors && $newsletterErrors->any())
    <input type="hidden" id="subscriptionErrorFlag" value="true">
    <input type="hidden" id="subscriptionErrorMessages"
        value="{{ implode('|', $newsletterErrors->all()) }}">
@endif


                        <button type="submit" class="btn btn-primary w-100">{{ __('messages.subscribe') }}</button>
                    </form>

                    <!-- Hidden input for success -->
                   
                  

                    <br>

                    <!-- TOP.GE ASYNC COUNTER CODE -->
                    <div id="top-ge-counter-container" data-site-id="117729" style="float: right"></div>
                    <script async src="//counter.top.ge/counter.js"></script>
                    <!-- / END OF TOP.GE COUNTER CODE -->
                </div>

@endif



                <!-- Success Modal -->
                <div class="modal fade" style="z-index: 101010101010 !important" id="subscriptionSuccessModal"
                    tabindex="-1" role="dialog" aria-labelledby="subscriptionSuccessModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="subscriptionSuccessModalLabel" style="color: black">
                                    გზავნილი</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body"
                                style="display: flex; align-items: center; gap: 8px; color:black !important">
                                <i class="bi bi-check-circle-fill text-success fs-4"></i>
                                <span>მადლობა გამოწერისთვის!</span>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Error Modal -->
                <div class="modal fade" style="z-index: 101010101010 !important"
                    id="subscriptionErrorModalPageSpecific" tabindex="-1" role="dialog"
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
                                <ul id="subscriptionErrorListPageSpecific"
                                    style="list-style: none; padding-top:10px;"></ul>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
  @php
                        $isPublishing = request()->getHost() === 'publishing.bukinistebi.ge';
                        @endphp

                        @if($isPublishing)

                         @else
                <div class="row mt-4">
                    <!-- Social Media -->
                    <div class="col text-center">
                        <h5 class="text-uppercase" style="position: relative; left:-15px">{{ __('messages.follow') }}
                        </h5>
                        
                      
 
                        
                       
                        <a href="https://www.facebook.com/bukinistebi.georgia" class="fb-icon" target="blank" aria-label="Follow us on Facebook">
                            <i class="bi bi-facebook fs-5"></i></a>
                        <a href="https://www.instagram.com/bukinistebi.ge/" class="insta-icon" target="blank" aria-label="Follow us on Instagram">
                            <i class="bi bi-instagram fs-5"></i></a>
                        <a href="https://www.youtube.com/channel/UCrXyA0hq0gDJME5wgRGTbbA" class="youtube-icon"
                            target="blank" aria-label="Visit our YouTube channel"><i class="bi bi-youtube fs-3"></i></a>
                        <a href="#" class="tiktok-icon" aria-label="Visit our TikTok"><i class="bi bi-tiktok fs-5"></i></a>
                       
                    </div>
                </div>
 @endif

            </div>

            <div class="text-center p-3 bg-dark">
                <!-- Additional footer content if needed -->

            </div>
    </footer><!-- Script -->
    @endunless




 
<script defer src="{{ asset('js/cookieConsent.js') }}"></script>
<script>
    window.cookieConsentConfig = {
        csrf: '{{ csrf_token() }}',
        user_name: '{{ Auth::check() ? Auth::user()->name : 'Guest' }}',
        storeUrl: '{{ route('store-user-behavior') }}'
    };
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




<script>
document.addEventListener("DOMContentLoaded", function () {

    const forumItem = document.getElementById("forumMenuItem");
    if (!forumItem) return;

    const VISIT_LIMIT = 3;

    let visits = localStorage.getItem("forumHighlightVisits");
    visits = visits ? parseInt(visits) : 0;

    if (visits < VISIT_LIMIT) {
        forumItem.classList.add("glow");
        localStorage.setItem("forumHighlightVisits", visits + 1);
    } else {
        // Remove badge after limit
        const badge = forumItem.querySelector(".forum-new-badge");
        if (badge) badge.remove();
    }

});
</script>



<script>
        // Prevent the dropdown from closing when interacting with tabs or dropdown content
        document.querySelectorAll('.dropdown-menu').forEach(function(dropdown) {
            dropdown.addEventListener('click', function(e) {
                e.stopPropagation(); // Prevent the default dropdown close behavior
            });
        });

        const mobileCategoryBack = document.getElementById('mobileCategoryBack');
        const genreDropdown = document.getElementById('genreDropdown');
        if (mobileCategoryBack && genreDropdown) {
            mobileCategoryBack.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const dropdown = bootstrap.Dropdown.getOrCreateInstance(genreDropdown);
                dropdown.hide();
            });
        }

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
            const subscriptionSuccessModal = new bootstrap.Modal(
                document.getElementById('subscriptionSuccessModal')
            );
            subscriptionSuccessModal.show();
        }

        // Handle subscription error modal
        const subscriptionErrorFlag = document.getElementById('subscriptionErrorFlag');
        if (subscriptionErrorFlag && subscriptionErrorFlag.value === 'true') {
            const subscriptionErrorModal = new bootstrap.Modal(
                document.getElementById('subscriptionErrorModalPageSpecific')
            );
            const errorMessages = document.getElementById('subscriptionErrorMessages').value.split('|');

            const errorList = document.getElementById('subscriptionErrorListPageSpecific');
            errorList.innerHTML = '';
            errorMessages.forEach(message => {
                const li = document.createElement('li');
                li.innerHTML = `<i class="bi bi-exclamation-circle-fill text-danger"></i> ${message}`;
                errorList.appendChild(li);
            });

            subscriptionErrorModal.show();
        }

        // ✅ MOBILE SEARCH OPEN/CLOSE — SIMPLE VERSION
        const openSearchBtn  = document.getElementById('mobileSearchToggle');
        const mobileOverlay  = document.getElementById('mobileSearchOverlay');
        const closeSearchBtn = document.getElementById('closeMobileSearch');

        if (openSearchBtn && mobileOverlay && closeSearchBtn) {
            openSearchBtn.addEventListener('click', function () {
                openSearchBtn.blur();
                mobileOverlay.classList.add('is-open');
                openSearchBtn.setAttribute('aria-expanded', 'true');
                setTimeout(function () {
                    const input = mobileOverlay.querySelector('input[name="title"]');
                    if (input) input.focus();
                }, 210);
            });

            closeSearchBtn.addEventListener('click', function () {
                mobileOverlay.classList.remove('is-open');
                openSearchBtn.setAttribute('aria-expanded', 'false');
            });
        }
    });
</script>



 
    <!-- Include Bootstrap JS if needed -->
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" defer></script>





    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const topNavbar = document.getElementById('topStickyNavbar');

            window.addEventListener('scroll', function() {
                if (window.scrollY > 10) {
                    topNavbar.classList.add('scrolled');
                } else {
                    topNavbar.classList.remove('scrolled');
                }
            }, { passive: true });
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











 



    @yield('scripts')




    @if (Auth::check() && Auth::user()->cart && Auth::user()->cart->cartItems()->count() > 0)
        <div class="sticky-cart-summary d-block d-md-none">
            <a href="@langurl(route('cart.index'))"
                class="btn btn-primary w-100 d-flex justify-content-between align-items-center">
                <span><i class="bi bi-cart-fill"></i> კალათაში {{ Auth::user()->cart->cartItems()->count() }} წიგნი
                    გაქვს </span>
                <span>ნახე კალათა</span>
            </a>
        </div>
    @endif
    @stack('scripts')



    
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.book-card').forEach(function (card) {
        const title = card.querySelector('.book-hover-title');
        if (!title) return;

        // მხოლოდ desktop-ზე ვამუშავებთ hover-ს
        if (window.innerWidth > 768) {
            title.textContent = title.getAttribute('data-short');

            card.addEventListener('mouseenter', function () {
                title.textContent = title.getAttribute('data-full');
            });

            card.addEventListener('mouseleave', function () {
                title.textContent = title.getAttribute('data-short');
            });
        }
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
                <a href="@langurl(route('cart.index'))" class="btn btn-sm btn-primary ms-2">ნახეთ კალათა</a>
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

    document.addEventListener('DOMContentLoaded', function () {
    initCartButtons();
});


        function updateCartCount(count) {
            const countElement = document.getElementById('cart-count');
            const bubble = document.getElementById('cart-bubble');

            if (countElement && bubble) {
                countElement.textContent = count;

                if (parseInt(count) > 0) {
                    bubble.style.display = 'inline-grid';
                } else {
                    bubble.style.display = 'none';
                }
            }
        }

        $(document).ready(function() {
           

$(document).on('click', '.toggle-cart-btn', function () {
                var button = $(this);
                var bookId = button.data('product-id');
var quantity = parseInt($('#quantity-' + bookId).val()) || 1;

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
    button.data('in-cart', true);

    const itemId = button.data('product-id');
    const itemName = button.data('book-title') || 'Unknown book';
    const itemPrice = parseFloat(button.data('book-price')) || 0;

    if (typeof gtag === 'function') {
        gtag('event', 'add_to_cart', {
            currency: 'GEL',
            value: itemPrice * quantity,
            items: [{
                item_id: String(itemId),
                item_name: itemName,
                price: itemPrice,
                quantity: quantity
            }]
        });
    }
    if (typeof fbq === 'function') {
    fbq('track', 'AddToCart', {
        content_ids: [String(itemId)],
        content_name: itemName,
        content_type: 'product',
        value: itemPrice * quantity,
        currency: 'GEL'
    });
}
} else if (response.action === 'removed') {
                                button.removeClass('btn-success').addClass('btn-primary');
                                button.find('i').removeClass('bi-check-circle').addClass(
                                    'bi-cart-plus');
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
                    bubble.style.display = 'inline-grid';
                } else {
                    bubble.style.display = 'none';
                }
            }
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 🌙 Dark mode toggle
            const modeToggle = document.getElementById('modeToggle');
            const prefersDark = localStorage.getItem('theme') === 'dark';

            if (prefersDark) {
                document.body.classList.add('dark-mode');
                modeToggle.innerHTML = '<i class="bi bi-sun"></i>';
            }

            modeToggle.addEventListener('click', function() {
                document.body.classList.toggle('dark-mode');
                if (document.body.classList.contains('dark-mode')) {
                    localStorage.setItem('theme', 'dark');
                    modeToggle.innerHTML = '<i class="bi bi-sun"></i>';
                } else {
                    localStorage.setItem('theme', 'light');
                    modeToggle.innerHTML = '<i class="bi bi-moon-fill"></i>';
                }
            });


            const navbar = document.getElementById('mainNavbar');
            if (!navbar) return;
            let lastScroll = window.scrollY;

            window.addEventListener('scroll', function() {
                const currentScroll = window.scrollY;

                if (currentScroll > lastScroll && currentScroll > 100) {
                    navbar.classList.add('hide'); // Scroll down: hide
                } else {
                    navbar.classList.remove('hide'); // Scroll up: show
                }

                lastScroll = currentScroll;
            }, { passive: true });
        });
    </script>




    <script>
        $(function() {
            const $form = $('form.d-none.d-lg-flex[role="search"]');
            const $input = $('#searchInput');
            const $box = $('#searchSuggestBox');
            let timer = null;

            function escapeHtml(s) {
                return String(s).replace(/[&<>"']/g, m => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;'
                } [m]));
            }

         $input.on('input', function () {
    const q = this.value.trim();
    clearTimeout(timer);

    // SHOW SPINNER
    $('#searchSpinner').show();

    if (q.length < 2) {
        $box.addClass('d-none').empty();
        $('#searchSpinner').hide();
        return;
    }

    timer = setTimeout(function () {
        $.get('{{ route("search.suggest") }}', { q }, function (res) {

    const list = res.items || [];
    const didYouMean = res.didYouMean || null;

    // HIDE SPINNER
    $('#searchSpinner').hide();

    // 🔹 DID YOU MEAN (only if no items)
    if (!list.length && didYouMean) {
        $box.html(`
            <div class="suggest-didyoumean" style="padding:10px;">
                <i class="bi bi-lightbulb"></i>
                {{ __('messages.didyoumean') }}
                <a href="#" class="didyoumean-link">${escapeHtml(didYouMean)}</a>?
            </div>
        `).removeClass('d-none');

        $box.find('.didyoumean-link').on('click', function (e) {
    e.preventDefault();

    const lang = @json($lang);
    window.location.href =
        '/search?title=' + encodeURIComponent(didYouMean)
        + (lang ? '&lang=' + lang : '');
});


        return;
    }

    // 🔹 NO RESULTS AT ALL
    if (!list.length) {
        $box.html('<div class="suggest-empty">{{ __("messages.noresult") }}</div>')
            .removeClass('d-none');
        return;
    }

    let html = '<ul class="list-unstyled mb-0">';


list.forEach(it => {
    const title  = it.title || '';
    const author = it.author || '';
    const url    = it.url || '';
    const img    = it.image || '{{ asset('default.webp') }}';
    const sold   = !!it.sold;

    html += `
    <li class="suggest-item ${sold ? 'suggest-sold' : ''}"
        data-title="${escapeHtml(title)}"
        data-url="${escapeHtml(url)}">
        <span class="thumb-bg" style="background-image:url('${img}');"></span>
      <div class="suggest-text">
    <div class="suggest-title">
        ${escapeHtml(title)}
        ${sold ? `<span class="suggest-sold-badge">Sold</span>` : ``}
    </div>
    ${author ? `<div class="suggest-author">${escapeHtml(author)}</div>` : ``}
</div>

    </li>`;
});


 // ONLY add See More if there ARE results
if (list.length >= 4) {
    html += `
        <li class="search-see-more" data-see-more>
            <span class="see-more-icon">
                <i class="bi bi-search"></i>
            </span>
            <span class="see-more-text">
                {{ __('messages.seemore') }}
            </span>
            <span class="see-more-arrow">
                <i class="bi bi-chevron-right"></i>
            </span>
        </li>`;
}


html += '</ul>';



                        $box.html(html).removeClass('d-none');
                    }).fail(function () {
            $('#searchSpinner').hide();
        });

    }, 200);
});

    $box.on('click', '[data-see-more]', function () {
    const q = $input.val().trim();
    const lang = @json($lang);

    if (q.length > 0) {
        window.location.href =
            '/search?title=' + encodeURIComponent(q)
            + (lang ? '&lang=' + lang : '');
    }
});




            // Click: go to URL if present; else fill+submit
            $box.on('click', '.suggest-item', function() {
    const url  = $(this).data('url');
    const lang = @json($lang);

    if (url) {
        window.location.href =
            url + (lang ? (url.includes('?') ? '&' : '?') + 'lang=' + lang : '');
    } else {
        $input.val($(this).data('title'));
        $form.trigger('submit');
    }
});


            $(document).on('click', function(e) {
                if (!$(e.target).closest('#searchInput, #searchSuggestBox').length) {
                    $box.addClass('d-none').empty();
                }
            });
        });
    </script>


    <script>
        $(function() {
            const $mobileForm = $('#mobileSearchOverlay form');
            const $mobileInput = $('#mobileSearchOverlay input[name="title"]');
            const $mobileBox = $('#searchSuggestMobile');
            let mTimer = null;

            function escapeHtml(s) {
                return String(s).replace(/[&<>"']/g, m => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;'
                } [m]));
            }

            $mobileInput.on('input', function () {
    const q = this.value.trim();
    clearTimeout(mTimer);

    // SHOW SPINNER
    $('#searchSpinnerMobile').show();

    if (q.length < 2) {
        $mobileBox.addClass('d-none').empty();
        $('#searchSpinnerMobile').hide();
        return;
    }

    mTimer = setTimeout(function () {
        $.get('{{ route("search.suggest") }}', { q }, function (res) {

    const list = res.items || [];
    const didYouMean = res.didYouMean || null;

    // HIDE SPINNER
    $('#searchSpinnerMobile').hide();

    if (!list.length && didYouMean) {
        $mobileBox.html(`
            <div class="suggest-didyoumean">
                <i class="bi bi-lightbulb"></i>
                {{ __('messages.didyoumean') }}
                <a href="#" class="didyoumean-link">${escapeHtml(didYouMean)}</a>?
            </div>
        `).removeClass('d-none');

        $mobileBox.find('.didyoumean-link').on('click', function (e) {
    e.preventDefault();

    const lang = @json($lang);
    window.location.href =
        '/search?title=' + encodeURIComponent(didYouMean)
        + (lang ? '&lang=' + lang : '');
});


        return;
    }

    if (!list.length) {
        $mobileBox.html('<div class="suggest-empty">{{ __("messages.noresult") }}</div>')
            .removeClass('d-none');
        return;
    }

    let html = '<ul class="list-unstyled mb-0">';


list.forEach(it => {
    const title  = it.title || '';
    const author = it.author || '';
    const url    = it.url || '';
    const img    = it.image || '{{ asset('default.webp') }}';
    const sold   = !!it.sold;

    html += `
    <li class="suggest-item ${sold ? 'suggest-sold' : ''}"
        data-title="${escapeHtml(title)}"
        data-url="${escapeHtml(url)}">
        <span class="thumb-bg" style="background-image:url('${img}');"></span>
        <div class="suggest-text">
    <div class="suggest-title">
        ${escapeHtml(title)}
        ${sold ? `<span class="suggest-sold-badge">Sold</span>` : ``}
    </div>
    ${author ? `<div class="suggest-author">${escapeHtml(author)}</div>` : ``}
</div>

    </li>`;
});


// ONLY add See More if there ARE results
 if (list.length >= 4) {
    html += `
        <li class="search-see-more" data-see-more>
            <span class="see-more-icon">
                <i class="bi bi-search"></i>
            </span>
            <span class="see-more-text">
                {{ __('messages.seemore') }}
            </span>
            <span class="see-more-arrow">
                <i class="bi bi-chevron-right"></i>
            </span>
        </li>`;
}


html += '</ul>';






                        $mobileBox.html(html).removeClass('d-none');
                    }).fail(function () {
            $('#searchSpinnerMobile').hide();
        });

    }, 200);
});

$mobileBox.on('click', '[data-see-more]', function() {
    const q = $mobileInput.val().trim();
    const lang = @json($lang);

    window.location.href =
        '/search?title=' + encodeURIComponent(q)
        + (lang ? '&lang=' + lang : '');
});



            $mobileBox.on('click', '.suggest-item', function() {
    const url  = $(this).data('url');
    const lang = @json($lang);

    if (url) {
        window.location.href =
            url + (lang ? (url.includes('?') ? '&' : '?') + 'lang=' + lang : '');
    } else {
        $mobileInput.val($(this).data('title'));
        $mobileForm.trigger('submit');
    }
});


            $(document).on('click', function(e) {
                if (!$(e.target).closest('#mobileSearchOverlay input[name="title"], #searchSuggestMobile')
                    .length) {
                    $mobileBox.addClass('d-none').empty();
                }
            });

            $('#mobileSearchToggle, #closeMobileSearch').on('click', function() {
                $mobileBox.addClass('d-none').empty();
            });
        });
    </script>

 
<script>
function initCartButtons(scope = document) {
    const translations = {
        added: @json(__('messages.added')),
        addToCart: @json(__('messages.addtocart'))
    };

    scope.querySelectorAll('.cart-btn-text').forEach(function(el) {
        const state = el.getAttribute('data-state');
        el.textContent = state === 'added'
            ? translations.added
            : translations.addToCart;
    });
}
</script> 




@php
$announcement = \App\Models\GlobalAnnouncement::where('is_active', 1)

    ->where(function($q){
        $q->whereNull('starts_at')
          ->orWhere('starts_at', '<=', now());
    })

    ->where(function($q){
        $q->whereNull('ends_at')
          ->orWhere('ends_at', '>=', now());
    })

    ->get()
    ->first(function($a){

        $now = now();

        if ($a->recurrence_type === 'none') return true;

        if ($a->recurrence_time) {
            if ($now->format('H:i') !== \Carbon\Carbon::parse($a->recurrence_time)->format('H:i')) {
                return false;
            }
        }

        if ($a->recurrence_type === 'daily') return true;

        if ($a->recurrence_type === 'weekly') {
            return $now->isSameDayOfWeek($a->starts_at ?? $a->created_at);
        }

        if ($a->recurrence_type === 'monthly') {
            return $now->day === ($a->starts_at?->day ?? $a->created_at->day);
        }

        return false;
    });
@endphp


@if($announcement)
<input type="hidden" id="announcement_id" value="{{ $announcement->id }}">

<div class="modal fade" id="announcementModal" tabindex="-1" style="display:none;background:rgba(0,0,0,.6)">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">
            {{ $announcement->title ?? 'Important Notice' }}
        </h5>
<button onclick="closeAnnouncement()" class="btn-close"></button>
      </div>

      <div class="modal-body">
        {!! nl2br(e($announcement->message)) !!}
      </div>

      <div class="modal-footer">
      <button class="btn btn-primary" onclick="closeAnnouncement()">
დახურვა </button>

      </div>

    </div>
  </div>
</div>
@endif
<script>
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById('announcementModal');
    const announcementId = document.getElementById('announcement_id')?.value;

    if (!modal || !announcementId) return;

    // If user already closed THIS announcement → don't show again
    if (localStorage.getItem('announcement_closed_' + announcementId)) {
        modal.remove();
        return;
    }

    // Show modal after page load + delay
    window.addEventListener('load', function () {

        setTimeout(function () {
            modal.style.display = 'block';
            modal.classList.add('show');
        }, 2500);   // 2500ms = 2.5 seconds (change if you want)

    });

    // Close + remember
    window.closeAnnouncement = function () {
        localStorage.setItem('announcement_closed_' + announcementId, true);
        modal.remove();
    };
});
</script>


</body>



</html>
