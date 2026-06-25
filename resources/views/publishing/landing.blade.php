@extends('layouts.app')

@section('title', 'გამომცემლობა ბუკინისტები')

@section('content')
<div class="container py-5" style="margin-top: 80px;">
    <section class="text-center mb-5">
        <h1 class="fw-bold mb-3">გამომცემლობა ბუკინისტები</h1>
        <p class="lead text-muted mx-auto" style="max-width: 780px;">
            წიგნის გამოცემა, რედაქტირება, დიზაინი და გავრცელება — ყველაფერი ერთ სივრცეში.
        </p>
        <a href="#about-publishing" class="btn btn-dark px-4 mt-2">დაგვიკავშირდით</a>
    </section>

    <section class="row g-4 mb-5">
        @forelse($items as $item)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0">
                    @php
                        $image = $item->image_1 ?? $item->image_2 ?? $item->image_3 ?? $item->image_4 ?? null;
                    @endphp

                    @if($image)
                        <img src="{{ asset('storage/' . $image) }}" class="card-img-top" alt="{{ $item->title }}" style="height: 240px; object-fit: cover;">
                    @endif

                    <div class="card-body d-flex flex-column">
                        @if($item->category)
                            <span class="badge bg-secondary align-self-start mb-2">{{ $item->category }}</span>
                        @endif

                        <h5 class="card-title">{{ $item->title }}</h5>
                        <p class="card-text text-muted">
                            {{ \Illuminate\Support\Str::limit(strip_tags($item->description), 140) }}
                        </p>

                        <div class="mt-auto d-flex gap-2 flex-wrap">
                            <a href="{{ route('publishing.show', $item->id) }}" class="btn btn-outline-dark btn-sm">სრულად ნახვა</a>
                            @if($item->shop_url)
                                <a href="{{ $item->shop_url }}" target="_blank" rel="noopener" class="btn btn-dark btn-sm">ყიდვა</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light border text-center">
                    ამ ეტაპზე ჩანაწერები დამატებული არ არის.
                </div>
            </div>
        @endforelse
    </section>

    <section id="about-publishing" class="card shadow-sm border-0">
        <div class="card-body p-4 p-md-5">
            <div class="row g-4 align-items-start">
                <div class="col-lg-5">
                    <h2 class="fw-bold mb-3">გსურთ წიგნის გამოცემა?</h2>
                    <p class="text-muted mb-0">
                        მოგვწერეთ დეტალები და სურვილის შემთხვევაში ატვირთეთ ფაილი PDF/DOC/DOCX ფორმატში.
                    </p>

                    @if(session('publishing_success'))
                        <div class="alert alert-success mt-4">{{ session('publishing_success') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger mt-4">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <div class="col-lg-7">
                    <form action="{{ route('publishing.contact') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                        @csrf
                        <div class="col-md-6">
                            <label class="form-label">სახელი</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ელფოსტა</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">შეტყობინება</label>
                            <textarea name="message" rows="5" class="form-control" required>{{ old('message') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">ფაილი (არასავალდებულო)</label>
                            <input type="file" name="attachment" class="form-control" accept=".pdf,.doc,.docx">
                        </div>
                        <div class="col-12 text-end">
                            <button class="btn btn-dark px-4">გაგზავნა</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
