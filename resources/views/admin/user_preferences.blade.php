@extends('admin.layouts.app')

@section('title', 'User Preferences and Purchases')

@section('content')
<table>
    <thead>
        <tr>
            <th>User Name</th>
            <th>Consent</th>
            <th>Time Spent</th>
            <th>Pages Visited</th>
            <th>Consent Given At</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($userPreferences as $preference)
            <tr>
                <td>{{ $preference->user->name }}</td>
                <td>{{ $preference->cookie_consent }}</td>
                <td>{{ $preference->time_spent }} seconds</td>
                <td>{{ $preference->page_visited }}</td>
                <td>{{ $preference->consent_given_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

 
@endsection

 

