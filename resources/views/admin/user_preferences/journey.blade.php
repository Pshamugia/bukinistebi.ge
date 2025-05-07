@extends('admin.layouts.app')

@section('title', 'მომხმარებლის ქცევის დეტალები')

@section('content')

<h4 class="mb-4">{{ $userLabel }}</h4>

@if($logs->isEmpty())
    <div class="alert alert-warning">მონაცემები არ მოიძებნა</div>
@else



<ul class="list-group">
    @foreach($logs as $log)
        <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">
                <div class="fw-bold">
                    <a href="{{ $log->page }}" target="_blank" class="text-decoration-underline text-primary">
                        {{ $log->page }} <i class="bi bi-box-arrow-up-right"></i>
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
