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

/* =========================
   Layout fixes for publishing
   ========================= */

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

/* =========================
   Top / Main Navbar
   ========================= */

body:has(.pub-site) #topStickyNavbar {
    position: relative !important;
    top: auto !important;
    min-height: 54px !important;
    border: 0 !important;
    box-shadow: none !important;
    background:
        radial-gradient(circle at 12% 20%, rgba(181, 138, 58, .10), transparent 28rem),
        linear-gradient(135deg, #17120d, #0d0a07) !important;
}

body:has(.pub-site) #topStickyNavbar > .container {
    max-width: 1420px !important;
    width: min(1420px, calc(100% - 80px)) !important;
    padding: 0 !important;
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
}

body:has(.pub-site) #topStickyNavbar .navbar-nav {
    margin-left: auto !important;
}

body:has(.pub-site) #topStickyNavbar .navbar-nav li {
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
    opacity: 1 !important;
    color: #d8c298 !important;
    font-family: liFont, sans-serif !important;
    font-size: 14px !important;
    font-weight: 700 !important;
    letter-spacing: .3px;
}

body:has(.pub-site) #topStickyNavbar li i,
body:has(.pub-site) #topStickyNavbar .fb-icon-top i,
body:has(.pub-site) #topStickyNavbar .insta-icon-top i {
    color: #c89a46 !important;
    font-size: 22px !important;
    transition: color .25s ease, transform .25s ease;
}

body:has(.pub-site) #topStickyNavbar .fb-icon-top,
body:has(.pub-site) #topStickyNavbar .insta-icon-top {
    margin-right: 18px !important;
}

body:has(.pub-site) #topStickyNavbar a:hover i {
    color: #f0c36b !important;
}

body:has(.pub-site) #mainNavbar {
    position: relative !important;
    top: auto !important;
    min-height: 74px !important;
    padding: 12px 0 !important;
    background: #fffaf2 !important;
    border-bottom: 1px solid rgba(181, 138, 58, .22) !important;
    box-shadow: 0 12px 34px rgba(28, 22, 14, .05) !important;
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

/* =========================
   Base
   ========================= */

.pub-site {
    margin: 0 !important;
    width: 100% !important;
    color: var(--pub-ink);
    overflow: hidden;
    background:
        radial-gradient(circle at 80% 8%, rgba(181, 138, 58, .16), transparent 34rem),
        radial-gradient(circle at 10% 85%, rgba(21, 18, 14, .10), transparent 30rem),
        linear-gradient(135deg, #f5efe4 0%, #fffaf2 48%, #e9dfcf 100%) !important;
}

.pub-wrap {
    width: min(1420px, calc(100% - 80px));
    margin: 0 auto;
}

/* =========================
   Typography
   ========================= */

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

.pub-lead,
.pub-section-copy,
.pub-feature p,
.pub-book-body p,
.pub-contact p,
.pub-empty,
.pub-btn,
.pub-kicker,
.pub-book-category,
.pub-form .form-label,
.pub-form .form-control {
    font-family: liFont, sans-serif !important;
}

.pub-feature h3,
.pub-book-body h3 {
    font-family: h4Font, liFont, sans-serif !important;
}

/* =========================
   Hero
   ========================= */

.pub-hero {
    min-height: 760px;
    display: grid;
    grid-template-columns: minmax(0, 1.04fr) minmax(420px, .96fr);
    gap: 76px;
    align-items: center;
    padding: 44px 0 96px;
    position: relative;
    perspective: 1200px;
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
    font-size: 14px;
    font-weight: 900;
    box-shadow: 0 12px 34px rgba(28, 22, 14, .05);
}

.pub-lead {
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

.pub-hero-visual {
    position: relative;
}

/* =========================
   Buttons
   ========================= */

.pub-btn {
    min-height: 54px;
    padding: 14px 22px;
    border-radius: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
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

/* =========================
   Hero 3D book stack
   ========================= */

.pub-book-stage {
    position: relative;
    width: min(620px, 100%);
    height: 640px;
    margin-left: auto;
    perspective: 1400px;
}

.pub-light-orb {
    position: absolute;
    width: 420px;
    height: 420px;
    right: 30px;
    top: 40px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(181, 138, 58, .22), transparent 68%);
    filter: blur(10px);
    transition: transform 1.2s ease;
}

.pub-3d-book {
    position: absolute;
    width: 350px;
    height: 500px;
    background: #fffaf2;
    border: 1px solid rgba(181, 138, 58, .35);
    box-shadow: 0 34px 90px rgba(21, 18, 14, .24);
    transform-style: preserve-3d;
    overflow: hidden;
    transition:
        transform 1.1s cubic-bezier(.16, 1, .3, 1),
        box-shadow 1.1s cubic-bezier(.16, 1, .3, 1);
}

.pub-3d-book::before {
    content: "";
    position: absolute;
    top: 0;
    right: -18px;
    width: 18px;
    height: 100%;
    background: linear-gradient(90deg, #d8cbb9, #fff6e6);
    transform: skewY(18deg);
    transform-origin: left;
}

.pub-3d-book::after {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(120deg, transparent 0%, rgba(255, 255, 255, .24) 38%, transparent 62%);
    opacity: 0;
    transition: opacity .8s ease;
}

.pub-3d-book img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    filter: saturate(.95) contrast(1.02);
}

.pub-book-front {
    right: 78px;
    top: 36px;
    z-index: 3;
    transform: rotateY(-5deg) rotateZ(0);
}

.pub-book-middle {
    right: 150px;
    top: 88px;
    z-index: 2;
    opacity: .9;
    transform: rotateZ(-7deg) translateZ(-50px);
}

.pub-book-back {
    right: 18px;
    top: 124px;
    z-index: 1;
    opacity: .72;
    transform: rotateZ(8deg) translateZ(-90px);
}

.pub-book-stage:hover .pub-book-front {
    transform: translateY(-16px) rotateY(-12deg) rotateZ(-1deg);
    box-shadow: 0 52px 130px rgba(21, 18, 14, .34);
}

.pub-book-stage:hover .pub-book-middle {
    transform: translate(-28px, 12px) rotateZ(-11deg);
}

.pub-book-stage:hover .pub-book-back {
    transform: translate(28px, 18px) rotateZ(12deg);
}

.pub-book-stage:hover .pub-3d-book::after {
    opacity: 1;
}

.pub-book-stage:hover .pub-light-orb {
    transform: translate(-30px, 20px) scale(1.08);
}

/* =========================
   Sections
   ========================= */

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
    font-size: 18px;
    line-height: 1.9;
}

.pub-section-alt .pub-section-copy {
    color: rgba(255, 255, 255, .70);
}

/* =========================
   Feature cards
   ========================= */

.pub-feature-grid,
.pub-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 24px;
}

.pub-feature {
    padding: 32px;
    min-height: 260px;
    background: rgba(255, 255, 255, .055);
    border: 1px solid rgba(255, 255, 255, .13);
    transition: .22s ease;
}

.pub-feature:hover {
    transform: translateY(-5px);
    background: rgba(255, 255, 255, .085);
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

.pub-feature h3 {
    margin: 0 0 12px;
    font-size: 24px;
    font-weight: 950;
}

.pub-feature p {
    margin: 0;
    color: rgba(255, 255, 255, .66);
    line-height: 1.8;
}

/* =========================
   Book cards
   ========================= */

.pub-book-card {
    position: relative;
    background: rgba(255, 250, 242, .82);
    border: 1px solid var(--pub-line);
    box-shadow: 0 20px 60px rgba(28, 22, 14, .08);
    overflow: hidden;
    transition: .22s ease;
}

.pub-book-card::after {
    content: "";
    position: absolute;
    inset: 0;
    pointer-events: none;
    opacity: 0;
    background: radial-gradient(circle at 50% 0%, rgba(181, 138, 58, .22), transparent 42%);
    transition: opacity .25s ease;
}

.pub-book-card:hover {
    transform: translateY(-7px);
    box-shadow: 0 30px 86px rgba(28, 22, 14, .15);
}

.pub-book-card:hover::after {
    opacity: 1;
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
    transform-origin: left center;
    transition: .3s ease;
}

.pub-book-card:hover .pub-book-image img {
    transform: scale(1.045) rotateY(-3deg);
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
    line-height: 1.75;
}

.pub-shop-btn {
    width: fit-content;
    margin-top: auto;
}

.pub-empty {
    padding: 34px;
    border: 1px dashed rgba(21, 18, 14, .24);
    background: rgba(255, 250, 242, .82);
    color: var(--pub-muted);
}

/* =========================
   Contact
   ========================= */

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
    color: rgba(255, 255, 255, .68);
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
    border: 1px solid rgba(255, 255, 255, .14);
    background: rgba(255, 255, 255, .06);
    box-shadow: 0 28px 80px rgba(0, 0, 0, .24);
    backdrop-filter: blur(12px);
}

.pub-form .form-label {
    color: rgba(255, 255, 255, .86);
    font-weight: 850;
}

.pub-form .form-control {
    min-height: 50px;
    border-radius: 0;
    border: 1px solid rgba(255, 255, 255, .18);
    background: rgba(255, 255, 255, .94);
    color: var(--pub-ink);
}

.pub-form textarea.form-control {
    min-height: 156px;
}

/* =========================
   Animation
   ========================= */

.pub-reveal {
    opacity: 0;
    transform: translateY(28px);
    transition: opacity 1s ease, transform 1s cubic-bezier(.16, 1, .3, 1);
}

.pub-reveal.is-visible {
    opacity: 1;
    transform: translateY(0);
}

/* =========================
   Responsive
   ========================= */

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

    .pub-book-stage {
        height: 540px;
        margin-left: 0;
    }

    .pub-3d-book {
        width: 300px;
        height: 430px;
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

    .pub-book-stage {
        height: 430px;
    }

    .pub-3d-book {
        width: 230px;
        height: 330px;
    }

    .pub-book-front {
        right: 42px;
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
    <div class="pub-book-stage">
        <div class="pub-light-orb"></div>

        <div class="pub-3d-book pub-book-back">
            <img src="https://bukinistebi.ge/uploads/logo/publishing_logo.jpg" alt="გამომცემლობა ბუკინისტები">
        </div>

        <div class="pub-3d-book pub-book-middle">
            <img src="https://bukinistebi.ge/uploads/logo/publishing_logo.jpg" alt="გამომცემლობა ბუკინისტები">
        </div>

        <div class="pub-3d-book pub-book-front">
            <img src="https://bukinistebi.ge/uploads/logo/publishing_logo.jpg" alt="გამომცემლობა ბუკინისტები">
        </div>
    </div>
</div>
    </section>

    <section class="pub-section pub-section-alt">
        <div class="pub-wrap">
            <div class="pub-section-head">
                <h2 class="pub-section-title" style="color: #362f2f !important;">რას აკეთებს გამომცემლობა?</h2>
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
                <h2 class="pub-section-title" style="color: #070707 !important;">გამოგვიგზავნეთ ტექსტი</h2>
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
    const revealTargets = document.querySelectorAll(
        '.pub-hero-copy, .pub-hero-visual, .pub-section, .pub-book-card, .pub-contact-grid'
    );

    revealTargets.forEach(function (el) {
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
        const stage = document.querySelector('.pub-book-stage');
        if (!stage) return;

        const move = window.scrollY * 0.025;
        stage.style.transform = 'translateY(' + move + 'px)';
    }, { passive: true });
});
</script>
@endsection
