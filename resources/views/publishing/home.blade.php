@extends('layouts.app')

@section('title', 'გამომცემლობა ბუკინისტები')

@section('content')
@php
    $featured = $items->first();
@endphp

<style>
    :root {
        --pub-ink: #171b1f;
        --pub-graphite: #23282d;
        --pub-charcoal: #111417;
        --pub-muted: #68717b;
        --pub-line: #dfe3e7;
        --pub-soft: #f4f5f6;
        --pub-white: #ffffff;
        --pub-shadow: 0 24px 70px rgba(17, 20, 23, .12);
        --pub-radius-lg: 32px;
        --pub-radius-md: 20px;
    }

    .pub-site {
        margin: 0 calc(50% - 50vw);
        background:
            radial-gradient(circle at top left, rgba(35, 40, 45, .08), transparent 34rem),
            linear-gradient(180deg, #fff 0%, #f6f7f8 44%, #fff 100%);
        color: var(--pub-ink);
        overflow: hidden;
        font-family: liFont, sans-serif !important;
    }

    h1,
    h2,
    h5 {
        font-family: h1Font, sans-serif !important;
    }

    h4 {
        font-family: h4Font, sans-serif !important;
    }

    body,
    button,
    input,
    li,
    select,
    span,
    p,
    .pub-lead,
    .pub-section-copy,
    .pub-section-title,
    .pub-feature p,
    .pub-book-body p,
    .pub-book-body h3,
    .pub-book-category,
    .pub-btn,
    .pub-kicker,
    .pub-hero-copy,
    .pub-section-title,
    .pub-contact p,
    .pub-form .form-label,
    .pub-form .form-control {
        font-family: liFont, sans-serif !important;
    }

    .pub-title,
    .pub-section-title,
    .pub-hero-copy h1 {
        font-family: h1Font, sans-serif !important;
    }

    .pub-wrap {
        width: min(1140px, calc(100% - 40px));
        max-width: 1140px;
        padding: 0 20px;
        margin: 0 auto;
        box-sizing: border-box;
    }

    .pub-hero {
        position: relative;
        padding: 70px 0 50px;
    }

    .pub-hero-copy {
        max-width: 760px;
    }

    .pub-hero-copy {
        position: relative;
        z-index: 1;
    }

    .pub-hero {
        position: relative;
        padding: 110px 0 80px;
        overflow: hidden;
        background: linear-gradient(180deg, #fbfbfb 0%, #f3ede5 100%);
    }

    .pub-hero::before {
        content: '';
        position: absolute;
        top: 8%;
        left: 8%;
        width: 260px;
        height: 260px;
        background: radial-gradient(circle, rgba(156,58,58,.12), transparent 62%);
        border-radius: 50%;
        z-index: 0;
    }

    .pub-hero::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: -70px;
        width: 260px;
        height: 260px;
        background: radial-gradient(circle, rgba(23,27,31,.06), transparent 58%);
        border-radius: 50%;
        z-index: 0;
    }

    .pub-hero-inner {
        display: grid;
        grid-template-columns: minmax(0, 1.1fr) minmax(360px, 420px);
        gap: 42px;
        align-items: center;
        position: relative;
        z-index: 1;
    }

    .pub-hero-copy {
        max-width: 740px;
        position: relative;
    }

    .pub-hero-copy::before {
        content: '';
        position: absolute;
        top: -18px;
        left: -22px;
        width: 92px;
        height: 92px;
        border-radius: 50%;
        background: rgba(156,58,58,.08);
        z-index: -1;
    }

    .pub-hero-panel {
        position: relative;
        width: 100%;
        min-height: 520px;
        border-radius: 48px;
        padding: 36px;
        background: linear-gradient(180deg, #ffffff 0%, #f8f3eb 100%);
        border: 1px solid rgba(17, 20, 23, .08);
        box-shadow: 0 44px 120px rgba(17, 20, 23, .14);
        overflow: hidden;
        display: grid;
        grid-template-rows: auto 1fr;
        gap: 26px;
    }

    .pub-hero-panel::before {
        content: '';
        position: absolute;
        top: -32px;
        right: -32px;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(156,58,58,.12);
    }

    .pub-hero-panel::after {
        content: '';
        position: absolute;
        bottom: -28px;
        left: -28px;
        width: 240px;
        height: 240px;
        border-radius: 50%;
        background: rgba(23,27,31,.06);
    }

    .pub-hero-panel-top {
        display: flex;
        justify-content: flex-end;
        width: 100%;
    }

    .pub-hero-panel-chip {
        padding: 11px 18px;
        border-radius: 999px;
        background: rgba(156,58,58,.12);
        color: var(--pub-ink);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
    }

    .pub-hero-panel-card {
        width: 100%;
        max-width: 380px;
        border-radius: 36px;
        background: linear-gradient(180deg, #fdfbf8 0%, #fff 100%);
        border: 1px solid rgba(17, 20, 23, .06);
        box-shadow: 0 32px 78px rgba(17, 20, 23, .10);
        overflow: hidden;
        padding: 30px;
        position: relative;
        z-index: 1;
    }

    .pub-hero-panel-card::before {
        content: '';
        position: absolute;
        top: 18px;
        right: 18px;
        width: 80px;
        height: 80px;
        border-radius: 24px;
        background: rgba(156,58,58,.12);
    }

    .pub-hero-panel-card-header {
        min-height: 170px;
        border-radius: 30px;
        background: linear-gradient(180deg, rgba(156,58,58,.14), rgba(255,255,255,.98));
        margin-bottom: 28px;
        position: relative;
    }

    .pub-hero-panel-card-header::before {
        content: '';
        position: absolute;
        bottom: 18px;
        left: 18px;
        width: 90px;
        height: 90px;
        border-radius: 24px;
        background: rgba(23,27,31,.06);
    }

    .pub-hero-panel-card-body {
        display: grid;
        gap: 16px;
    }

    .pub-hero-panel-card-line {
        height: 14px;
        border-radius: 999px;
        background: linear-gradient(90deg, rgba(35,40,45,.08), rgba(35,40,45,.02));
    }

    .pub-hero-panel-card-line.long {
        width: 100%;
    }

    .pub-hero-panel-card-line.medium {
        width: 80%;
    }

    .pub-hero-panel-card-line.short {
        width: 56%;
    }

    .pub-hero-panel-card-footer {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
        margin-top: 32px;
    }

    .pub-hero-panel-card-tag {
        min-height: 14px;
        border-radius: 999px;
        background: rgba(156,58,58,.16);
    }

    .pub-hero-panel-card-tag.light {
        background: rgba(23,27,31,.08);
    }

    #topStickyNavbar,
    #topStickyNavbar.scrolled,
    #mainNavbar {
        position: static !important;
        top: auto !important;
        transform: none !important;
        width: 100% !important;
    }

    .pub-kicker {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 24px;
        padding: 11px 16px;
        border: 1px solid rgba(17, 20, 23, .08);
        border-radius: 999px;
        background: rgba(255,255,255,.92);
        color: var(--pub-muted);
        font-size: 13px;
        font-weight: 800;
        letter-spacing: .03em;
        backdrop-filter: blur(8px);
        box-shadow: 0 12px 28px rgba(17,20,23,.04);
    }

    .pub-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        margin-top: 40px;
    }

    .pub-btn {
        min-width: 190px;
        padding: 14px 24px;
        border-radius: 999px;
        font-weight: 900;
        letter-spacing: .01em;
    }

    .pub-btn-dark {
        background: #16181b;
        color: #fff;
        border-color: transparent;
        box-shadow: 0 18px 38px rgba(23,27,31,.18);
    }

    .pub-btn-light {
        background: #fff;
        color: var(--pub-ink);
        border-color: rgba(17,20,23,.12);
    }

    .pub-btn-light:hover {
        background: rgba(255,255,255,.96);
    }

    .pub-kicker {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 22px;
        padding: 9px 14px;
        border: 1px solid var(--pub-line);
        border-radius: 999px;
        background: rgba(255, 255, 255, .82);
        color: var(--pub-muted);
        font-size: 14px;
        font-weight: 800;
        letter-spacing: .02em;
        backdrop-filter: blur(12px);
    }

    .pub-kicker i {
        color: var(--pub-ink);
    }

    .pub-title {
        max-width: 760px;
        margin: 0 0 24px;
        font-size: clamp(44px, 6.6vw, 88px);
        line-height: .95;
        font-weight: 950;
        letter-spacing: -.06em;
        color: var(--pub-charcoal);
    }

    .pub-lead {
        max-width: 690px;
        margin: 0 0 18px;
        color: #4f5963;
        font-size: 20px;
        line-height: 1.85;
    }

    .pub-lead strong {
        color: var(--pub-ink);
        font-weight: 900;
    }

    .pub-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 34px;
    }

    .pub-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-height: 52px;
        padding: 13px 22px;
        border: 1px solid var(--pub-ink);
        border-radius: 999px;
        font-weight: 900;
        text-decoration: none;
        transition: transform .2s ease, box-shadow .2s ease, background .2s ease, color .2s ease, border-color .2s ease;
    }

    .pub-btn:hover {
        transform: translateY(-2px);
    }

    .pub-btn-dark {
        background: var(--pub-ink);
        color: var(--pub-white);
        box-shadow: 0 14px 34px rgba(23, 27, 31, .22);
    }

    .pub-btn-dark:hover {
        background: #000;
        border-color: #000;
        color: var(--pub-white);
        box-shadow: 0 18px 42px rgba(0, 0, 0, .26);
    }

    .pub-btn-light {
        background: var(--pub-white);
        color: var(--pub-ink);
        border-color: var(--pub-line);
    }

    .pub-btn-light:hover {
        border-color: var(--pub-ink);
        color: var(--pub-ink);
    }

    .pub-visual-card {
        position: relative;
        padding: 18px;
        border: 1px solid rgba(255, 255, 255, .16);
        border-radius: var(--pub-radius-lg);
        background: rgba(255, 255, 255, .08);
        box-shadow: var(--pub-shadow);
        backdrop-filter: blur(10px);
    }

    .pub-visual-card::before {
        content: '';
        position: absolute;
        inset: 34px -18px -18px 48px;
        border-radius: 28px;
        background: rgba(255,255,255,.12);
        z-index: -1;
    }

    .pub-visual-card img {
        display: block;
        width: 100%;
        aspect-ratio: 4 / 5;
        object-fit: cover;
        border-radius: 24px;
        background: #fff;
    }

    .pub-floating-note {
        position: absolute;
        left: -34px;
        bottom: 34px;
        width: min(280px, 72%);
        padding: 22px;
        border: 1px solid var(--pub-line);
        border-radius: 22px;
        background: rgba(255,255,255,.94);
        box-shadow: 0 20px 48px rgba(17, 20, 23, .14);
        color: var(--pub-ink);
    }

    .pub-floating-note strong {
        display: block;
        margin-bottom: 8px;
        font-size: 18px;
        font-weight: 950;
    }

    .pub-floating-note span {
        color: var(--pub-muted);
        line-height: 1.7;
    }

    .pub-section {
        padding: 74px 0;
    }

    .pub-section-alt {
        background: var(--pub-soft);
        border-block: 1px solid var(--pub-line);
    }

    .pub-section-head {
        display: grid;
        grid-template-columns: minmax(240px, .75fr) minmax(0, 1.25fr);
        gap: 34px;
        align-items: end;
        margin-bottom: 34px;
    }

    .pub-section-title {
        margin: 0;
        color: var(--pub-charcoal);
        font-size: clamp(34px, 4vw, 56px);
        line-height: 1;
        font-weight: 950;
        letter-spacing: -.045em;
    }

    .pub-section-copy {
        margin: 0;
        color: var(--pub-muted);
        font-size: 17px;
        line-height: 1.9;
    }

    .pub-feature-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .pub-feature {
        position: relative;
        min-height: 250px;
        padding: 28px;
        border: 1px solid var(--pub-line);
        border-radius: var(--pub-radius-md);
        background: var(--pub-white);
        box-shadow: 0 12px 34px rgba(17, 20, 23, .05);
        overflow: hidden;
    }

    .pub-feature::after {
        content: '';
        position: absolute;
        right: -44px;
        top: -44px;
        width: 120px;
        height: 120px;
        border-radius: 999px;
        background: #eef0f2;
    }

    .pub-feature i {
        position: relative;
        z-index: 1;
        display: inline-flex;
        width: 52px;
        height: 52px;
        align-items: center;
        justify-content: center;
        margin-bottom: 22px;
        border-radius: 16px;
        background: var(--pub-ink);
        color: #fff;
        font-size: 24px;
    }

    .pub-feature h3 {
        margin: 0 0 12px;
        font-size: 22px;
        font-weight: 950;
        color: var(--pub-charcoal);
    }

    .pub-feature p {
        margin: 0;
        color: var(--pub-muted);
        line-height: 1.8;
    }

    .pub-showcase-top {
        display: flex;
        justify-content: space-between;
        gap: 24px;
        align-items: end;
        margin-bottom: 30px;
    }

    .pub-showcase-top .pub-section-copy {
        max-width: 560px;
    }

    .pub-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 24px;
    }

    .pub-book-card {
        display: flex;
        flex-direction: column;
        min-height: 100%;
        border: 1px solid var(--pub-line);
        border-radius: 26px;
        background: var(--pub-white);
        box-shadow: 0 14px 40px rgba(17, 20, 23, .07);
        overflow: hidden;
        transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
    }

    .pub-book-card:hover {
        transform: translateY(-7px);
        border-color: #c6ccd2;
        box-shadow: 0 26px 70px rgba(17, 20, 23, .13);
    }

    .pub-book-image {
        display: block;
        aspect-ratio: 4 / 3;
        background: linear-gradient(135deg, #eceff1, #fff);
        overflow: hidden;
    }

    .pub-book-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .28s ease;
    }

    .pub-book-card:hover .pub-book-image img {
        transform: scale(1.045);
    }

    .pub-book-body {
        display: flex;
        flex: 1;
        flex-direction: column;
        padding: 24px;
    }

    .pub-book-category {
        display: inline-flex;
        width: fit-content;
        margin-bottom: 12px;
        padding: 7px 11px;
        border-radius: 999px;
        background: #edf0f2;
        color: #4d5660;
        font-size: 12px;
        font-weight: 950;
    }

    .pub-book-body h3 {
        margin: 0 0 10px;
        color: var(--pub-charcoal);
        font-size: 23px;
        font-weight: 950;
        letter-spacing: -.02em;
    }

    .pub-book-body p {
        margin: 0 0 22px;
        color: var(--pub-muted);
        line-height: 1.75;
    }

    .pub-shop-btn {
        width: fit-content;
        margin-top: auto;
    }

    .pub-empty {
        padding: 34px;
        border: 1px dashed #b9c0c7;
        border-radius: var(--pub-radius-md);
        background: #fff;
        color: var(--pub-muted);
    }

    .pub-contact {
        padding: 78px 0 86px;
        background:
            radial-gradient(circle at 12% 20%, rgba(255,255,255,.08), transparent 24rem),
            linear-gradient(135deg, #171b1f, #0d0f11);
        color: var(--pub-white);
    }

    .pub-contact-grid {
        display: grid;
        grid-template-columns: minmax(260px, .85fr) minmax(320px, 1.15fr);
        gap: 42px;
        align-items: start;
    }

    .pub-contact .pub-section-title {
        color: var(--pub-white);
    }

    .pub-contact p {
        color: rgba(255,255,255,.72);
        font-size: 17px;
        line-height: 1.85;
    }

    .pub-contact a {
        color: var(--pub-white);
        text-decoration: underline;
        text-underline-offset: 4px;
    }

    .pub-form {
        padding: 30px;
        border: 1px solid rgba(255,255,255,.14);
        border-radius: 28px;
        background: rgba(255,255,255,.07);
        box-shadow: 0 24px 64px rgba(0,0,0,.22);
        backdrop-filter: blur(12px);
    }

    .pub-form .form-label {
        color: rgba(255,255,255,.86);
        font-weight: 850;
    }

    .pub-form .form-control {
        min-height: 48px;
        border: 1px solid rgba(255,255,255,.18);
        border-radius: 14px;
        background: rgba(255,255,255,.94);
        color: var(--pub-ink);
    }

    .pub-form textarea.form-control {
        min-height: 156px;
    }

    @media (max-width: 991px) {
        .pub-hero,
        .pub-section-head,
        .pub-contact-grid {
            grid-template-columns: 1fr;
        }

        .pub-hero::after {
            display: none;
        }

        .pub-visual-card {
            max-width: 520px;
            background: var(--pub-ink);
        }

        .pub-feature-grid,
        .pub-grid {
            grid-template-columns: 1fr 1fr;
        }

        .pub-showcase-top {
            display: block;
        }

        .pub-showcase-top .pub-section-title {
            margin-bottom: 14px;
        }
    }

    @media (max-width: 575px) {
        .pub-wrap {
            width: min(100% - 24px, 1180px);
        }

        .pub-hero {
            min-height: 0;
            padding: 46px 0;
        }

        .pub-title {
            letter-spacing: -.04em;
        }

        .pub-floating-note {
            position: static;
            width: auto;
            margin-top: 12px;
        }

        .pub-feature-grid,
        .pub-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="pub-site">
    <section class="pub-hero">
        <div class="container pub-hero-inner">
            <div class="pub-hero-copy">
                <div class="pub-kicker"><i class="bi bi-book-half"></i> გამომცემლობა ბუკინისტები</div>
                <h1 class="pub-title">ძველი გამოცემების ახალი სიცოცხლე</h1>
                <p class="pub-lead">
                    <strong>გამომცემლობა „ბუკინისტები“</strong> არის ძველი გამოცემების ახალი სიცოცხლე.
                    ჩვენი მთავარი ნიშა ბუკინისტური წიგნების გამოცემა და ბუკინისტური გამოცემების მშენებლობაა.
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
            <div class="pub-hero-panel">
                <div class="pub-hero-panel-top">
                    <span class="pub-hero-panel-chip">გამომცემლობა</span>
                </div>
                <div class="pub-hero-panel-card">
                    <div class="pub-hero-panel-card-header"></div>
                    <div class="pub-hero-panel-card-body">
                        <div class="pub-hero-panel-card-line long"></div>
                        <div class="pub-hero-panel-card-line medium"></div>
                        <div class="pub-hero-panel-card-line short"></div>
                        <div class="pub-hero-panel-card-line medium"></div>
                    </div>
                    <div class="pub-hero-panel-card-footer">
                        <span class="pub-hero-panel-card-tag"></span>
                        <span class="pub-hero-panel-card-tag light"></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pub-section pub-section-alt">
        <div class="container pub-wrap">
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
        <div class="container pub-wrap">
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
        <div class="container pub-wrap pub-contact-grid">
            <div>
                <h2 class="pub-section-title">გამოგვიგზავნეთ ტექსტი</h2>
                <p>
                    შეავსეთ ფორმა, ატვირთეთ ფაილი ან დაგვიტოვეთ შეტყობინება — თანამშრომლობის დეტალებით დაგიკავშირდებით.
                </p>
                <p class="mb-0"><strong>Email:</strong> <a href="mailto:publishing@bukinistebi.ge">publishing@bukinistebi.ge</a></p>
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
@endsection
