@extends('admin.layouts.app')

@section('title', 'User Preferences and Purchases')

@section('content')


<div style="max-width: 400px; margin: 0 auto;">
    <canvas id="consentChart" height="200"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('consentChart').getContext('2d');

    const chart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['თანხმობა', 'უარყოფა'],
            datasets: [{
                label: 'Cookie Consent Breakdown',
                data: [{{ $acceptedCount }}, {{ $rejectedCount }}],
                backgroundColor: ['#28a745', '#dc3545']
            }]
        }
    });
});
</script>

<form method="GET" class="mb-3">
    <select name="consent" class="form-select w-auto d-inline">
        <option value="all">ყველა</option>
        <option value="accepted" {{ request('consent') == 'accepted' ? 'selected' : '' }}>თანხმობა</option>
        <option value="rejected" {{ request('consent') == 'rejected' ? 'selected' : '' }}>უარყოფა</option>
    </select>
    <button type="submit" class="btn btn-primary btn-sm">გაფილტრე</button>
</form>

<table class="table table-bordered table-striped small">
    <thead>
        <tr>
            <th>მომხმარებელი</th>
            <th>სტატუსი</th>
            <th>დრო (წმ)</th>
            <th>გვერდი</th>
            <th>თარიღი</th>
        </tr>
    </thead>
    <tbody>
        @foreach($userPreferences as $pref)
            <tr>
                <td>{{ $pref->user->email ?? 'Guest: ' . $pref->guest_id }}</td>
                <td>
                    <span class="badge {{ $pref->cookie_consent == 'accepted' ? 'bg-success' : 'bg-danger' }}">
                        {{ ucfirst($pref->cookie_consent) }}
                    </span>
                </td>
                <td>{{ $pref->time_spent }} წამი</td>
                <td>{{ $pref->page }}</td>
                <td>{{ $pref->date }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $userPreferences->links() }}


 
@endsection

 

