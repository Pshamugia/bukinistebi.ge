@extends('layouts.app')

@section('title', $item->title)

@section('content')
<div class="container py-5" style="margin-top: 80px; max-width: 1000px;">
    <a href="{{ route('publishing.landing') }}" class="btn btn-link px-0 mb-3">← უკან</a>

    <article class="card shadow-sm border-0">
        @php
            $images = collect([$item->image_1, $item->image_2, $item->image_3, $item->image_4])->filter();
        @endphp

        @if($images->isNotEmpty())
            <div class="row g-2 p-3">
                @foreach($images as $image)
                    <div class="col-md-6">
                        <img src="{{ asset('storage/' . $image) }}" alt="{{ $item->title }}" class="img-fluid rounded" style="width: 100%; height: 320px; object-fit: cover;">
                    </div>
                @endforeach
            </div>
        @endif

        <div class="card-body p-4 p-md-5">
            @if($item->category)
                <span class="badge bg-secondary mb-3">{{ $item->category }}</span>
            @endif

            <h1 class="fw-bold mb-4">{{ $item->title }}</h1>

            <div class="fs-5 text-muted mb-4">
                {!! nl2br(e($item->description)) !!}
            </div>

            @if($item->shop_url)
                <a href="{{ $item->shop_url }}" target="_blank" rel="noopener" class="btn btn-dark px-4">ყიდვა</a>
            @endif
        </div>
    </article>
</div>
@endsection
