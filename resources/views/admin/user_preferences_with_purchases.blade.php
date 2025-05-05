@extends('admin.layouts.app')

@section('title', 'User Preferences and Purchases')

@section('content')
<div class="container">
    <h2>{{ __('User Preferences and Purchases') }}</h2>
    <table class="table">
        <thead>
            <tr>
                <th>{{ __('User Name') }}</th>
                <th>{{ __('Cookie Consent') }}</th>
                <th>{{ __('Time Spent') }}</th>
                <th>{{ __('Page') }}</th>
                <th>{{ __('Date') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($userPreferences as $preference)
    <tr>
        <td>{{ $preference->user_name ?? 'Guest' }}</td>  <!-- Display user_name -->
        <td>{{ $preference->cookie_consent }}</td>
        <td>{{ $preference->time_spent }} ms</td>
        <td>{{ $preference->page }}</td>
        <td>{{ $preference->created_at ? $preference->created_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
    </tr>
@endforeach
        
        </tbody>
    </table>
    {{ $userPreferences->links() }} <!-- Pagination links -->
</div>
    
 
@endsection

 

