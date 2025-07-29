@extends('admin.layouts.app')

@section('title', 'User Preferences Records')

@section('content')
<div class="container">
    <h2>{{ __('User Preferences Records') }}</h2>

    <table class="table">
        <thead>
            <tr>
                <th>{{ __('User Name') }}</th>
                <th>{{ __('Cookie Consent') }}</th>
                <th>{{ __('Theme Preference') }}</th>
                <th>{{ __('Language Preference') }}</th>
                <th>{{ __('Notification Preference') }}</th>
                <th>{{ __('Date') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($userPreferences as $preference)
                <tr>
                    <td>{{ $preference->user->name ?? 'Unknown User' }}</td>
                    <td>{{ $preference->cookie_consent }}</td>
                    <td>{{ $preference->theme_preference }}</td>
                    <td>{{ $preference->language_preference }}</td>
                    <td>{{ $preference->notification_preference }}</td>
                    <td>{{ $preference->created_at->format('Y-m-d H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $userPreferences->links() }} <!-- Pagination links -->
</div>
@endsection
