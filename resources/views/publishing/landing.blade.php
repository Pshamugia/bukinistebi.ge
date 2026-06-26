@extends('layouts.app')

@section('title', 'გამომცემლობა ბუკინისტები')

@section('content')
<style>
    .publishing-landing {
        margin-top: 38px;
        color: #1f2933;
    }

    .publishing-hero {
        display: grid;
        grid-template-columns: minmax(0, 1.1fr) minmax(280px, .9fr);
        gap: 32px;
        align-items: center;
        padding: 54px 42px;
        border-radius: 18px;
        background:
            linear-gradient(135deg, rgba(25, 25, 24, .88), rgba(80, 65, 39, .7)),
            url("{{ asset('images/book-bg.webp') }}");
        background-size: cover;
        background-position: center;
        color: #fff;
        overflow: hidden;
    }

    .publishing-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 12px;
        border: 1px solid rgba(255,255,255,.32);
        border-radius: 999px;
        background: rgba(255,255,255,.12);
        font-size: 14px;
        margin-bottom: 18px;
    }

    .publishing-hero h1 {
        max-width: 720px;
        font-size: clamp(34px, 5vw, 64px);
        line-height: 1.05;
        margin-bottom: 18px;
        font-weight: 800;
    }

    .publishing-hero p {
        max-width: 660px;
        font-size: 18px;
        line-height: 1.75;
        color: rgba(255,255,255,.88);
        margin-bottom: 26px;
    }

    .publishing-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .publishing-actions .btn {
        border-radius: 999px;
        padding: 11px 18px;
        font-weight: 700;
    }

    .publishing-panel {
        padding: 24px;
        border-radius: 16px;
        background: rgba(255,255,255,.92);
        color: #222;
        box-shadow: 0 18px 48px rgba(0,0,0,.25);
    }

    .publishing-panel h3 {
        margin-bottom: 14px;
        font-size: 22px;
        font-weight: 800;
    }

    .publishing-panel ul {
        padding-left: 18px;
        margin-bottom: 0;
        line-height: 1.9;
    }

    .publishing-section {
        padding: 46px 0 0;
    }

    .publishing-section h2 {
        font-size: 30px;
        font-weight: 800;
        margin-bottom: 18px;
    }

    .publishing-services {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
    }

    .publishing-service {
        padding: 22px;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 10px 24px rgba(15, 23, 42, .06);
    }

    .publishing-service i {
        font-size: 28px;
        color: #b0892f;
        margin-bottom: 12px;
    }

    .publishing-service h3 {
        font-size: 18px;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .publishing-service p {
        color: #5f6b7a;
        line-height: 1.7;
        margin: 0;
    }

    .publishing-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
    }

    .publishing-card {
        overflow: hidden;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 10px 24px rgba(15, 23, 42, .06);
    }

    .publishing-card img {
        width: 100%;
        aspect-ratio: 4 / 3;
        object-fit: cover;
        background: #f3f4f6;
    }

    .publishing-card-body {
        padding: 18px;
    }

    .publishing-card h3 {
        font-size: 18px;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .publishing-card p {
        color: #5f6b7a;
        line-height: 1.6;
        margin-bottom: 12px;
    }

    .publishing-contact {
        display: grid;
        grid-template-columns: minmax(0, .8fr) minmax(280px, 1.2fr);
        gap: 24px;
        align-items: start;
        padding: 30px;
        border-radius: 16px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
    }

    .publishing-contact form .form-control {
        min-height: 44px;
        border-radius: 10px;
    }

    .publishing-contact textarea.form-control {
        min-height: 150px;
    }

    @media (max-width: 991px) {
        .publishing-hero,
        .publishing-contact {
            grid-template-columns: 1fr;
        }

        .publishing-services,
        .publishing-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="publishing-landing">
    <section class="publishing-hero">
        <div>
            <div class="publishing-kicker">
                <i class="bi bi-book"></i>
                <span>გამომცემლობა ბუკინისტები</span>
            </div>
            <h1>წიგნის გამოცემა, მომზადება და მკითხველამდე მიტანა</h1>
            <p>
                ვეხმარებით ავტორებს, მთარგმნელებსა და ორგანიზაციებს წიგნის იდეიდან დაბეჭდილ გამოცემამდე:
                რედაქტირება, დიზაინი, დაკაბადონება, ბეჭდვა და გავრცელება.
            </p>
            <div class="publishing-actions">
                <a href="#about-publishing" class="btn btn-warning">დაგვიკავშირდით</a>
                <a href="https://bukinistebi.ge" class="btn btn-outline-light">ბუკინისტური მაღაზია</a>
            </div>
        </div>

        <div class="publishing-panel">
            <h3>რას ვაკეთებთ</h3>
            <ul>
                <li>წიგნის რედაქტირება და კორექტურა</li>
                <li>გარეკანის დიზაინი და დაკაბადონება</li>
                <li>ბეჭდვისთვის ფაილების მომზადება</li>
                <li>ავტორებთან და გამომცემლებთან თანამშრომლობა</li>
            </ul>
        </div>
    </section>

    <section class="publishing-section">
        <h2>სერვისები</h2>
        <div class="publishing-services">
            <article class="publishing-service">
                <i class="bi bi-pencil-square"></i>
                <h3>ტექსტზე მუშაობა</h3>
                <p>რედაქტირება, კორექტურა და სტრუქტურული მოწესრიგება გამოცემისთვის.</p>
            </article>
            <article class="publishing-service">
                <i class="bi bi-palette"></i>
                <h3>დიზაინი</h3>
                <p>გარეკანის ვიზუალი, შიდა გვერდების სტილი და სრული დაკაბადონება.</p>
            </article>
            <article class="publishing-service">
                <i class="bi bi-printer"></i>
                <h3>ბეჭდვა</h3>
                <p>ბეჭდვისთვის გამართული ფაილები და პროცესის ორგანიზებაში დახმარება.</p>
            </article>
        </div>
    </section>

    @if($items->isNotEmpty())
        <section class="publishing-section">
            <h2>ნამუშევრები</h2>
            <div class="publishing-grid">
                @foreach($items as $item)
                    <article class="publishing-card">
                        @if($item->image_1)
                            <img src="{{ asset('storage/' . $item->image_1) }}" alt="{{ $item->title }}">
                        @endif
                        <div class="publishing-card-body">
                            <h3>{{ $item->title }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit(strip_tags($item->description), 120) }}</p>
                            <a href="{{ $item->shop_url ?: route('publishing.show', $item->id) }}" class="btn btn-sm btn-outline-dark">მაღაზიაში ნახვა</a>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    <section class="publishing-section" id="about-publishing">
        <div class="publishing-contact">
            <div>
                <h2>დაგვიკავშირდით</h2>
                <p class="text-muted">
                    გამოგვიგზავნეთ ტექსტის მოკლე აღწერა ან ფაილი და დაგიბრუნდებით თანამშრომლობის დეტალებით.
                </p>
                <p class="mb-1"><strong>Email:</strong> publishing@bukinistebi.ge</p>
            </div>

            <form method="POST" action="{{ route('publishing.contact') }}" enctype="multipart/form-data">
                @csrf

                @if(session('publishing_success'))
                    <div class="alert alert-success">{{ session('publishing_success') }}</div>
                @endif

                <div class="mb-3">
                    <label class="form-label">სახელი</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">ელფოსტა</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">შეტყობინება</label>
                    <textarea name="message" class="form-control" required>{{ old('message') }}</textarea>
                    @error('message') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">ფაილი</label>
                    <input type="file" name="attachment" class="form-control" accept=".pdf,.doc,.docx">
                    @error('attachment') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-dark">გაგზავნა</button>
            </form>
        </div>
    </section>
</div>

<style>
    /* Active publishing.bukinistebi.ge redesign: this route renders publishing.landing. */
    .publishing-landing{margin:0 calc(50% - 50vw);padding:58px calc(50vw - 50%) 76px;background:#f5f6f7;color:#17191c;font-family:liFont,sans-serif;overflow:hidden}.publishing-landing *{box-sizing:border-box}.publishing-hero{border:1px solid #e1e5e9;border-radius:34px;background:linear-gradient(135deg,#fff 0%,#f0f2f4 100%)!important;color:#17191c!important;box-shadow:0 28px 70px rgba(17,24,39,.09);gap:52px;padding:68px 54px}.publishing-kicker{border-color:#dfe3e8;background:#fff;color:#5b626b;font-weight:800}.publishing-kicker i{color:#8d1b1f}.publishing-hero h1,.publishing-section h2,.publishing-panel h3{font-family:h1Font,liFont,sans-serif;letter-spacing:-.02em;color:#15181c}.publishing-hero h1{font-size:clamp(38px,5vw,66px);line-height:1.04}.publishing-hero p{color:#5d6570!important;font-size:17px}.publishing-actions .btn{border-radius:999px;padding:12px 20px;font-weight:900}.publishing-actions .btn-warning{border-color:#15191d;background:#15191d;color:#fff}.publishing-actions .btn-warning:hover{border-color:#8d1b1f;background:#8d1b1f;color:#fff}.publishing-actions .btn-outline-light{border-color:#d8dde2;background:#fff;color:#15191d}.publishing-panel{position:relative;min-height:340px;border:0;border-radius:30px;background:radial-gradient(circle at 85% 12%,rgba(141,27,31,.42),transparent 28%),linear-gradient(145deg,#20252b,#101214)!important;color:#fff;box-shadow:0 30px 70px rgba(16,18,20,.22);overflow:hidden}.publishing-panel h3,.publishing-panel ul{display:none}.publishing-panel:before{content:'Publishing Studio';position:absolute;left:28px;top:26px;color:rgba(255,255,255,.72);font-size:12px;font-weight:900;letter-spacing:.08em;text-transform:uppercase}.publishing-panel:after{content:'იდეა → რედაქტურა → წიგნი';position:absolute;left:54px;right:54px;top:86px;bottom:72px;padding:30px;border-radius:24px;background:#f8f9fa;color:#17191c;font-family:h1Font,liFont,sans-serif;font-size:27px;font-weight:900;line-height:1.22;box-shadow:0 24px 48px rgba(0,0,0,.25),34px 132px 0 -4px #15191d,120px 112px 0 -4px #8d1b1f,206px 138px 0 -4px #dfe3e7}.publishing-section{padding-top:62px}.publishing-section h2{font-size:clamp(30px,3.4vw,44px);font-weight:900}.publishing-service,.publishing-card{border:1px solid #e0e4e8;border-radius:22px;background:#fff;box-shadow:0 18px 42px rgba(18,22,28,.07);overflow:hidden}.publishing-service{padding:26px}.publishing-service i{display:inline-flex;align-items:center;justify-content:center;width:40px;height:40px;border-radius:13px;background:#15191d;color:#fff;font-size:18px}.publishing-service h3,.publishing-card h3{color:#17191c;font-weight:900}.publishing-service p,.publishing-card p{color:#68707a}.publishing-card{transition:.18s ease}.publishing-card:hover{transform:translateY(-4px);box-shadow:0 24px 54px rgba(18,22,28,.11)}.publishing-card .btn{border-radius:999px;font-weight:900}.publishing-contact{border-radius:30px;background:#101214;color:#fff}.publishing-contact .text-muted{color:rgba(255,255,255,.72)!important}.publishing-contact form .form-control{border:0;border-radius:12px;background:#f5f6f7}@media(max-width:991px){.publishing-landing{padding-top:32px}.publishing-hero{grid-template-columns:1fr;padding:42px 24px}.publishing-services,.publishing-grid{grid-template-columns:1fr 1fr}}@media(max-width:575px){.publishing-services,.publishing-grid{grid-template-columns:1fr}.publishing-panel:after{left:24px;right:24px;font-size:22px}}
</style>

@endsection
