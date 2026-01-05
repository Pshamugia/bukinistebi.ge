@extends('layouts.app')

@section('title', 'Submit Auction')

@section('content')
<div class="container mt-5" style="position: relative; top:50px;">
    <h2>აუქციონის დამატება</h2>

    @if(session('error'))
        <div class="alert alert-warning">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

<form method="POST"
      action="{{ route('auction.submit.store') }}"
      enctype="multipart/form-data">        @csrf

        <div class="mb-3">
            <label>წიგნის სათაური</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>ავტორი (არასავალდებულო)</label>
            <input type="text" name="author" class="form-control">
        </div>

        <div class="mb-3">
    <label class="form-label">
        აღწერა <span class="text-danger">*</span>
    </label>
    <textarea
        name="description"
        class="form-control"
        rows="5"
        required
        placeholder="მოკლე აღწერა, მდგომარეობა, დეტალები..."
    >{{ old('description') }}</textarea>

    @error('description')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>


        <div class="mb-3">
            <label>საწყისი ფასი (₾)</label>
            <input type="number" step="0.01" name="start_price" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>დაწყების დრო</label>
            <input type="datetime-local" name="start_time" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>დასრულების დრო</label>
            <input type="datetime-local" name="end_time" class="form-control" required>
        </div>


        <div class="mb-3">
    <label class="form-label">
        ფოტოები (მინ. 1, მაქს. 4)
    </label>

    <input type="file"
           name="photos[]"
           class="form-control mb-2"
           accept="image/*"
           required>

    <input type="file"
           name="photos[]"
           class="form-control mb-2"
           accept="image/*">

    <input type="file"
           name="photos[]"
           class="form-control mb-2"
           accept="image/*">

    <input type="file"
           name="photos[]"
           class="form-control"
           accept="image/*">

    <small class="text-muted">
        JPG / PNG / WEBP · მაქს. 5MB თითო სურათი
    </small>
</div>



        <button class="btn btn-primary">
            გაგზავნა დასამტკიცებლად
        </button>
    </form>
</div>
@endsection
