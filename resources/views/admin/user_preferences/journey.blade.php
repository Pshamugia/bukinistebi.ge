@extends('admin.layouts.app')

@section('title', 'მომხმარებლის ქცევის დეტალები')

@section('content')

<h4 class="mb-4">{{ $userLabel }}</h4>
<p class="mb-3"><strong>მთლიანი დრო საიტზე:</strong> {{ gmdate('H:i:s', $totalTimeSpent) }}</p>

@if($logs->isEmpty())
    <div class="alert alert-warning">მონაცემები არ მოიძებნა</div>
@else



<ul class="list-group">
    @foreach($logs as $log)
        <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">
                <div class="fw-bold">
                    @php
                    $parsedUrl = parse_url($log->page);
                    $path = $parsedUrl['path'] ?? '';
                    $title = null;
                    $icon = '';
                
                    // Handle ?title=... from search
                    parse_str($parsedUrl['query'] ?? '', $queryParams);
                    if (str_contains($path, '/search') && isset($queryParams['title'])) {
                        $title = 'Search: ' . $queryParams['title'];
                        $icon = '🔍';
                    }
                
                    // Book page
                    elseif (preg_match('/\/books\/.*\/(\d+)/', $path, $matches)) {
                        $book = \App\Models\Book::find($matches[1]);
                        if ($book) {
                            $title = $book->title;
                            $icon = '📘';
                        }
                    }
                
                    // News article
                    elseif (preg_match('/\/full_news\/.*\/(\d+)/', $path, $matches)) {
                        $news = \App\Models\BookNews::find($matches[1]);
                        if ($news) {
                            $title = $news->title;
                            $icon = '📰';
                        }
                    }
                
                    // Fallback to last path segment
                    $fallback = \Illuminate\Support\Str::afterLast($path, '/') ?: 'მთავარი';
                    $displayText = $title ?? ucfirst($fallback);
                @endphp
                
                <a href="{{ $log->page }}" target="_blank" class="text-decoration-underline text-primary">
                    {{ $icon }} {{ $displayText }}
                    <i class="bi bi-box-arrow-up-right small text-muted"></i>
                </a>
                

                </div>
                <small class="text-muted">
                    🕒 {{ \Carbon\Carbon::parse($log->created_at)->format('Y-m-d H:i') }} |
                    დრო: {{ $log->time_spent }} წამი
                </small>
            </div>
            <span class="badge {{ $log->cookie_consent === 'accepted' ? 'bg-success' : 'bg-danger' }}">
                {{ ucfirst($log->cookie_consent) }}
            </span>
        </li>
    @endforeach
</ul>
@endif

<a href="{{ route('admin.user.preferences.purchases') }}" class="btn btn-secondary mt-4">← დაბრუნდი</a>

@endsection
