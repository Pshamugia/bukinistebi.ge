@extends('layouts.app')

@section('title', 'გამომცემლობა ბუკინისტები')

@section('content')
@php
    $featured = $items->first();
    $heroImage = $featured?->image_1 ? asset('storage/' . $featured->image_1) : asset('uploads/logo/bukinistebi.ge.png');
@endphp

<style>
    .pub-page {
        margin: 0 calc(50% - 50vw);
        background: #f7f4ef;
        color: #25201b;
    }

    .pub-wrap {
        width: min(1180px, calc(100% - 32px));
        margin: 0 auto;
    }

    .pub-hero {
        display: grid;
        grid-template-columns: minmax(0, 1.05fr) minmax(300px, .95fr);
        min-height: 560px;
        align-items: center;
        gap: 42px;
        padding: 52px 0 44px;
    }

    .pub-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
        color: #8f5b22;
        font-weight: 800;
        letter-spacing: 0;
    }

    .pub-eyebrow span {
        width: 38px;
        height: 1px;
        background: #b8863b;
    }

    .pub-title {
        max-width: 720px;
        margin: 0 0 20px;
        font-size: 56px;
        line-height: 1.08;
        font-weight: 900;
    }

    .pub-lead {
        max-width: 650px;
        margin: 0 0 28px;
        font-size: 18px;
        line-height: 1.85;
        color: #5a5148;
    }

    .pub-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .pub-btn {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        min-height: 46px;
        padding: 11px 18px;
        border-radius: 4px;
        border: 1px solid #25201b;
        font-weight: 800;
        text-decoration: none;
        transition: .2s ease;
    }

    .pub-btn-primary {
        background: #25201b;
        color: #fff;
    }

    .pub-btn-primary:hover {
        color: #fff;
        background: #8f2d22;
        border-color: #8f2d22;
    }

    .pub-btn-outline {
        color: #25201b;
        background: transparent;
    }

    .pub-btn-outline:hover {
        color: #8f2d22;
        border-color: #8f2d22;
    }

    .pub-visual {
        position: relative;
        min-height: 430px;
    }

    .pub-visual-main {
        position: absolute;
        inset: 0 42px 38px 0;
        border-radius: 4px;
        background: #211d19;
        box-shadow: 22px 24px 0 #e4d5bd;
        overflow: hidden;
    }

    .pub-visual-main img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: .88;
    }

    .pub-note {
        position: absolute;
        right: 0;
        bottom: 0;
        max-width: 310px;
        padding: 22px;
        border-radius: 4px;
        background: #fff;
        border: 1px solid #eadfce;
        box-shadow: 0 16px 34px rgba(58, 43, 25, .14);
    }

    .pub-note strong {
        display: block;
        margin-bottom: 8px;
        font-size: 18px;
    }

    .pub-note p {
        margin: 0;
        color: #6a5f54;
        line-height: 1.7;
    }

    .pub-band {
        padding: 54px 0;
        background: #fff;
    }

    .pub-section-head {
        display: flex;
        justify-content: space-between;
        gap: 24px;
        align-items: end;
        margin-bottom: 24px;
    }

    .pub-section-head h2 {
        margin: 0;
        font-size: 34px;
        font-weight: 900;
    }

    .pub-section-head p {
        max-width: 520px;
        margin: 0;
        color: #665d55;
        line-height: 1.7;
    }

    .pub-services {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1px;
        background: #e7ddd0;
        border: 1px solid #e7ddd0;
    }

    .pub-service {
        min-height: 210px;
        padding: 26px;
        background: #fff;
    }

    .pub-service i {
        display: inline-flex;
        margin-bottom: 18px;
        font-size: 30px;
        color: #9f6426;
    }

    .pub-service h3 {
        margin: 0 0 10px;
        font-size: 19px;
        font-weight: 900;
    }

    .pub-service p {
        margin: 0;
        color: #6a5f54;
        line-height: 1.7;
    }

    .pub-showcase {
        padding: 58px 0;
    }

    .pub-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 22px;
    }

    .pub-item {
        background: #fff;
        border: 1px solid #e8decf;
        border-radius: 4px;
        overflow: hidden;
    }

    .pub-item-image {
        display: block;
        aspect-ratio: 4 / 3;
        background: #eee4d6;
        overflow: hidden;
    }

    .pub-item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .25s ease;
    }

    .pub-item:hover .pub-item-image img {
        transform: scale(1.04);
    }

    .pub-item-body {
        padding: 20px;
    }

    .pub-category {
        display: inline-flex;
        margin-bottom: 10px;
        color: #8f5b22;
        font-size: 13px;
        font-weight: 800;
    }

    .pub-item h3 {
        margin: 0 0 10px;
        font-size: 20px;
        font-weight: 900;
    }

    .pub-item p {
        min-height: 76px;
        margin: 0 0 14px;
        color: #625850;
        line-height: 1.65;
    }

    .pub-empty {
        padding: 28px;
        border: 1px dashed #cdbb9e;
        background: rgba(255,255,255,.65);
        color: #6a5f54;
    }

    .pub-contact {
        padding: 56px 0 64px;
        background: #27211c;
        color: #fff;
    }

    .pub-contact-grid {
        display: grid;
        grid-template-columns: minmax(0, .85fr) minmax(320px, 1.15fr);
        gap: 38px;
        align-items: start;
    }

    .pub-contact h2 {
        margin: 0 0 16px;
        font-size: 34px;
        font-weight: 900;
    }

    .pub-contact p {
        color: rgba(255,255,255,.74);
        line-height: 1.8;
    }

    .pub-contact a {
        color: #f2c46d;
    }

    .pub-form {
        padding: 26px;
        border: 1px solid rgba(255,255,255,.16);
        background: rgba(255,255,255,.06);
        border-radius: 4px;
    }

    .pub-form .form-label {
        color: rgba(255,255,255,.86);
        font-weight: 700;
    }

    .pub-form .form-control {
        min-height: 45px;
        border-radius: 4px;
        border: 0;
    }

    .pub-form textarea.form-control {
        min-height: 150px;
    }

    @media (max-width: 991px) {
        .pub-hero,
        .pub-contact-grid {
            grid-template-columns: 1fr;
        }

        .pub-title {
            font-size: 42px;
        }

        .pub-services,
        .pub-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 575px) {
        .pub-wrap {
            width: min(100% - 24px, 1180px);
        }

        .pub-hero {
            min-height: 0;
            padding-top: 32px;
        }

        .pub-title {
            font-size: 34px;
        }

        .pub-visual {
            min-height: 330px;
        }

        .pub-visual-main {
            inset: 0 18px 42px 0;
            box-shadow: 14px 16px 0 #e4d5bd;
        }

        .pub-section-head {
            display: block;
        }

        .pub-section-head h2 {
            margin-bottom: 12px;
            font-size: 28px;
        }

        .pub-services,
        .pub-grid {
            grid-template-columns: 1fr;
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
                <img src="{{ $heroImage }}" alt="">
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
                            <a class="pub-item-image" href="{{ route('publishing.show', $item->id) }}">
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
                                <a href="{{ route('publishing.show', $item->id) }}" class="pub-btn pub-btn-outline">
                                    ნახვა <i class="bi bi-arrow-right"></i>
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
