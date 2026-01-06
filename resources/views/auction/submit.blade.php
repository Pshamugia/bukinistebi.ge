@extends('layouts.app')

@section('title', 'Submit Auction')

@section('content')
<div class="container mt-5" style="max-width: 900px; position: relative; top:50px;">

    <h2 class="mb-4 fw-bold">
        🏷️ აუქციონის დამატება
    </h2>

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

    
    

    <form method="POST"
          action="{{ route('auction.submit.store') }}"
          enctype="multipart/form-data">
        @csrf

        {{-- ================= BASIC INFO ================= --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body" style="background-color: #f8f9fa;">
                <h5 class="mb-3">📘 ძირითადი ინფორმაცია</h5>

                <div class="mb-3">
                    <label class="form-label fw-semibold">წიგნის სათაური *</label>
                    <input type="text"
                           name="title"
                           class="form-control"
                           required
                           placeholder="მაგ: ძველი ქართული გამოცემა">
                </div>

                <div class="mb-3">
                    <label class="form-label">ავტორი (არასავალდებულო)</label>
                    <input type="text"
                           name="author"
                           class="form-control"
                           placeholder="მაგ: ილია ჭავჭავაძე">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        აღწერა *
                    </label>
                    <textarea name="description"
                              class="form-control"
                              rows="5"
                              required
                              placeholder="მდგომარეობა, გამოცემა, შენიშვნები...">{{ old('description') }}</textarea>
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
                               required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">დაწყების დრო *</label>
                        <input type="datetime-local"
                               name="start_time"
                               class="form-control"
                               required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">დასრულების დრო *</label>
                        <input type="datetime-local"
                               name="end_time"
                               class="form-control"
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
                               placeholder="არასავალდებულო">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">მაქსიმალური ბიჯი (₾)</label>
                        <input type="number"
                               name="max_bid"
                               id="maxBid"
                               class="form-control"
                               step="0.01"
                               placeholder="არასავალდებულო">
                    </div>
                </div>

                <div class="form-check mt-2">
                    <input class="form-check-input"
                           type="checkbox"
                           name="is_free_bid"
                           value="1"
                           id="isFreeBid">
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
document.getElementById('isFreeBid').addEventListener('change', function () {
    const disabled = this.checked;
    document.getElementById('minBid').disabled = disabled;
    document.getElementById('maxBid').disabled = disabled;
});
</script>
@endsection
