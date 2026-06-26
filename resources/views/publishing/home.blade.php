@extends('layouts.app')

@section('title', 'გამომცემლობა ბუკინისტები')

@section('content')
@php
    $featured = $items->first();
    $heroImage = $featured?->image_1 ? asset('storage/' . $featured->image_1) : asset('uploads/logo/bukinistebi.ge.png');
@endphp

<style>
    .pub-page {
        margin:0 calc(50% - 50vw);
        overflow:hidden;
        background:#f5f6f7;
        color:#17191c;
        font-family:liFont,sans-serif;
    }

    .pub-page * {
        box-sizing:border-box;
    }

    .pub-wrap {
        width:min(1120px,calc(100% - 32px));
        margin:0 auto;
    }

    .pub-hero {
        display:grid;
        grid-template-columns:minmax(0,1.02fr) minmax(320px,.98fr);
        min-height:620px;
        align-items:center;
        gap:56px;
        padding:68px 0 64px;
    }

    .pub-eyebrow {
        display:inline-flex;
        align-items:center;
        gap:10px;
        margin-bottom:20px;
        padding:8px 13px;
        border:1px solid #e2e5e8;
        border-radius:999px;
        background:#fff;
        color:#5b626b;
        font-size:13px;
        font-weight:800;
        box-shadow:0 12px 30px rgba(20,24,28,.04);
    }

    .pub-eyebrow span {
        width:8px;
        height:8px;
        border-radius:50%;
        background:#8d1b1f;
    }

    .pub-title,.pub-section-head h2,.pub-contact h2 {
        font-family:h1Font,liFont,sans-serif;
        letter-spacing:-.02em;
    }

    .pub-title {
        max-width:690px;
        margin:0 0 18px;
        color:#15181c;
        font-size:clamp(38px,5vw,64px);
        line-height:1.05;
        font-weight:900;
    }

    .pub-lead {
        max-width:640px;
        margin:0 0 30px;
        color:#5d6570;
        font-size:17px;
        line-height:1.85;
    }

    .pub-actions {
        display:flex;
        flex-wrap:wrap;
        gap:12px;
    }

    .pub-btn {
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap:9px;
        min-height:46px;
        padding:11px 19px;
        border-radius:999px;
        border:1px solid #17191c;
        font-size:14px;
        font-weight:900;
        text-decoration:none;
        transition:.18s ease;
    }

    .pub-btn:hover {
        transform:translateY(-1px);
        text-decoration:none;
    }

    .pub-btn-primary {
        background:#15191d;
        color:#fff;
        box-shadow:0 16px 30px rgba(21,25,29,.16);
    }

    .pub-btn-primary:hover {
        color:#fff;
        background:#8d1b1f;
        border-color:#8d1b1f;
    }

    .pub-btn-outline {
        color:#15191d;
        background:#fff;
        border-color:#d8dde2;
    }

    .pub-btn-outline:hover {
        color:#8d1b1f;
        border-color:rgba(141,27,31,.36);
        box-shadow:0 12px 24px rgba(18,22,28,.07);
    }

    .pub-visual {
        position:relative;
        min-height:430px;
        border-radius:32px;
        background:radial-gradient(circle at 82% 10%,rgba(141,27,31,.42),transparent 28%),linear-gradient(145deg,#20252b 0%,#101214 100%);
        box-shadow:0 30px 70px rgba(16,18,20,.22);
        overflow:hidden;
    }

    .pub-visual:before {
        content:'Publishing Studio';
        position:absolute;
        left:28px;
        top:26px;
        z-index:3;
        color:rgba(255,255,255,.72);
        font-size:12px;
        font-weight:900;
        letter-spacing:.08em;
        text-transform:uppercase;
    }

    .pub-visual-main {
        position:absolute;
        left:54px;
        right:54px;
        top:86px;
        bottom:72px;
        border-radius:24px;
        background:#f8f9fa;
        box-shadow:0 24px 48px rgba(0,0,0,.25);
        overflow:visible;
    }

    .pub-visual-main:before {
        content:'იდეა → რედაქტურა → წიგნი';
        position:absolute;
        left:30px;
        right:30px;
        top:28px;
        color:#17191c;
        font-family:h1Font,liFont,sans-serif;
        font-size:27px;
        font-weight:900;
        line-height:1.22;
    }

    .pub-visual-main:after {
        content:'';
        position:absolute;
        left:30px;
        right:30px;
        top:106px;
        height:7px;
        border-radius:999px;
        background:#dfe3e7;
        box-shadow:0 19px 0 #edf0f2,0 38px 0 #edf0f2;
    }

    .pub-book-stack {
        position:absolute;
        left:34px;
        bottom:28px;
        width:94px;
        height:142px;
        border-radius:8px 16px 16px 8px;
        background:#15191d;
        box-shadow:86px -18px 0 #8d1b1f,172px 8px 0 #dfe3e7,0 18px 28px rgba(0,0,0,.18);
        transform:rotate(-7deg);
    }

    .pub-note {
        position:absolute;
        left:28px;
        right:28px;
        bottom:24px;
        padding:16px 18px;
        border-radius:18px;
        border:1px solid rgba(255,255,255,.12);
        background:rgba(255,255,255,.08);
        color:#fff;
        backdrop-filter:blur(12px);
    }

    .pub-note strong {
        display:block;
        margin-bottom:5px;
        font-size:15px;
    }

    .pub-note p {
        margin:0;
        color:rgba(255,255,255,.68);
        font-size:13px;
        line-height:1.55;
    }

    .pub-band {
        padding:64px 0;
        border-top:1px solid #e3e7eb;
        border-bottom:1px solid #e3e7eb;
        background:#eef0f2;
    }

    .pub-section-head {
        display:flex;
        justify-content:space-between;
        gap:28px;
        align-items:flex-end;
        margin-bottom:26px;
    }

    .pub-section-head h2 {
        margin:0;
        color:#17191c;
        font-size:clamp(30px,3.4vw,44px);
        line-height:1.1;
        font-weight:900;
    }

    .pub-section-head p {
        max-width:560px;
        margin:0;
        color:#606873;
        font-size:15px;
        line-height:1.75;
    }

    .pub-services {
        display:grid;
        grid-template-columns:repeat(4,minmax(0,1fr));
        gap:18px;
    }

    .pub-service {
        min-height:190px;
        padding:24px;
        border:1px solid #dfe3e8;
        border-radius:22px;
        background:#fff;
        box-shadow:0 18px 40px rgba(18,22,28,.06);
    }

    .pub-service i {
        display:inline-flex;
        align-items:center;
        justify-content:center;
        width:40px;
        height:40px;
        margin-bottom:18px;
        border-radius:13px;
        background:#15191d;
        color:#fff;
        font-size:18px;
    }

    .pub-service h3 {
        margin:0 0 10px;
        color:#17191c;
        font-size:19px;
        font-weight:900;
    }

    .pub-service p {
        margin:0;
        color:#68707a;
        line-height:1.7;
    }

    .pub-showcase {
        padding:70px 0;
        background:#f8f9fa;
    }

    .pub-grid {
        display:grid;
        grid-template-columns:repeat(3,minmax(0,1fr));
        gap:22px;
    }

    .pub-item {
        overflow:hidden;
        border:1px solid #e0e4e8;
        border-radius:22px;
        background:#fff;
        box-shadow:0 18px 42px rgba(18,22,28,.07);
        transition:.18s ease;
    }

    .pub-item:hover {
        transform:translateY(-4px);
        box-shadow:0 24px 54px rgba(18,22,28,.11);
    }

    .pub-item-image {
        display:block;
        aspect-ratio:4/3;
        background:#edf0f2;
        overflow:hidden;
    }

    .pub-item-image img {
        width:100%;
        height:100%;
        object-fit:cover;
        transition:.25s ease;
    }

    .pub-item:hover .pub-item-image img {
        transform:scale(1.04);
    }

    .pub-item-body {
        padding:20px;
    }

    .pub-category {
        display:inline-flex;
        margin-bottom:10px;
        color:#8d1b1f;
        font-size:13px;
        font-weight:900;
    }

    .pub-item h3 {
        margin:0 0 10px;
        color:#17191c;
        font-size:20px;
        font-weight:900;
    }

    .pub-item p {
        min-height:76px;
        margin:0 0 14px;
        color:#68707a;
        line-height:1.65;
    }

    .pub-empty {
        padding:28px;
        border:1px dashed #cbd1d8;
        border-radius:20px;
        background:#fff;
        color:#68707a;
    }

    .pub-contact {
        padding:70px 0 78px;
        background:#101214;
        color:#fff;
    }

    .pub-contact-grid {
        display:grid;
        grid-template-columns:minmax(0,.85fr) minmax(320px,1.15fr);
        gap:38px;
        align-items:start;
    }

    .pub-contact h2 {
        margin:0 0 16px;
        font-size:38px;
        font-weight:900;
    }

    .pub-contact p {
        color:rgba(255,255,255,.72);
        line-height:1.8;
    }

    .pub-contact a {
        color:#fff;
    }

    .pub-form {
        padding:26px;
        border:1px solid rgba(255,255,255,.12);
        border-radius:22px;
        background:rgba(255,255,255,.07);
        box-shadow:0 20px 50px rgba(0,0,0,.2);
    }

    .pub-form .form-label {
        color:rgba(255,255,255,.86);
        font-weight:700;
    }

    .pub-form .form-control {
        min-height:46px;
        border:0;
        border-radius:12px;
        background:#f5f6f7;
    }

    .pub-form textarea.form-control {
        min-height:150px;
    }

    @media(max-width:991px) {
        .pub-hero,.pub-contact-grid {
            grid-template-columns:1fr;
        }

        .pub-hero {
            min-height:0;
            padding-top:42px;
        }

        .pub-visual {
            max-width:430px;
            width:100%;
        }

        .pub-services,.pub-grid {
            grid-template-columns:1fr 1fr;
        }

    }

    @media(max-width:575px) {
        .pub-wrap {
            width:min(100% - 24px,1120px);
        }

        .pub-hero {
            gap:28px;
            padding:34px 0 46px;
        }

        .pub-title {
            font-size:34px;
        }

        .pub-visual {
            min-height:340px;
            border-radius:24px;
        }

        .pub-visual-main {
            left:24px;
            right:24px;
            top:76px;
            bottom:68px;
        }

        .pub-visual-main:before {
            font-size:21px;
        }

        .pub-section-head {
            display:block;
        }

        .pub-section-head h2 {
            margin-bottom:12px;
        }

        .pub-services,.pub-grid {
            grid-template-columns:1fr;
        }

    }
</style>


<div class="pub-page">
    <section class="pub-wrap pub-hero">
        <div>
            <div class="pub-eyebrow"><span></span> გამომცემლობა ბუკინისტები</div>
            <h1 class="pub-title">წიგნის მომზადება, გამოცემა და მკითხველამდე მიტანა</h1>
            <p class="pub-lead">
                ავტორებთან, მთარგმნელებთან და ორგანიზაციებთან ერთად ვამზადებთ წიგნს იდეიდან დაბეჭდილ გამოცემამდე:
                ტექსტი, დიზაინი, დაკაბადონება, ბეჭდვა და გავრცელება.
            </p>
            <div class="pub-actions">
                <a href="#about-publishing" class="pub-btn pub-btn-primary">
                    <i class="bi bi-envelope"></i> დაგვიკავშირდით
                </a>
                @if($items->isNotEmpty())
                    <a href="#publishing-works" class="pub-btn pub-btn-outline">
                        <i class="bi bi-grid"></i> ნამუშევრები
                    </a>
                @endif
            </div>
        </div>

        <div class="pub-visual" aria-hidden="true">
            <div class="pub-visual-main">
                <span class="pub-book-stack"></span>
            </div>
            <div class="pub-note">
                <strong>სრული საგამომცემლო პროცესი</strong>
                <p>ერთი სივრცე ტექსტისთვის, ვიზუალისთვის, ბეჭდვისთვის და წიგნის მაღაზიამდე მისატანად.</p>
            </div>
        </div>
    </section>

    <section class="pub-band">
        <div class="pub-wrap">
            <div class="pub-section-head">
                <h2>რას ვაკეთებთ</h2>
                <p>შიდა ბლოკები ცალკე ნაწილებადაა დალაგებული, რომ გვერდი publishing-ის გვერდს ჰგავდეს და არა ჩვეულებრივ წიგნების სიას.</p>
            </div>

            <div class="pub-services">
                <article class="pub-service">
                    <i class="bi bi-pencil-square"></i>
                    <h3>რედაქტირება</h3>
                    <p>ტექსტის წაკითხვა, კორექტურა და გამოცემისთვის საჭირო სტრუქტურის მოწესრიგება.</p>
                </article>
                <article class="pub-service">
                    <i class="bi bi-layout-text-window-reverse"></i>
                    <h3>დაკაბადონება</h3>
                    <p>შიდა გვერდების გამართული ფორმატი, სათაურები, თავები და ბეჭდვისთვის მზადება.</p>
                </article>
                <article class="pub-service">
                    <i class="bi bi-palette"></i>
                    <h3>ყდის დიზაინი</h3>
                    <p>წიგნის ხასიათზე მორგებული ვიზუალი, რომელსაც თაროზეც დამოუკიდებლად უჭირავს ადგილი.</p>
                </article>
                <article class="pub-service">
                    <i class="bi bi-printer"></i>
                    <h3>ბეჭდვა</h3>
                    <p>ფაილების მომზადება, ბეჭდვის პროცესის კოორდინაცია და საბოლოო ტირაჟის მიღება.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="pub-showcase" id="publishing-works">
        <div class="pub-wrap">
            <div class="pub-section-head">
                <h2>ადმინიდან ატვირთული მასალები</h2>
                <p>აქ ავტომატურად გამოჩნდება ის ჩანაწერები და სურათები, რასაც ბუკინისტების ადმინში publishing-ისთვის ტვირთავ.</p>
            </div>

            @if($items->isNotEmpty())
                <div class="pub-grid">
                    @foreach($items as $item)
                        @php
                            $cover = collect([$item->image_1, $item->image_2, $item->image_3, $item->image_4])->filter()->first();
                        @endphp
                        <article class="pub-item">
                            <a class="pub-item-image" href="{{ $item->shop_url ?: route('publishing.show', $item->id) }}">
                                @if($cover)
                                    <img src="{{ asset('storage/' . $cover) }}" alt="{{ $item->title }}">
                                @else
                                    <img src="{{ asset('uploads/logo/bukinistebi.ge.png') }}" alt="{{ $item->title }}">
                                @endif
                            </a>
                            <div class="pub-item-body">
                                @if($item->category)
                                    <span class="pub-category">{{ $item->category }}</span>
                                @endif
                                <h3>{{ $item->title }}</h3>
                                <p>{{ \Illuminate\Support\Str::limit(strip_tags($item->description), 135) }}</p>
                                <a href="{{ $item->shop_url ?: route('publishing.show', $item->id) }}" class="pub-btn pub-btn-outline">
                                    მაღაზიაში ნახვა <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="pub-empty">
                    მასალები ჯერ არ ჩანს. როცა ადმინიდან publishing ჩანაწერს დაამატებ, ეს ბლოკი ავტომატურად შეივსება.
                </div>
            @endif
        </div>
    </section>

    <section class="pub-contact" id="about-publishing">
        <div class="pub-wrap pub-contact-grid">
            <div>
                <h2>დაგვიკავშირდით</h2>
                <p>
                    მოგვწერეთ წიგნის იდეა, მოკლე აღწერა ან გამოგვიგზავნეთ ფაილი. დეტალებს დაგიბრუნებთ publishing@bukinistebi.ge-დან.
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

                <button type="submit" class="pub-btn pub-btn-primary">
                    გაგზავნა <i class="bi bi-send"></i>
                </button>
            </form>
        </div>
    </section>
</div>
@endsection
