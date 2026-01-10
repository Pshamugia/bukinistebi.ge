@extends('admin.layouts.app')

@section('title', 'Edit Auction')

@section('content')
<div class="container mt-4">
    <h2>Edit Auction</h2>

    <form method="POST" action="{{ route('admin.auctions.update', $auction->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Book</label>
            <select name="book_id" class="form-control book-chosen">
                @foreach($books as $book)
                <option value="{{ $book->id }}" {{ $auction->book_id == $book->id ? 'selected' : '' }}>
                    {{ $book->title }} — {{ $book->author->name ?? '' }}
                </option>
                
                @endforeach
            </select>
        </div>
        <script>
            $(document).ready(function() {
                $('.book-chosen').chosen({
                    no_results_text: "Oops, nothing found!"
                }); 
            });
                </script> 
        <div class="mb-3">
            <label>Start Price (₾)</label>
            <input type="number" name="start_price" step="0.01" value="{{ $auction->start_price }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Start Time</label>
            <input type="datetime-local" name="start_time" value="{{ \Carbon\Carbon::parse($auction->start_time)->format('Y-m-d\TH:i') }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>End Time</label>
            <input type="datetime-local" name="end_time" value="{{ \Carbon\Carbon::parse($auction->end_time)->format('Y-m-d\TH:i') }}" class="form-control">
        </div>

        <div class="form-group">
            <label>Minimum Bid</label>
            <input type="number" name="min_bid" class="form-control" step="0.01" value="{{ old('min_bid', $auction->min_bid ?? '') }}">
        </div>
        
        <div class="form-group">
            <label>Maximum Bid</label>
            <input type="number" name="max_bid" class="form-control" step="0.01" value="{{ old('max_bid', $auction->max_bid ?? '') }}">
        </div>
        
        <div class="form-group form-check">
            <input type="checkbox" name="is_free_bid" class="form-check-input" value="1"
                {{ old('is_free_bid', $auction->is_free_bid ?? false) ? 'checked' : '' }}>
            <label class="form-check-label">Allow Free Bidding (no min/max)</label>
        </div>


        @if(isset($auction) && $auction->book)
    <div class="mb-3">
        <label class="fw-semibold">Book Images</label>

        <div class="d-flex gap-2 flex-wrap">
            @php
                $images = $auction->book->images ?? collect();
            @endphp

            @foreach($images as $img)
                <img src="{{ asset('storage/'.$img->path) }}"
                     style="width:90px;height:120px;object-fit:cover;border-radius:6px;">
            @endforeach

            @if($images->isEmpty() && $auction->book->photo)
                <img src="{{ asset('storage/'.$auction->book->photo) }}"
                     style="width:90px;height:120px;object-fit:cover;border-radius:6px;">
            @endif
        </div>

        <small class="text-muted d-block mt-1">
            Images are managed in the book editor
        </small>

        <a href="{{ route('admin.books.edit', $auction->book->id) }}"
           class="btn btn-sm btn-outline-secondary mt-2">
            Edit Book Images →
        </a>
    </div>
@endif


        <div class="mb-3">
    <label>YouTube ვიდეო</label>
    <input type="url"
           name="video"
           class="form-control"
           value="{{ old('video', $auction->video ?? '') }}">
</div>

        
<br><Br>        

        <button type="submit" class="btn btn-success">Update Auction</button>
    </form>
</div>
@endsection
