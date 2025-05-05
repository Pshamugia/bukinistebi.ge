@extends('admin.layouts.app')

@section('title', 'User Search Keywords')

@section('content')
<div class="container">
    <h2>{{ __('რას ეძებენ ჩვენი მომხმარებლები') }}</h2>

    <!-- Display Top 5 Most Searched Keywords -->
    <div class="top-keywords">
        <div style="text-align: left; background-color: rgb(120, 194, 228); padding: 22px; border-radius: 5px">

        <h4>{{ __('Top 10 ყველაზე ძიებადი სიტყვა') }}</h4>
        <ul style="list-style-type: none;">
            @foreach($topKeywords as $index => $keyword)
            <li>{{ $index + 1 }}. {{ $keyword->keyword }} ({{ $keyword->count }})</li> <!-- Display count of searches -->
        @endforeach
        </ul>
    </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>{{ __('User Name') }}</th>
                <th>{{ __('Search Keyword') }}</th>
                <th>{{ __('Date') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($keywords as $keyword)
                <tr>
                    <td>{{ $keyword->user->name ?? 'Guest' }}</td>
                    <td>{{ $keyword->keyword }}</td>
                    <td>{{ $keyword->created_at->format('Y-m-d H:i:s') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">{{ __('No keywords found.') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $keywords->links('pagination.custom-pagination') }} <!-- Pagination links -->
</div>
@endsection
