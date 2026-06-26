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


<style>
    /* Publishing home redesign override: appended intentionally to avoid merge conflicts with the existing view markup. */
    .pub-page {
        margin: 0 calc(50% - 50vw) !important;
        overflow: hidden;
        background:
            radial-gradient(circle at 8% 8%, rgba(141, 27, 31, .08), transparent 26%),
            linear-gradient(180deg, #fff 0%, #f4f5f6 42%, #fff 100%) !important;
        color: #17191c !important;
        font-family: liFont, sans-serif !important;
    }

    .pub-page * { box-sizing: border-box; }
    .pub-wrap { width: min(1180px, calc(100% - 32px)) !important; }
    .pub-hero { position: relative; display: grid !important; grid-template-columns: minmax(0, 1.04fr) minmax(320px, .96fr) !important; min-height: 620px !important; align-items: center; gap: 54px !important; padding: 70px 0 64px !important; }
    .pub-eyebrow { display: inline-flex !important; align-items: center; gap: 12px !important; margin-bottom: 22px !important; padding: 9px 14px !important; border: 1px solid #dfe2e6 !important; border-radius: 999px !important; background: rgba(255,255,255,.82) !important; color: #626972 !important; font-size: 14px !important; font-weight: 800 !important; box-shadow: 0 14px 35px rgba(18, 22, 28, .06) !important; }
    .pub-eyebrow span { width: 8px !important; height: 8px !important; border-radius: 50% !important; background: #8d1b1f !important; box-shadow: 0 0 0 6px rgba(141, 27, 31, .09) !important; }

    .pub-title, .pub-section-head h2, .pub-contact h2 { font-family: h1Font, liFont, sans-serif !important; letter-spacing: -.03em; }
    .pub-title { max-width: 780px !important; margin: 0 0 22px !important; font-size: clamp(42px, 6vw, 76px) !important; line-height: 1.02 !important; font-weight: 900 !important; color: #111315 !important; }
    .pub-lead { max-width: 670px !important; margin: 0 0 34px !important; font-size: 18px !important; line-height: 1.9 !important; color: #5f6670 !important; }

    .pub-btn { display: inline-flex !important; align-items: center; justify-content: center; gap: 10px !important; min-height: 48px !important; padding: 12px 20px !important; border-radius: 999px !important; border: 1px solid #151719 !important; font-weight: 900 !important; text-decoration: none !important; transition: transform .22s ease, box-shadow .22s ease, background .22s ease, color .22s ease, border-color .22s ease !important; }
    .pub-btn:hover { transform: translateY(-2px); text-decoration: none !important; }
    .pub-btn-primary { background: #151719 !important; color: #fff !important; box-shadow: 0 18px 34px rgba(17, 19, 21, .18) !important; }
    .pub-btn-primary:hover { background: #8d1b1f !important; border-color: #8d1b1f !important; box-shadow: 0 20px 38px rgba(141, 27, 31, .23) !important; }
    .pub-btn-outline { color: #151719 !important; background: #fff !important; border-color: #d8dce1 !important; }
    .pub-btn-outline:hover { color: #8d1b1f !important; border-color: rgba(141, 27, 31, .34) !important; box-shadow: 0 16px 30px rgba(25, 29, 35, .08) !important; }

    .pub-visual { min-height: 500px !important; border-radius: 34px !important; background: linear-gradient(145deg, #22262c 0%, #111315 100%) !important; box-shadow: 0 28px 70px rgba(17, 19, 21, .22) !important; overflow: hidden !important; }
    .pub-visual-main { inset: 48px 46px 132px 46px !important; border-radius: 24px !important; background: rgba(255,255,255,.94) !important; box-shadow: 0 26px 55px rgba(0,0,0,.22) !important; }
    .pub-visual-main::before { content: 'Publishing Studio'; position: absolute; left: 24px; top: 24px; z-index: 2; color: #8d1b1f; font-weight: 900; font-family: liFont, sans-serif; }
    .pub-visual-main::after { content: 'იდეიდან დასრულებულ წიგნამდე'; position: absolute; left: 24px; right: 24px; top: 58px; z-index: 2; color: #17191c; font-family: h1Font, liFont, sans-serif; font-size: 28px; line-height: 1.25; font-weight: 900; }
    .pub-visual-main img { width: 118px !important; height: 188px !important; object-fit: cover !important; position: absolute; left: 58px; bottom: -104px; opacity: 1 !important; border-radius: 8px 16px 16px 8px; box-shadow: 122px -18px 0 #8d1b1f, 244px 10px 0 #f6f7f8, 0 24px 35px rgba(0,0,0,.22) !important; filter: grayscale(.12) contrast(.96); transform: rotate(-8deg); }
    .pub-note { right: 34px !important; bottom: 28px !important; max-width: 270px !important; padding: 18px 20px !important; border-radius: 18px !important; background: rgba(255,255,255,.1) !important; border: 1px solid rgba(255,255,255,.14) !important; color: #fff !important; backdrop-filter: blur(12px); }
    .pub-note p { color: rgba(255,255,255,.72) !important; }

    .pub-band { padding: 72px 0 !important; background: #fff !important; border-top: 1px solid #eceff2; border-bottom: 1px solid #eceff2; }
    .pub-section-head { gap: 24px !important; margin-bottom: 30px !important; }
    .pub-section-head h2 { font-size: clamp(30px, 4vw, 48px) !important; font-weight: 900 !important; color: #151719 !important; }
    .pub-section-head p { color: #68707a !important; line-height: 1.75 !important; }
    .pub-services { gap: 16px !important; background: transparent !important; border: 0 !important; }
    .pub-service { min-height: 224px !important; padding: 28px !important; border: 1px solid #e3e7eb !important; border-radius: 24px !important; background: linear-gradient(180deg, #fff, #f8f9fa) !important; box-shadow: 0 18px 45px rgba(16, 24, 40, .06) !important; }
    .pub-service i, .pub-category { color: #8d1b1f !important; }
    .pub-service h3, .pub-item h3 { color: #17191c !important; font-weight: 900 !important; }
    .pub-service p, .pub-item p { color: #69717b !important; }
    .pub-showcase { padding: 76px 0 !important; }
    .pub-grid { gap: 24px !important; }
    .pub-item { border: 1px solid #e2e6ea !important; border-radius: 26px !important; box-shadow: 0 20px 55px rgba(16, 24, 40, .08) !important; transition: transform .22s ease, box-shadow .22s ease !important; }
    .pub-item:hover { transform: translateY(-6px); box-shadow: 0 28px 70px rgba(16, 24, 40, .14) !important; }
    .pub-item-image { background: #eef0f2 !important; }
    .pub-item-body { padding: 22px !important; }
    .pub-contact { padding: 76px 0 84px !important; background: #111315 !important; color: #fff !important; }
    .pub-form { padding: 28px !important; border: 1px solid rgba(255,255,255,.12) !important; background: rgba(255,255,255,.06) !important; border-radius: 26px !important; box-shadow: 0 24px 60px rgba(0,0,0,.22) !important; }
    .pub-form .form-control { min-height: 48px !important; border-radius: 14px !important; border: 1px solid rgba(255,255,255,.12) !important; background: rgba(255,255,255,.96) !important; }

    @media (max-width: 991px) { .pub-hero { grid-template-columns: 1fr !important; } .pub-services, .pub-grid { grid-template-columns: 1fr 1fr !important; } .pub-visual { min-height: 470px !important; } }
    @media (max-width: 575px) { .pub-hero { min-height: 0 !important; padding: 38px 0 48px !important; gap: 30px !important; } .pub-services, .pub-grid { grid-template-columns: 1fr !important; } .pub-visual { min-height: 420px !important; border-radius: 24px !important; } .pub-visual-main { left: 22px !important; right: 22px !important; top: 24px !important; } .pub-note { left: 22px !important; right: 22px !important; max-width: none !important; } }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const shopUrls = {
            @foreach($items as $item)
                "{{ route('publishing.show', $item->id) }}": @json($item->shop_url ?: null),
            @endforeach
        };

        document.querySelectorAll('.pub-item').forEach(function (card) {
            const imageLink = card.querySelector('.pub-item-image');
            const button = card.querySelector('.pub-item-body .pub-btn');
            if (!button) return;

            button.childNodes.forEach(function (node) {
                if (node.nodeType === Node.TEXT_NODE) {
                    node.nodeValue = 'მაღაზიაში ნახვა ';
                }
            });

            const shopUrl = shopUrls[button.href] || null;
            if (shopUrl) {
                button.href = shopUrl;
                if (imageLink) imageLink.href = shopUrl;
            }
        });
    });
</script>
@endsection
