@extends('layouts.app')

@section('title', 'გამომცემლობა ბუკინისტები')

@section('content')
<style>
    .pub-page {
        margin: 0 calc(50% - 50vw);
        overflow: hidden;
        background:
            radial-gradient(circle at 8% 8%, rgba(141, 27, 31, .08), transparent 26%),
            linear-gradient(180deg, #ffffff 0%, #f4f5f6 42%, #ffffff 100%);
        color: #17191c;
        font-family: liFont, sans-serif;
    }

    .pub-page * { box-sizing: border-box; }

    .pub-wrap {
        width: min(1180px, calc(100% - 32px));
        margin: 0 auto;
    }

    .pub-hero {
        position: relative;
        display: grid;
        grid-template-columns: minmax(0, 1.04fr) minmax(320px, .96fr);
        min-height: 620px;
        align-items: center;
        gap: 54px;
        padding: 70px 0 64px;
    }

    .pub-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 22px;
        padding: 9px 14px;
        border: 1px solid #dfe2e6;
        border-radius: 999px;
        background: rgba(255,255,255,.82);
        color: #626972;
        font-size: 14px;
        font-weight: 800;
        letter-spacing: .01em;
        box-shadow: 0 14px 35px rgba(18, 22, 28, .06);
    }

    .pub-eyebrow span {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #8d1b1f;
        box-shadow: 0 0 0 6px rgba(141, 27, 31, .09);
    }

    .pub-title,
    .pub-section-head h2,
    .pub-contact h2 {
        font-family: h1Font, liFont, sans-serif;
        letter-spacing: -.03em;
    }

    .pub-title {
        max-width: 780px;
        margin: 0 0 22px;
        font-size: clamp(42px, 6vw, 76px);
        line-height: 1.02;
        font-weight: 900;
        color: #111315;
    }

    .pub-lead {
        max-width: 670px;
        margin: 0 0 34px;
        font-size: 18px;
        line-height: 1.9;
        color: #5f6670;
    }

    .pub-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 13px;
    }

    .pub-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-height: 48px;
        padding: 12px 20px;
        border-radius: 999px;
        border: 1px solid #151719;
        font-weight: 900;
        text-decoration: none;
        transition: transform .22s ease, box-shadow .22s ease, background .22s ease, color .22s ease, border-color .22s ease;
    }

    .pub-btn:hover { transform: translateY(-2px); text-decoration: none; }

    .pub-btn-primary {
        background: #151719;
        color: #fff;
        box-shadow: 0 18px 34px rgba(17, 19, 21, .18);
    }

    .pub-btn-primary:hover {
        color: #fff;
        background: #8d1b1f;
        border-color: #8d1b1f;
        box-shadow: 0 20px 38px rgba(141, 27, 31, .23);
    }

    .pub-btn-outline {
        color: #151719;
        background: #fff;
        border-color: #d8dce1;
    }

    .pub-btn-outline:hover {
        color: #8d1b1f;
        border-color: rgba(141, 27, 31, .34);
        box-shadow: 0 16px 30px rgba(25, 29, 35, .08);
    }

    .pub-visual {
        position: relative;
        min-height: 500px;
        border-radius: 34px;
        background: linear-gradient(145deg, #22262c 0%, #111315 100%);
        box-shadow: 0 28px 70px rgba(17, 19, 21, .22);
        overflow: hidden;
    }

    .pub-visual::before {
        content: '';
        position: absolute;
        inset: 22px;
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 26px;
    }

    .pub-visual::after {
        content: '';
        position: absolute;
        width: 330px;
        height: 330px;
        right: -130px;
        top: -120px;
        border-radius: 50%;
        background: rgba(255,255,255,.08);
    }

    .pub-studio-card {
        position: absolute;
        left: 46px;
        right: 46px;
        top: 48px;
        padding: 24px;
        border-radius: 24px;
        background: rgba(255,255,255,.94);
        color: #17191c;
        box-shadow: 0 26px 55px rgba(0,0,0,.22);
    }

    .pub-studio-card small {
        display: block;
        margin-bottom: 10px;
        color: #8d1b1f;
        font-weight: 900;
    }

    .pub-studio-card strong {
        display: block;
        font-family: h1Font, liFont, sans-serif;
        font-size: 28px;
        line-height: 1.2;
    }

    .pub-book-art {
        position: absolute;
        left: 62px;
        right: 62px;
        bottom: 66px;
        height: 220px;
    }

    .pub-book {
        position: absolute;
        bottom: 0;
        width: 118px;
        height: 188px;
        border-radius: 8px 16px 16px 8px;
        background: #f6f7f8;
        box-shadow: 0 24px 35px rgba(0,0,0,.22);
        transform-origin: bottom center;
    }

    .pub-book::before {
        content: '';
        position: absolute;
        left: 16px;
        top: 0;
        bottom: 0;
        width: 1px;
        background: rgba(0,0,0,.12);
    }

    .pub-book::after {
        content: '';
        position: absolute;
        left: 30px;
        right: 18px;
        top: 34px;
        height: 8px;
        border-radius: 10px;
        background: currentColor;
        box-shadow: 0 24px 0 currentColor, 0 48px 0 currentColor;
        opacity: .22;
    }

    .pub-book.one { left: 10px; color: #151719; transform: rotate(-9deg); }
    .pub-book.two { left: 128px; color: #8d1b1f; height: 214px; background: #8d1b1f; transform: rotate(1deg); }
    .pub-book.two::before, .pub-book.two::after { background: rgba(255,255,255,.72); color: #fff; }
    .pub-book.three { left: 248px; color: #555d66; height: 172px; transform: rotate(8deg); }

    .pub-note {
        position: absolute;
        right: 34px;
        bottom: 28px;
        max-width: 270px;
        padding: 18px 20px;
        border-radius: 18px;
        background: rgba(255,255,255,.1);
        border: 1px solid rgba(255,255,255,.14);
        color: #fff;
        backdrop-filter: blur(12px);
    }

    .pub-note strong { display: block; margin-bottom: 6px; font-size: 16px; }
    .pub-note p { margin: 0; color: rgba(255,255,255,.72); line-height: 1.65; font-size: 14px; }

    .pub-band { padding: 72px 0; background: #fff; border-top: 1px solid #eceff2; border-bottom: 1px solid #eceff2; }

    .pub-section-head {
        display: flex;
        justify-content: space-between;
        gap: 24px;
        align-items: end;
        margin-bottom: 30px;
    }

    .pub-section-head h2 { margin: 0; font-size: clamp(30px, 4vw, 48px); font-weight: 900; color: #151719; }
    .pub-section-head p { max-width: 560px; margin: 0; color: #68707a; line-height: 1.75; }

    .pub-services { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; }

    .pub-service {
        min-height: 224px;
        padding: 28px;
        border: 1px solid #e3e7eb;
        border-radius: 24px;
        background: linear-gradient(180deg, #fff, #f8f9fa);
        box-shadow: 0 18px 45px rgba(16, 24, 40, .06);
    }

    .pub-service i { display: inline-flex; margin-bottom: 18px; font-size: 28px; color: #8d1b1f; }
    .pub-service h3 { margin: 0 0 10px; font-size: 19px; font-weight: 900; color: #17191c; }
    .pub-service p { margin: 0; color: #69717b; line-height: 1.75; }

    .pub-showcase { padding: 76px 0; }
    .pub-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 24px; }

    .pub-item {
        background: #fff;
        border: 1px solid #e2e6ea;
        border-radius: 26px;
        overflow: hidden;
        box-shadow: 0 20px 55px rgba(16, 24, 40, .08);
        transition: transform .22s ease, box-shadow .22s ease;
    }

    .pub-item:hover { transform: translateY(-6px); box-shadow: 0 28px 70px rgba(16, 24, 40, .14); }
    .pub-item-image { display: block; aspect-ratio: 4 / 3; background: #eef0f2; overflow: hidden; }
    .pub-item-image img { width: 100%; height: 100%; object-fit: cover; transition: transform .3s ease; }
    .pub-item:hover .pub-item-image img { transform: scale(1.05); }
    .pub-item-body { padding: 22px; }
    .pub-category { display: inline-flex; margin-bottom: 11px; color: #8d1b1f; font-size: 13px; font-weight: 900; }
    .pub-item h3 { margin: 0 0 10px; font-size: 21px; font-weight: 900; color: #17191c; }
    .pub-item p { min-height: 78px; margin: 0 0 16px; color: #67707a; line-height: 1.7; }
    .pub-empty { padding: 30px; border: 1px dashed #cfd5dc; border-radius: 22px; background: #fff; color: #68707a; }

    .pub-contact {
        padding: 76px 0 84px;
        background: #111315;
        color: #fff;
    }

    .pub-contact-grid { display: grid; grid-template-columns: minmax(0, .85fr) minmax(320px, 1.15fr); gap: 42px; align-items: start; }
    .pub-contact h2 { margin: 0 0 16px; font-size: clamp(30px, 4vw, 46px); font-weight: 900; }
    .pub-contact p { color: rgba(255,255,255,.72); line-height: 1.85; }
    .pub-contact a { color: #fff; text-decoration-color: rgba(255,255,255,.35); }

    .pub-form { padding: 28px; border: 1px solid rgba(255,255,255,.12); background: rgba(255,255,255,.06); border-radius: 26px; box-shadow: 0 24px 60px rgba(0,0,0,.22); }
    .pub-form .form-label { color: rgba(255,255,255,.86); font-weight: 800; }
    .pub-form .form-control { min-height: 48px; border-radius: 14px; border: 1px solid rgba(255,255,255,.12); background: rgba(255,255,255,.96); }
    .pub-form textarea.form-control { min-height: 150px; }

    @media (max-width: 991px) {
        .pub-hero, .pub-contact-grid { grid-template-columns: 1fr; }
        .pub-services, .pub-grid { grid-template-columns: 1fr 1fr; }
        .pub-visual { min-height: 470px; }
    }

    @media (max-width: 575px) {
        .pub-wrap { width: min(100% - 24px, 1180px); }
        .pub-hero { min-height: 0; padding: 38px 0 48px; gap: 30px; }
        .pub-services, .pub-grid { grid-template-columns: 1fr; }
        .pub-section-head { display: block; }
        .pub-section-head h2 { margin-bottom: 12px; }
        .pub-visual { min-height: 420px; border-radius: 24px; }
        .pub-studio-card { left: 22px; right: 22px; top: 24px; }
        .pub-book-art { left: 22px; right: 22px; transform: scale(.82); transform-origin: left bottom; }
        .pub-note { left: 22px; right: 22px; max-width: none; }
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
            <div class="pub-studio-card">
                <small>Publishing Studio</small>
                <strong>იდეიდან დასრულებულ წიგნამდე — სოლიდურად, მშვიდად, მკაფიოდ.</strong>
            </div>
            <div class="pub-book-art">
                <span class="pub-book one"></span>
                <span class="pub-book two"></span>
                <span class="pub-book three"></span>
            </div>
            <div class="pub-note">
                <strong>წიგნის შექმნის პროცესი</strong>
                <p>რედაქტურა, დიზაინი, ბეჭდვა და მაღაზიამდე მიტანა ერთ სწორ ხაზზე.</p>
            </div>
        </div>
    </section>

    <section class="pub-band">
        <div class="pub-wrap">
            <div class="pub-section-head">
                <h2>რას ვაკეთებთ</h2>
                <p>ვაწყობთ საგამომცემლო პროცესს ისე, რომ ტექსტმა მიიღოს სწორად დამუშავებული ფორმა, ვიზუალი და საბოლოო ხარისხი.</p>
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
                <h2>ჩვენი გამოცემები</h2>
                <p>ბუკინისტების გამომცემლობის წიგნები — სუფთა ვიზუალით, მკაფიო აღწერით და პირდაპირი ბმულით მაღაზიაში.</p>
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
