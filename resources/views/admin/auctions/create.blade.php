@extends('admin.layouts.app')

@section('title', 'Create Auction')

@section('content')
<div class="container mt-4">
    <h2>ახალი აუქციონის შექმნა</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.auctions.store') }}">
        @csrf

        <div class="mb-3">
            <label>აარჩიე წიგნი</label>
            <select name="book_id" class="form-control book-chosen" required>
                @foreach($books as $book)
                    <option value="{{ $book->id }}">{{ $book->title }}</option>
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
            <label>საწყისი ფასი (GEL)</label>
            <input type="number" name="start_price" class="form-control" step="0.01" required>
        </div>

        <div class="mb-3">
            <label>აუქციონის დაწყების დრო</label>
            <input type="datetime-local" name="start_time" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>აუქციონის დასრულების დრო</label>
            <input type="datetime-local" name="end_time" class="form-control" required>
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

        <br>


        <button type="submit" class="btn btn-primary">შექმენი აუქციონი</button>
    </form>
</div>
@endsection
