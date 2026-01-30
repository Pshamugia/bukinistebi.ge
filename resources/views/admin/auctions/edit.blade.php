@extends('admin.layouts.app')

@section('title', 'Edit Auction')

@section('content')
<div class="container mt-4">

    <h2 class="mb-4">✏️ Edit Auction</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- ===============================
         ONE FORM — DO NOT SPLIT
    ================================ --}}
    <form method="POST"
          action="{{ route('admin.auctions.update.full', $auction) }}">
        @csrf
        @method('PUT')

        {{-- ===============================
            BOOK SECTION
        ================================ --}}
        <h5 class="mt-4">📘 Book</h5>

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text"
                   name="title"
                   class="form-control"
                   value="{{ old('title', $auction->book->title) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description"
                      id="description"
                      class="form-control"
                      rows="6">{{ old('description', $auction->book->description) }}</textarea>
        </div>


        <div class="mb-3">
    <label class="form-label">Auction Category</label>

    <select name="auction_category_id"
            class="form-select"
            required>
        <option value="">— Select category —</option>

        @foreach($categories as $category)
            <option value="{{ $category->id }}"
                {{ old('auction_category_id', $auction->auction_category_id) == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</div>


        {{-- ===============================
            AUCTION SECTION
        ================================ --}}
        <h5 class="mt-5">🔨 Auction</h5>

        <div class="mb-3">
            <label class="form-label">Start Price (₾)</label>
            <input type="number"
                   step="0.01"
                   name="start_price"
                   class="form-control"
                   value="{{ old('start_price', $auction->start_price) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Start Time</label>
            <input type="datetime-local"
                   name="start_time"
                   class="form-control"
                   value="{{ \Carbon\Carbon::parse($auction->start_time)->format('Y-m-d\TH:i') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">End Time</label>
            <input type="datetime-local"
                   name="end_time"
                   class="form-control"
                   value="{{ \Carbon\Carbon::parse($auction->end_time)->format('Y-m-d\TH:i') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Minimum Bid</label>
            <input type="number"
                   step="0.01"
                   name="min_bid"
                   class="form-control"
                   value="{{ old('min_bid', $auction->min_bid) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Maximum Bid</label>
            <input type="number"
                   step="0.01"
                   name="max_bid"
                   class="form-control"
                   value="{{ old('max_bid', $auction->max_bid) }}">
        </div>

        <div class="form-check mb-3">
            <input type="checkbox"
                   class="form-check-input"
                   name="is_free_bid"
                   value="1"
                   {{ old('is_free_bid', $auction->is_free_bid) ? 'checked' : '' }}>
            <label class="form-check-label">
                Allow Free Bidding (no min / max)
            </label>
        </div>

        <div class="mb-3">
            <label class="form-label">YouTube Video URL</label>
            <input type="url"
                   name="video"
                   class="form-control"
                   value="{{ old('video', $auction->video) }}">
        </div>

        {{-- ===============================
            IMAGES (VIEW ONLY)
        ================================ --}}
        <h5 class="mt-5">🖼 Book Images</h5>

        <div class="d-flex gap-2 flex-wrap mb-2">
            @forelse($auction->book->images ?? [] as $img)
                <img src="{{ asset('storage/'.$img->path) }}"
                     style="width:90px;height:120px;object-fit:cover;border-radius:6px;">
            @empty
                @if($auction->book->photo)
                    <img src="{{ asset('storage/'.$auction->book->photo) }}"
                         style="width:90px;height:120px;object-fit:cover;border-radius:6px;">
                @endif
            @endforelse
        </div>

        <a href="{{ route('admin.books.edit', $auction->book_id) }}"
           class="btn btn-outline-secondary btn-sm mb-4">
            Edit Book Images →
        </a>

        {{-- ===============================
            SUBMIT
        ================================ --}}
        <div class="mt-4">
            <button type="submit" class="btn btn-success px-4">
                💾 Update Auction
            </button>

            <a href="{{ route('admin.auctions.index') }}"
               class="btn btn-outline-secondary ms-2">
                Cancel
            </a>
        </div>

    </form>
</div>

{{-- ===============================
    CKEDITOR
=============================== --}}
<script>
    if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.replace('description');
    }
</script>
@endsection
