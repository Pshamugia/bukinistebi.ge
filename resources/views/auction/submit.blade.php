@extends('layouts.app')

@section('title', 'Submit Auction')

@section('content')
<div class="container mt-5" style="max-width: 900px; position: relative; top:50px;">

    <h2 class="mb-4 fw-bold">
        🏷️ აუქციონის დამატება
    </h2>

    <style>
        .auction-rules-accept {
            display: flex;
            align-items: center;
        }

        .rules-check {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            user-select: none;
        }

        .rules-check input {
            display: none;
        }

        /* Custom checkbox */
        .rules-check .checkmark {
            width: 22px;
            height: 22px;
            border: 2px solid #8b5e3c;
            border-radius: 5px;
            background: #fffdf7;
            position: relative;
            flex-shrink: 0;
        }

        /* Checked state */
        .rules-check input:checked+.checkmark {
            background: #8b5e3c;
            border-color: #8b5e3c;
        }

        .rules-check input:checked+.checkmark::after {
            content: "✓";
            position: absolute;
            top: -1px;
            left: 4px;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
        }

        /* Text */
        .rules-text {
            font-size: 16px;
            color: #4d3b2f;
        }

        .rules-text a {
            color: #5a3e2b;
            font-weight: 600;
            text-decoration: underline;
        }

        .rules-text a:hover {
            color: #8b5e3c;
        }
    </style>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
        <h5 class="mb-1">✔ აუქციონი მიღებულია</h5>
        <p class="mb-0">
            {{ session('success') }}
        </p>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif


    @if(session('error'))
    <div class="alert alert-warning">{{ session('error') }}</div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif


    <form method="POST"
        action="{{ route('auction.submit.store') }}"
        enctype="multipart/form-data">
        @csrf

        {{-- ================= BASIC INFO ================= --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body" style="background-color: #f8f9fa;">
                <h5 class="mb-3">📘 ძირითადი ინფორმაცია</h5>

                <div class="mb-3">
                    <label class="form-label fw-semibold"> დაასათაურე *</label>
                    <input type="text"
                        name="title"
                        class="form-control"
                        value="{{ old('title') }}"
                        required
                        placeholder="მაგ: ძველი ქართული გამოცემა">
                </div>

                <div class="mb-3">
                    <label class="form-label">ავტორი (არასავალდებულო)</label>
                    <input type="text"
                        name="author"
                        class="form-control"
                        value="{{ old('author') }}"
                        placeholder="მაგ: ილია ჭავჭავაძე">
                </div>


                <div class="mb-3">
                    <label class="form-label fw-semibold">კატეგორია *</label>
                    <select name="auction_category_id" class="form-select" required>
                        <option value="">აირჩიე კატეგორია</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('auction_category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                </div>


                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        აღწერა *
                    </label>
                    <textarea name="description"
                        class="form-control"
                        rows="5"
                        required
                        placeholder="წიგნის ან ნივთის მდგომარეობა, შენიშვნები...">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        {{-- ================= AUCTION SETTINGS ================= --}}
        <div class="card shadow-sm mb-4" style="background-color: #f8f9fa;">
            <div class="card-body">
                <h5 class="mb-3">⏱️ აუქციონის პარამეტრები</h5>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">საწყისი ფასი (₾) *</label>
                        <input type="number"
                            step="0.01"
                            name="start_price"
                            class="form-control"
                            value="{{ old('start_price') }}"
                            required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">ბლიც-ფასი (₾)</label>
                        <input type="number"
                            step="0.01"
                            min="0"
                            name="buy_now_price"
                            class="form-control"
                            value="{{ old('buy_now_price') }}"
                            placeholder="არასავალდებულო">
                        <small class="text-muted">თუ შეივსება, ფასი უნდა იყოს საწყის ფასზე მეტი.</small>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">დაწყების დრო *</label>
                        <input type="datetime-local"
                            name="start_time"
                            class="form-control"
                            value="{{ old('start_time') }}"
                            required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">დასრულების დრო *</label>
                        <input type="datetime-local"
                            name="end_time"
                            class="form-control"
                            value="{{ old('end_time') }}"
                            required>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= BID RULES ================= --}}
        <div class="card shadow-sm mb-4" style="background-color: #f8f9fa;">
            <div class="card-body">
                <h5 class="mb-3">💰 ბიჯის წესები</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">მინიმალური ბიჯი (₾)</label>
                        <input type="number"
                            name="min_bid"
                            id="minBid"
                            class="form-control"
                            step="0.01"
                            value="{{ old('min_bid') }}"
                            placeholder="არასავალდებულო">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">მაქსიმალური ბიჯი (₾)</label>
                        <input type="number"
                            name="max_bid"
                            id="maxBid"
                            class="form-control"
                            step="0.01"
                            value="{{ old('max_bid') }}"
                            placeholder="არასავალდებულო">
                    </div>
                </div>

                <div class="form-check mt-2">
                    <input class="form-check-input"
                        type="checkbox"
                        name="is_free_bid"
                        value="1"
                        id="isFreeBid"
                        {{ old('is_free_bid') ? 'checked' : '' }}>
                    <label class="form-check-label" for="isFreeBid">
                        თავისუფალი ბიჯი (შეზღუდვების გარეშე)
                    </label>
                </div>

                <small class="text-muted d-block mt-2">
                    თუ მონიშნულია — მინ. და მაქს. ბიჯები იგნორირდება.
                </small>
            </div>
        </div>

        {{-- ================= PHOTOS ================= --}}
        <div class="card shadow-sm mb-4" style="background-color: #f8f9fa;">
            <div class="card-body">
                <h5 class="mb-3">🖼️ ფოტოები</h5>

                <p class="text-muted mb-2">
                    მინიმუმ 1, მაქსიმუმ 4 ფოტო · JPG / PNG / WEBP · მაქს. 5MB
                </p>

                @for($i = 0; $i < 4; $i++)
                    <input type="file"
                    name="photos[]"
                    class="form-control mb-2"
                    accept="image/*"
                    {{ $i === 0 ? 'required' : '' }}>
                    @endfor
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">
                YouTube ვიდეო (არასავალდებულო)
            </label>

            <input type="url"
                name="video"
                class="form-control"
                placeholder="https://www.youtube.com/watch?v=XXXXX"
                value="{{ old('video') }}">

            <small class="text-muted">
                მხოლოდ YouTube ბმული (preview მოგვიანებით დაემატება)
            </small>
        </div>



        <div class="auction-rules-accept mt-4 mb-4">
            <label class="rules-check">
                <input
                    type="checkbox"
                    name="accept_rules"
                    required>
                <span class="checkmark"></span>

                <span class="rules-text">
                    წავიკითხე და ვეთანხმები
                    <a href="{{ route('auction.rules') }}" target="_blank">
                        ბუკინისტური აუქციონის წესებს
                    </a>
                </span>
            </label>
        </div>



        {{-- ================= SUBMIT ================= --}}
        <div class="text-end">
            <button class="btn btn-primary btn-lg px-5">
                ✔ გაგზავნა დასამტკიცებლად
            </button>
        </div>

    </form>
    <br><br>
</div>

{{-- UX helper --}}
<script>
    document.getElementById('isFreeBid').addEventListener('change', function() {
        const disabled = this.checked;
        document.getElementById('minBid').disabled = disabled;
        document.getElementById('maxBid').disabled = disabled;
    });
</script>
@endsection