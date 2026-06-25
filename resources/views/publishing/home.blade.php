@extends('layouts.app')

@section('title', 'გამომცემლობა ბუკინისტები')

@section('content')
@php
    $featured = $items->first();
    $heroImage = $featured?->image_1 ? asset('storage/' . $featured->image_1) : asset('uploads/logo/bukinistebi.ge.png');
@endphp

<style>
    .publishing-old-page {
        margin: 0 calc(50% - 50vw);
        background: #f6efe4;
        color: #2b2118;
        font-family: inherit;
    }

    .publishing-old-wrap {
        width: min(1180px, calc(100% - 32px));
        margin: 0 auto;
    }

    .publishing-old-hero {
        position: relative;
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(300px, 430px);
        gap: 48px;
        align-items: center;
        min-height: 640px;
        padding: 70px 0 64px;
        overflow: hidden;
    }

    .publishing-old-hero::before {
        content: '';
        position: absolute;
        top: 54px;
        right: calc(50% - 50vw);
        width: 42vw;
        height: calc(100% - 108px);
        background: #e7d3b2;
        z-index: 0;
    }

    .publishing-old-copy,
    .publishing-old-cover {
        position: relative;
        z-index: 1;
    }

    .publishing-old-kicker {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        color: #9c6124;
        font-weight: 800;
    }

    .publishing-old-kicker::before {
        content: '';
        width: 48px;
        height: 2px;
        background: #9c6124;
    }

    .publishing-old-title {
        max-width: 760px;
        margin: 0 0 24px;
        font-size: clamp(42px, 6vw, 78px);
        line-height: .98;
        font-weight: 900;
        letter-spacing: -.04em;
    }

    .publishing-old-lead {
        max-width: 720px;
        margin: 0 0 18px;
        font-size: 20px;
        line-height: 1.85;
        color: #5d5044;
    }

    .publishing-old-lead strong {
        color: #2b2118;
    }

    .publishing-old-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 30px;
    }

    .publishing-old-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-height: 48px;
        padding: 12px 20px;
        border: 1px solid #2b2118;
        border-radius: 0;
        font-weight: 800;
        text-decoration: none;
        transition: .2s ease;
    }

    .publishing-old-btn-primary {
        background: #2b2118;
        color: #fff;
    }

    .publishing-old-btn-primary:hover {
        background: #8f2d22;
        border-color: #8f2d22;
        color: #fff;
    }

    .publishing-old-btn-light {
        background: transparent;
        color: #2b2118;
    }

    .publishing-old-btn-light:hover {
        border-color: #8f2d22;
        color: #8f2d22;
    }

    .publishing-old-cover-frame {
        position: relative;
        padding: 18px;
        background: #fffaf2;
        border: 1px solid #d8c19e;
        box-shadow: 20px 22px 0 rgba(43, 33, 24, .12);
    }

    .publishing-old-cover-frame img {
        display: block;
        width: 100%;
        aspect-ratio: 3 / 4;
        object-fit: cover;
        background: #fff;
    }

    .publishing-old-stamp {
        position: absolute;
        left: -28px;
        bottom: 34px;
        max-width: 220px;
        padding: 18px;
        background: #2b2118;
        color: #fff;
        font-weight: 800;
        line-height: 1.5;
    }

    .publishing-old-about {
        padding: 64px 0;
        background: #fffaf2;
        border-top: 1px solid #eadbc4;
        border-bottom: 1px solid #eadbc4;
    }

    .publishing-old-about-grid {
        display: grid;
        grid-template-columns: minmax(240px, .65fr) minmax(0, 1.35fr);
        gap: 42px;
        align-items: start;
    }

    .publishing-old-section-title {
        margin: 0;
        font-size: clamp(32px, 4vw, 48px);
        line-height: 1.08;
        font-weight: 900;
    }

    .publishing-old-text {
        color: #5d5044;
        font-size: 18px;
        line-height: 1.9;
    }

    .publishing-old-text p:last-child {
        margin-bottom: 0;
    }

    .publishing-old-cards {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1px;
        margin-top: 34px;
        background: #e2cfb1;
        border: 1px solid #e2cfb1;
    }

    .publishing-old-card {
        min-height: 210px;
        padding: 28px;
        background: #f6efe4;
    }

    .publishing-old-card span {
        display: block;
        margin-bottom: 18px;
        color: #9c6124;
        font-size: 34px;
        line-height: 1;
    }

    .publishing-old-card h3 {
        margin: 0 0 12px;
        font-size: 20px;
        font-weight: 900;
    }

    .publishing-old-card p {
        margin: 0;
        color: #66584a;
        line-height: 1.75;
    }

    .publishing-old-works {
        padding: 68px 0;
    }

    .publishing-old-head {
        display: flex;
        justify-content: space-between;
        gap: 28px;
        align-items: end;
        margin-bottom: 28px;
    }

    .publishing-old-head p {
        max-width: 560px;
        margin: 0;
        color: #66584a;
        line-height: 1.75;
    }

    .publishing-old-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 24px;
    }

    .publishing-old-item {
        background: #fffaf2;
        border: 1px solid #e4d2b8;
    }

    .publishing-old-item-image {
        display: block;
        aspect-ratio: 4 / 3;
        overflow: hidden;
        background: #e7d3b2;
    }

    .publishing-old-item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .25s ease;
    }

    .publishing-old-item:hover img {
        transform: scale(1.04);
    }

    .publishing-old-item-body {
        padding: 22px;
    }

    .publishing-old-category {
        display: inline-flex;
        margin-bottom: 10px;
        color: #9c6124;
        font-size: 13px;
        font-weight: 900;
    }

    .publishing-old-item h3 {
        margin: 0 0 10px;
        font-size: 21px;
        font-weight: 900;
    }

    .publishing-old-item p {
        min-height: 78px;
        margin: 0 0 16px;
        color: #66584a;
        line-height: 1.7;
    }

    .publishing-old-empty {
        padding: 30px;
        border: 1px dashed #cdb58f;
        background: rgba(255, 250, 242, .65);
        color: #66584a;
    }

    .publishing-old-contact {
        padding: 68px 0 74px;
        background: #2b2118;
        color: #fff;
    }

    .publishing-old-contact-grid {
        display: grid;
        grid-template-columns: minmax(260px, .8fr) minmax(320px, 1.2fr);
        gap: 42px;
        align-items: start;
    }

    .publishing-old-contact p {
        color: rgba(255,255,255,.74);
        font-size: 17px;
        line-height: 1.85;
    }

    .publishing-old-contact a {
        color: #f1c676;
    }

    .publishing-old-form {
        padding: 28px;
        border: 1px solid rgba(255,255,255,.16);
        background: rgba(255,255,255,.06);
    }

    .publishing-old-form .form-label {
        color: rgba(255,255,255,.86);
        font-weight: 700;
    }

    .publishing-old-form .form-control {
        min-height: 46px;
        border: 0;
        border-radius: 0;
    }

    .publishing-old-form textarea.form-control {
        min-height: 152px;
    }

    @media (max-width: 991px) {
        .publishing-old-hero,
        .publishing-old-about-grid,
        .publishing-old-contact-grid {
            grid-template-columns: 1fr;
        }

        .publishing-old-hero::before {
            display: none;
        }

        .publishing-old-cover-frame {
            max-width: 430px;
        }

        .publishing-old-cards,
        .publishing-old-grid {
            grid-template-columns: 1fr;
        }

        .publishing-old-head {
            display: block;
        }

        .publishing-old-head .publishing-old-section-title {
            margin-bottom: 14px;
        }
    }

    @media (max-width: 575px) {
        .publishing-old-wrap {
            width: min(100% - 24px, 1180px);
        }

        .publishing-old-hero {
            min-height: 0;
            padding: 42px 0;
        }

        .publishing-old-stamp {
            position: static;
            max-width: none;
        }
    }
</style>

<div class="publishing-old-page">
    <section class="publishing-old-wrap publishing-old-hero">
        <div class="publishing-old-copy">
            <div class="publishing-old-kicker">გამომცემლობა ბუკინისტები</div>
            <h1 class="publishing-old-title">ძველი გამოცემების ახალი სიცოცხლე</h1>
            <p class="publishing-old-lead">
                <strong>გამომცემლობა „ბუკინისტები“</strong> არის ძველი გამოცემების ახალი სიცოცხლე.
                ჩვენი მთავარი ნიშა ბუკინისტური წიგნების გამოცემაა.
            </p>
            <p class="publishing-old-lead">
                ამავდროულად, „ბუკინისტების“ ლოგოს ქვეშ თანამედროვე ავტორებიც გამოჩნდებიან.
            </p>
            <div class="publishing-old-actions">
                <a href="#about-publishing" class="publishing-old-btn publishing-old-btn-primary">
                    <i class="bi bi-feather"></i> ტექსტის გამოგზავნა
                </a>
                @if($items->isNotEmpty())
                    <a href="#publishing-works" class="publishing-old-btn publishing-old-btn-light">
                        <i class="bi bi-book"></i> გამოცემები
                    </a>
                @endif
            </div>
        </div>

        <div class="publishing-old-cover">
            <div class="publishing-old-cover-frame">
                <img src="{{ $heroImage }}" alt="გამომცემლობა ბუკინისტები">
                <div class="publishing-old-stamp">წიგნებს მხოლოდ ტექსტებად არა, ცოცხალ ისტორიებად ვხედავთ.</div>
            </div>
        </div>
    </section>

    <section class="publishing-old-about">
        <div class="publishing-old-wrap">
            <div class="publishing-old-about-grid">
                <h2 class="publishing-old-section-title">რას აკეთებს გამომცემლობა?</h2>
                <div class="publishing-old-text">
                    <p>
                        აქ შეგიძლიათ წარადგინოთ ტექსტები, გამოცემის მიმართულებით პირველი კონსულტაცია მიიღოთ
                        და წიგნი საფუძვლიანად განიხილოთ.
                    </p>
                    <p>
                        ეს სივრცე შექმნილია იმისთვის, რომ ავტორს ჰქონდეს მკაფიო, თანამედროვე და ადამიანური ურთიერთობა გამომცემლობასთან.
                        სახელს ჩაწერთ, ტექსტს ატვირთავთ ან შეტყობინებას დატოვებთ — ჩვენ კი დაგიბრუნდებით.
                    </p>
                </div>
            </div>

            <div class="publishing-old-cards">
                <article class="publishing-old-card">
                    <span><i class="bi bi-journal-text"></i></span>
                    <h3>ტექსტის წარდგენა</h3>
                    <p>გამოგვიგზავნეთ ნაწარმოები, იდეა ან მოკლე აღწერა და ერთად განვიხილოთ გამოცემის გზა.</p>
                </article>
                <article class="publishing-old-card">
                    <span><i class="bi bi-chat-square-text"></i></span>
                    <h3>პირველი კონსულტაცია</h3>
                    <p>თუ ჯერ მხოლოდ გეგმა გაქვთ, მოგვწერეთ — დაგეხმარებით შემდეგი ნაბიჯების განსაზღვრაში.</p>
                </article>
                <article class="publishing-old-card">
                    <span><i class="bi bi-stars"></i></span>
                    <h3>ძველი და ახალი</h3>
                    <p>ვაბრუნებთ ძველ ტექსტებს მკითხველთან და ადგილს ვუთმობთ თანამედროვე ავტორებსაც.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="publishing-old-works" id="publishing-works">
        <div class="publishing-old-wrap">
            <div class="publishing-old-head">
                <h2 class="publishing-old-section-title">გამოცემები</h2>
                <p>აქ გამოჩნდება publishing-ის ჩანაწერები და სურათები, რომლებიც ადმინიდან დაემატება.</p>
            </div>

            @if($items->isNotEmpty())
                <div class="publishing-old-grid">
                    @foreach($items as $item)
                        @php
                            $cover = collect([$item->image_1, $item->image_2, $item->image_3, $item->image_4])->filter()->first();
                        @endphp
                        <article class="publishing-old-item">
                            <a class="publishing-old-item-image" href="{{ route('publishing.show', $item) }}">
                                @if($cover)
                                    <img src="{{ asset('storage/' . $cover) }}" alt="{{ $item->title }}">
                                @else
                                    <img src="{{ asset('uploads/logo/bukinistebi.ge.png') }}" alt="{{ $item->title }}">
                                @endif
                            </a>
                            <div class="publishing-old-item-body">
                                @if($item->category)
                                    <span class="publishing-old-category">{{ $item->category }}</span>
                                @endif
                                <h3>{{ $item->title }}</h3>
                                <p>{{ \Illuminate\Support\Str::limit(strip_tags($item->description), 135) }}</p>
                                <a href="{{ route('publishing.show', $item) }}" class="publishing-old-btn publishing-old-btn-light">
                                    ნახვა <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="publishing-old-empty">
                    მასალები ჯერ არ არის დამატებული. ჩანაწერების დამატების შემდეგ ისინი აქ, ძველ publishing დიზაინში გამოჩნდება.
                </div>
            @endif
        </div>
    </section>

    <section class="publishing-old-contact" id="about-publishing">
        <div class="publishing-old-wrap publishing-old-contact-grid">
            <div>
                <h2 class="publishing-old-section-title">გამოგვიგზავნეთ ტექსტი</h2>
                <p>
                    შეავსეთ ფორმა, ატვირთეთ ფაილი ან დაგვიტოვეთ შეტყობინება — თანამშრომლობის დეტალებით დაგიკავშირდებით.
                </p>
                <p class="mb-0"><strong>Email:</strong> <a href="mailto:publishing@bukinistebi.ge">publishing@bukinistebi.ge</a></p>
            </div>

            <form class="publishing-old-form" method="POST" action="{{ route('publishing.contact') }}" enctype="multipart/form-data">
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

                <button type="submit" class="publishing-old-btn publishing-old-btn-primary">
                    გაგზავნა <i class="bi bi-send"></i>
                </button>
            </form>
        </div>
    </section>
</div>
@endsection
