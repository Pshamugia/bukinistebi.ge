@extends('layouts.app')

@section('title', 'Book News')

@section('content')
<div class="container mt-5">
    <h2>Latest Book News</h2>
    <div class="row">
        @foreach($news as $newsItem)
        <div class="col-md-6 mb-4">
            <div class="card">
                @if($newsItem->image)
                <img src="{{ asset('storage/' . $newsItem->image) }}" class="card-img-top" alt="{{ $newsItem->title }}">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $newsItem->title }}</h5>
                    <p class="card-text">{{ Str::limit($newsItem->content, 150) }}</p>
                    <a href="{{ route('book_news.show', $newsItem->id) }}" class="btn btn-primary">Read More</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    {{ $news->links() }}
</div>
@endsection
