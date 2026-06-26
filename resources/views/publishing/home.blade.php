@extends('layouts.app')

@section('title', 'გამომცემლობა ბუკინისტები')

@section('content')
@php
    $featured = $items->first();
    $heroImage = $featured?->image_1 ? asset('storage/' . $featured->image_1) : asset('uploads/logo/bukinistebi.ge.png');
@endphp

<style>
@font-face {
    font-family: h1Font;
    src: url('/fonts/alk-tommaso-webfont.ttf') format('truetype');
    font-weight: 400;
    font-style: normal;
    font-display: swap;
}

@font-face {
    font-family: liFont;
    src: url('/fonts/bpg_boxo-boxo.ttf') format('truetype');
    font-weight: 400;
    font-style: normal;
    font-display: swap;
}

@font-face {
    font-family: h4Font;
    src: url('/fonts/bpg-glaho-web-caps-webfont.ttf') format('truetype');
    font-weight: 700;
    font-style: normal;
    font-display: swap;
}

:root {
    --pub-bg: #f5efe4;
    --pub-paper: #fffaf2;
    --pub-ink: #15120e;
    --pub-muted: #6d6256;
    --pub-gold: #b58a3a;
    --pub-gold-dark: #8d6827;
    --pub-line: rgba(21, 18, 14, .14);
    --pub-dark: #17120d;
    --pub-white: #ffffff;
    --pub-shadow: 0 28px 80px rgba(28, 22, 14, .14);
}

/* layout fixes only for publishing */
body:has(.pub-site) {
    padding-top: 0 !important;
    background: var(--pub-bg);
}

body:has(.pub-site) > .container.mt-5 {
    margin-top: 0 !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
    max-width: none !important;
    width: 100% !important;
}

body:has(.pub-site) hr {
    display: none !important;
}

/* publishing navbar recolor + right menu */
body:has(.pub-site) #topStickyNavbar {
    position: relative !important;
    top: auto !important;
    background: #1b1712 !important;
    min-height: 54px !important;
    border: 0 !important;
    box-shadow: none !important;
}

body:has(.pub-site) #mainNavbar {
    position: relative !important;
    top: auto !important;
    background: #fffaf2 !important;
    border-bottom: 1px solid rgba(181, 138, 58, .22) !important;
    box-shadow: 0 12px 34px rgba(28, 22, 14, .05) !important;
    min-height: 74px !important;
    padding: 12px 0 !important;
}

body:has(.pub-site) #mainNavbar > .container {
    max-width: 1420px !important;
    width: min(1420px, calc(100% - 80px)) !important;
    padding: 0 !important;
}

body:has(.pub-site) #navbarNav {
    justify-content: flex-end !important;
}

body:has(.pub-site) #navbarNav > nav.navbar {
    width: auto !important;
    margin-left: auto !important;
    padding: 0 !important;
    background: transparent !important;
    border: 0 !important;
}

body:has(.pub-site) #navbarNav > nav.navbar > .container {
    width: auto !important;
    max-width: none !important;
    padding: 0 !important;
}

body:has(.pub-site) #navbarNav .btn-light {
    background: rgba(181, 138, 58, .10) !important;
    border: 1px solid rgba(181, 138, 58, .20) !important;
    color: #3a2b15 !important;
}

body:has(.pub-site) #navbarNav .btn-dark {
    background: #17120d !important;
    border: 1px solid #17120d !important;
    color: #fffaf2 !important;
}

.pub-site {
    margin: 0 !important;
    width: 100% !important;
    background:
        radial-gradient(circle at 82% 8%, rgba(181, 138, 58, .13), transparent 34rem),
        radial-gradient(circle at 12% 90%, rgba(21, 18, 14, .08), transparent 30rem),
        linear-gradient(180deg, var(--pub-bg), #fffaf2 46%, #f7f1e7);
    color: var(--pub-ink);
    overflow: hidden;
}

.pub-wrap {
    width: min(1420px, calc(100% - 80px));
    margin: 0 auto;
}

.pub-hero {
    min-height: 760px;
    display: grid;
    grid-template-columns: minmax(0, 1.04fr) minmax(420px, .96fr);
    gap: 76px;
    align-items: center;
    padding: 44px 0 96px;
    position: relative;
}

.pub-hero::before {
    content: "BUKINISTEBI";
    position: absolute;
    top: 8px;
    left: -8px;
    font-size: clamp(70px, 12vw, 170px);
    line-height: 1;
    font-weight: 950;
    letter-spacing: -.08em;
    color: rgba(21, 18, 14, .035);
    pointer-events: none;
}

.pub-kicker {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 30px;
    padding: 10px 16px;
    border: 1px solid rgba(181, 138, 58, .28);
    border-radius: 999px;
    background: rgba(255, 250, 242, .82);
    color: var(--pub-gold-dark);
    font-family: liFont, sans-serif !important;
    font-size: 14px;
    font-weight: 900;
    box-shadow: 0 12px 34px rgba(28, 22, 14, .05);
}

.pub-hero h1,
.pub-section-title {
    font-family: h1Font, sans-serif !important;
    font-weight: 400 !important;
    color: #111;
}

.pub-hero h1 {
    max-width: 820px;
    margin: 0 0 28px;
    font-size: clamp(68px, 7.6vw, 116px);
    line-height: .88;
    letter-spacing: -1px;
}

.pub-lead {
    font-family: liFont, sans-serif !important;
    max-width: 780px;
    margin: 0 0 16px;
    color: #3a332d;
    font-size: 23px;
    line-height: 1.82;
}

.pub-lead strong {
    font-weight: 950;
}

.pub-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    margin-top: 40px;
}

.pub-btn {
    min-height: 54px;
    padding: 14px 22px;
    border-radius: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    font-family: liFont, sans-serif !important;
    font-weight: 900;
    text-decoration: none;
    transition: .22s ease;
    border: 1px solid var(--pub-ink);
}

.pub-btn:hover {
    transform: translateY(-2px);
    text-decoration: none;
}

.pub-btn-dark {
    background: var(--pub-ink);
    color: var(--pub-white);
    box-shadow: 0 18px 42px rgba(21, 18, 14, .2);
}

.pub-btn-dark:hover {
    background: #000;
    color: var(--pub-white);
}

.pub-btn-light {
    background: transparent;
    color: var(--pub-ink);
}

.pub-btn-light:hover {
    background: var(--pub-ink);
    color: var(--pub-white);
}

.pub-hero-visual {
    position: relative;
}

.pub-visual-card {
    position: relative;
    padding: 18px;
    background: rgba(255,255,255,.36);
    border: 1px solid var(--pub-line);
    box-shadow: var(--pub-shadow);
}

.pub-visual-card::before {
    content: "";
    position: absolute;
    inset: 34px -28px -28px 42px;
    border: 1px solid rgba(181, 138, 58, .42);
    z-index: 0;
}

.pub-visual-card img {
    position: relative;
    z-index: 1;
    display: block;
    width: 100%;
    aspect-ratio: 4 / 5;
    object-fit: cover;
    filter: saturate(.92) contrast(1.03);
}

.pub-section {
    padding: 92px 0;
}

.pub-section-alt {
    background:
        radial-gradient(circle at 12% 20%, rgba(181, 138, 58, .10), transparent 28rem),
        linear-gradient(135deg, #17120d, #0d0a07);
    color: var(--pub-white);
}

.pub-section-head,
.pub-showcase-top {
    display: grid;
    grid-template-columns: minmax(320px, .85fr) minmax(0, 1.15fr);
    gap: 42px;
    align-items: end;
    margin-bottom: 42px;
}

.pub-section-title {
    margin: 0;
    font-size: clamp(52px, 5.4vw, 82px);
    line-height: .95;
    letter-spacing: -1px;
}

.pub-section-copy {
    margin: 0;
    color: var(--pub-muted);
    font-family: liFont, sans-serif !important;
    font-size: 18px;
    line-height: 1.9;
}

.pub-section-alt .pub-section-copy {
    color: rgba(255,255,255,.70);
}

.pub-feature-grid,
.pub-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 24px;
}

.pub-feature {
    padding: 32px;
    min-height: 260px;
    background: rgba(255,255,255,.055);
    border: 1px solid rgba(255,255,255,.13);
    transition: .22s ease;
}

.pub-feature:hover {
    transform: translateY(-5px);
    background: rgba(255,255,255,.085);
}

.pub-feature i {
    width: 54px;
    height: 54px;
    margin-bottom: 24px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(181, 138, 58, .58);
    color: var(--pub-gold);
    font-size: 25px;
}

.pub-feature h3,
.pub-book-body h3 {
    font-family: h4Font, liFont, sans-serif !important;
}

.pub-feature h3 {
    margin: 0 0 12px;
    font-size: 24px;
    font-weight: 950;
}

.pub-feature p {
    margin: 0;
    color: rgba(255,255,255,.66);
    font-family: liFont, sans-serif !important;
    line-height: 1.8;
}

.pub-book-card {
    background: rgba(255,250,242,.82);
    border: 1px solid var(--pub-line);
    box-shadow: 0 20px 60px rgba(28, 22, 14, .08);
    overflow: hidden;
    transition: .22s ease;
}

.pub-book-card:hover {
    transform: translateY(-7px);
    box-shadow: 0 30px 86px rgba(28, 22, 14, .15);
}

.pub-book-image {
    display: block;
    aspect-ratio: 4 / 3;
    overflow: hidden;
    background: #eadfce;
}

.pub-book-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: .3s ease;
}

.pub-book-card:hover .pub-book-image img {
    transform: scale(1.045);
}

.pub-book-body {
    min-height: 280px;
    padding: 26px;
    display: flex;
    flex-direction: column;
}

.pub-book-category {
    width: fit-content;
    margin-bottom: 14px;
    padding: 7px 11px;
    border: 1px solid rgba(181, 138, 58, .36);
    color: var(--pub-gold-dark);
    font-family: liFont, sans-serif !important;
    font-size: 12px;
    font-weight: 950;
}

.pub-book-body h3 {
    margin: 0 0 12px;
    color: var(--pub-ink);
    font-size: 24px;
    line-height: 1.2;
    font-weight: 950;
}

.pub-book-body p {
    margin: 0 0 24px;
    color: var(--pub-muted);
    font-family: liFont, sans-serif !important;
    line-height: 1.75;
}

.pub-shop-btn {
    width: fit-content;
    margin-top: auto;
}

.pub-empty {
    padding: 34px;
    border: 1px dashed rgba(21, 18, 14, .24);
    background: rgba(255,250,242,.82);
    color: var(--pub-muted);
    font-family: liFont, sans-serif !important;
}

.pub-contact {
    background:
        radial-gradient(circle at 18% 20%, rgba(181, 138, 58, .16), transparent 26rem),
        linear-gradient(135deg, #15120e, #080706);
    color: var(--pub-white);
    padding: 94px 0 108px;
}

.pub-contact-grid {
    display: grid;
    grid-template-columns: minmax(330px, .84fr) minmax(420px, 1.16fr);
    gap: 54px;
    align-items: start;
}

.pub-contact p {
    color: rgba(255,255,255,.68);
    font-family: liFont, sans-serif !important;
    font-size: 18px;
    line-height: 1.85;
}

.pub-contact a {
    color: #e4c17c;
    text-decoration: underline;
    text-underline-offset: 4px;
}

.pub-form {
    padding: 34px;
    border: 1px solid rgba(255,255,255,.14);
    background: rgba(255,255,255,.06);
    box-shadow: 0 28px 80px rgba(0,0,0,.24);
    backdrop-filter: blur(12px);
}

.pub-form .form-label {
    color: rgba(255,255,255,.86);
    font-family: liFont, sans-serif !important;
    font-weight: 850;
}

.pub-form .form-control {
    min-height: 50px;
    border-radius: 0;
    border: 1px solid rgba(255,255,255,.18);
    background: rgba(255,255,255,.94);
    color: var(--pub-ink);
    font-family: liFont, sans-serif !important;
}

.pub-form textarea.form-control {
    min-height: 156px;
}

@media (max-width: 991px) {
    body:has(.pub-site) #mainNavbar > .container {
        width: min(100% - 28px, 1420px) !important;
    }

    .pub-wrap {
        width: min(100% - 32px, 1420px);
    }

    .pub-hero,
    .pub-section-head,
    .pub-showcase-top,
    .pub-contact-grid {
        grid-template-columns: 1fr;
    }

    .pub-hero {
        min-height: 0;
        gap: 42px;
    }

    .pub-feature-grid,
    .pub-grid {
        grid-template-columns: 1fr 1fr;
    }

    .pub-visual-card {
        max-width: 580px;
    }
}

@media (max-width: 575px) {
    .pub-wrap {
        width: min(100% - 24px, 1420px);
    }

    .pub-hero {
        padding: 42px 0 64px;
    }

    .pub-hero h1 {
        font-size: 56px;
    }

    .pub-actions {
        flex-direction: column;
        align-items: stretch;
    }

    .pub-btn {
        width: 100%;
    }

    .pub-feature-grid,
    .pub-grid {
        grid-template-columns: 1fr;
    }
}

.pub-site {
    background:
        radial-gradient(circle at 80% 8%, rgba(181,138,58,.16), transparent 34rem),
        radial-gradient(circle at 10% 85%, rgba(21,18,14,.10), transparent 30rem),
        linear-gradient(135deg, #f5efe4 0%, #fffaf2 48%, #e9dfcf 100%) !important;
}

.pub-hero {
    perspective: 1200px;
}

.pub-visual-card {
    background: transparent !important;
    border: 0 !important;
    box-shadow: none !important;
    padding: 0 !important;
}

.pub-visual-card::before {
    display: none !important;
}

.pub-book-stack {
    position: relative;
    width: min(560px, 100%);
    height: 620px;
    margin-left: auto;
}

.pub-stack-book {
    position: absolute;
    width: 340px;
    height: 470px;
    background: #fffaf2;
    border: 1px solid rgba(181,138,58,.28);
    box-shadow: 0 34px 90px rgba(21,18,14,.22);
    transform-style: preserve-3d;
    transition: transform .35s ease, box-shadow .35s ease;
    overflow: hidden;
}

.pub-stack-book img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.pub-stack-book.main {
    right: 60px;
    top: 34px;
    z-index: 3;
}

.pub-stack-book.back-one {
    right: 120px;
    top: 82px;
    z-index: 2;
    transform: rotate(-7deg) translateZ(-40px);
    opacity: .92;
}

.pub-stack-book.back-two {
    right: 12px;
    top: 112px;
    z-index: 1;
    transform: rotate(8deg) translateZ(-80px);
    opacity: .72;
}

.pub-book-stack:hover .pub-stack-book.main {
    transform: translateY(-12px) rotateY(-6deg);
    box-shadow: 0 46px 120px rgba(21,18,14,.30);
}

.pub-book-stack:hover .pub-stack-book.back-one {
    transform: translate(-22px, 8px) rotate(-10deg);
}

.pub-book-stack:hover .pub-stack-book.back-two {
    transform: translate(22px, 14px) rotate(11deg);
}

.pub-paper-texture {
    position: absolute;
    inset: 0;
    pointer-events: none;
    opacity: .18;
    background-image:
        linear-gradient(rgba(21,18,14,.035) 1px, transparent 1px),
        linear-gradient(90deg, rgba(21,18,14,.025) 1px, transparent 1px);
    background-size: 34px 34px;
    mix-blend-mode: multiply;
}

.pub-reveal {
    opacity: 0;
    transform: translateY(26px);
    transition: opacity .75s ease, transform .75s ease;
}

.pub-reveal.is-visible {
    opacity: 1;
    transform: translateY(0);
}

.pub-book-card {
    position: relative;
}

.pub-book-card::after {
    content: "";
    position: absolute;
    inset: 0;
    pointer-events: none;
    opacity: 0;
    background: radial-gradient(circle at 50% 0%, rgba(181,138,58,.22), transparent 42%);
    transition: opacity .25s ease;
}

.pub-book-card:hover::after {
    opacity: 1;
}

.pub-book-image img {
    transform-origin: left center;
}

.pub-book-card:hover .pub-book-image img {
    transform: scale(1.045) rotateY(-3deg);
}

@media (max-width: 991px) {
    .pub-book-stack {
        height: 520px;
        margin: 0;
    }

    .pub-stack-book {
        width: 290px;
        height: 410px;
    }
}

@media (max-width: 575px) {
    .pub-book-stack {
        height: 420px;
    }

    .pub-stack-book {
        width: 230px;
        height: 330px;
    }

    .pub-stack-book.main {
        right: 34px;
    }
}
</style>

<div class="pub-site">
    <section class="pub-wrap pub-hero">
        <div class="pub-hero-copy">
            <div class="pub-kicker"><i class="bi bi-book-half"></i> publishing.bukinistebi.ge</div>

            <h1>გამომცემლობა ბუკინისტები</h1>

            <p class="pub-lead">
                <strong>გამომცემლობა „ბუკინისტები“</strong> არის ძველი გამოცემების ახალი სიცოცხლე.
                ჩვენი მთავარი ნიშა ბუკინისტური წიგნების გამოცემაა.
            </p>

            <p class="pub-lead">
                ამავდროულად, „ბუკინისტების“ ლოგოს ქვეშ თანამედროვე ავტორებიც გამოჩნდებიან.
            </p>

            <div class="pub-actions">
                <a href="#about-publishing" class="pub-btn pub-btn-dark">
                    <i class="bi bi-feather"></i> ტექსტის გამოგზავნა
                </a>

                @if($items->isNotEmpty())
                    <a href="#publishing-works" class="pub-btn pub-btn-light">
                        <i class="bi bi-grid-3x3-gap"></i> გამოცემები
                    </a>
                @endif
            </div>
        </div>

        <div class="pub-hero-visual pub-reveal">
    <div class="pub-book-stack">
        <div class="pub-paper-texture"></div>

        <div class="pub-stack-book back-one">
            <img src="https://bukinistebi.ge/uploads/logo/publishing_logo.jpg" alt="გამომცემლობა ბუკინისტები">
        </div>

        <div class="pub-stack-book back-two">
            <img src="https://bukinistebi.ge/uploads/logo/publishing_logo.jpg" alt="გამომცემლობა ბუკინისტები">
        </div>

        <div class="pub-stack-book main">
            <img src="https://bukinistebi.ge/uploads/logo/publishing_logo.jpg" alt="გამომცემლობა ბუკინისტები">
        </div>
    </div>
</div>
    </section>

    <section class="pub-section pub-section-alt">
        <div class="pub-wrap">
            <div class="pub-section-head">
                <h2 class="pub-section-title">რას აკეთებს გამომცემლობა?</h2>
                <p class="pub-section-copy">
                    აქ შეგიძლიათ წარადგინოთ ტექსტები, მიიღოთ გამოცემის მიმართულებით პირველი კონსულტაცია და წიგნი საფუძვლიანად განიხილოთ. სივრცე აგებულია მარტივ, სუფთა და მკითხველისთვის კომფორტულ გამოცდილებაზე.
                </p>
            </div>

            <div class="pub-feature-grid">
                <article class="pub-feature">
                    <i class="bi bi-journal-text"></i>
                    <h3>ტექსტის წარდგენა</h3>
                    <p>გამოგვიგზავნეთ ნაწარმოები, იდეა ან მოკლე აღწერა და ერთად განვიხილოთ გამოცემის გზა.</p>
                </article>

                <article class="pub-feature">
                    <i class="bi bi-chat-square-text"></i>
                    <h3>პირველი კონსულტაცია</h3>
                    <p>თუ ჯერ მხოლოდ გეგმა გაქვთ, მოგვწერეთ — დაგეხმარებით შემდეგი ნაბიჯების განსაზღვრაში.</p>
                </article>

                <article class="pub-feature">
                    <i class="bi bi-stars"></i>
                    <h3>ძველი და ახალი</h3>
                    <p>ვაბრუნებთ ძველ ტექსტებს მკითხველთან და ადგილს ვუთმობთ თანამედროვე ავტორებსაც.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="pub-section" id="publishing-works">
        <div class="pub-wrap">
            <div class="pub-showcase-top">
                <h2 class="pub-section-title">გამოცემები</h2>
            </div>

            @if($items->isNotEmpty())
                <div class="pub-grid">
                    @foreach($items as $item)
                        @php
                            $cover = collect([$item->image_1, $item->image_2, $item->image_3, $item->image_4])->filter()->first();
                            $shopUrl = $item->shop_url ?: route('publishing.show', $item);
                        @endphp

                        <article class="pub-book-card">
                            <a class="pub-book-image" href="{{ $shopUrl }}" @if($item->shop_url) target="_blank" rel="noopener" @endif>
                                @if($cover)
                                    <img src="{{ asset('storage/' . $cover) }}" alt="{{ $item->title }}">
                                @else
                                    <img src="{{ asset('uploads/logo/bukinistebi.ge.png') }}" alt="{{ $item->title }}">
                                @endif
                            </a>

                            <div class="pub-book-body">
                                @if($item->category)
                                    <span class="pub-book-category">{{ $item->category }}</span>
                                @endif

                                <h3>{{ $item->title }}</h3>

                                <p>{{ \Illuminate\Support\Str::limit(strip_tags($item->description), 135) }}</p>

                                <a href="{{ $shopUrl }}" class="pub-btn pub-btn-light pub-shop-btn" @if($item->shop_url) target="_blank" rel="noopener" @endif>
                                    მაღაზიაში ნახვა <i class="bi bi-arrow-up-right"></i>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="pub-empty">
                    მასალები ჯერ არ არის დამატებული. ჩანაწერების დამატების შემდეგ ისინი აქ გამოჩნდება.
                </div>
            @endif
        </div>
    </section>

    <section class="pub-contact" id="about-publishing">
        <div class="pub-wrap pub-contact-grid">
            <div>
                <h2 class="pub-section-title">გამოგვიგზავნეთ ტექსტი</h2>
                <p>
                    შეავსეთ ფორმა, ატვირთეთ ფაილი ან დაგვიტოვეთ შეტყობინება — თანამშრომლობის დეტალებით დაგიკავშირდებით.
                </p>
                <p class="mb-0">
                    <strong>Email:</strong>
                    <a href="mailto:publishing@bukinistebi.ge">publishing@bukinistebi.ge</a>
                </p>
            </div>

            <form class="pub-form" method="POST" action="{{ route('publishing.contact') }}" enctype="multipart/form-data">
                @csrf

                @if(session('publishing_success'))
                    <div class="alert alert-success">{{ session('publishing_success') }}</div>
                @endif

                <div class="mb-3">
                    <label class="form-label">სახელი</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name') <div class="text-warning small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">ელფოსტა</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    @error('email') <div class="text-warning small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">შეტყობინება</label>
                    <textarea name="message" class="form-control" required>{{ old('message') }}</textarea>
                    @error('message') <div class="text-warning small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">ფაილი</label>
                    <input type="file" name="attachment" class="form-control" accept=".pdf,.doc,.docx">
                    @error('attachment') <div class="text-warning small mt-1">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="pub-btn pub-btn-dark">
                    გაგზავნა <i class="bi bi-send"></i>
                </button>
            </form>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.pub-hero-copy, .pub-section, .pub-book-card, .pub-contact-grid').forEach(function (el) {
        el.classList.add('pub-reveal');
    });

    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    document.querySelectorAll('.pub-reveal').forEach(function (el) {
        observer.observe(el);
    });

    window.addEventListener('scroll', function () {
        const stack = document.querySelector('.pub-book-stack');
        if (!stack) return;

        const move = window.scrollY * 0.035;
        stack.style.transform = 'translateY(' + move + 'px)';
    }, { passive: true });
});
</script>
@endsection