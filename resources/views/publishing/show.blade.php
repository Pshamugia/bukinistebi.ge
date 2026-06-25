@extends('layouts.app')

@section('title', $item->title)

@section('content')
<style>
    .publishing-show {
        margin-top: 40px;
    }

    .publishing-show-header {
        padding: 34px;
        border-radius: 16px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        margin-bottom: 24px;
    }

    .publishing-show-header h1 {
        font-weight: 800;
        margin-bottom: 12px;
    }

    .publishing-show-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 28px;
    }

    .publishing-show-grid img {
        width: 100%;
        border-radius: 14px;
        object-fit: cover;
        background: #f3f4f6;
    }

    @media (max-width: 768px) {
        .publishing-show-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="publishing-show">
    <div class="publishing-show-header">
        <a href="{{ route('welcome') }}" class="btn btn-sm btn-outline-secondary mb-3">← უკან</a>
        <h1>{{ $item->title }}</h1>
        @if($item->category)
            <span class="badge bg-warning text-dark">{{ $item->category }}</span>
        @endif
    </div>

    @php
        $images = collect([$item->image_1, $item->image_2, $item->image_3, $item->image_4])->filter();
    @endphp

    @if($images->isNotEmpty())
        <div class="publishing-show-grid">
            @foreach($images as $image)
                <img src="{{ asset('storage/' . $image) }}" alt="{{ $item->title }}">
            @endforeach
        </div>
    @endif

    <div class="lead">
        {!! nl2br(e($item->description)) !!}
    </div>

    @if($item->shop_url)
        <a href="{{ $item->shop_url }}" class="btn btn-dark mt-4" target="_blank" rel="noopener">მაღაზიაში ნახვა</a>
    @endif
</div>
@endsection
