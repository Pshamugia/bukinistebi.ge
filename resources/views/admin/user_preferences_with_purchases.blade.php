@extends('admin.layouts.app')

@section('title', 'User Preferences and Purchases')

@section('content')


<div class="row">
    <!-- Consent Pie Chart -->
    <div class="col-md-6 d-flex flex-column align-items-center">
        <h6 class="text-center">თანხმობის სტატუსი</h6>
        <div style="width: 100%; max-width: 350px;">
            <canvas id="consentChart"></canvas>
        </div>
    </div>

    <!-- User Path Bar Chart -->
    <div class="col-md-6">
        <h6 class="text-center">ყველაზე ხშირად ნანახი გვერდები</h6>
        <canvas id="userPathChart" height="300"></canvas>
    </div>
</div>
 




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
            <th>მთლიანი დრო</th>
            <th>თარიღი</th>
        </tr>
    </thead>
    <tbody>
        @foreach($userPreferences as $pref)
        <tr>
            <td>
                @if(!empty($pref->identifier))
                    <a href="{{ route('admin.user.preferences.journey', $pref->identifier) }}">
                        {{ $pref->label }}
                    </a>
                @else
                    <span class="text-danger">No Identifier</span>
                @endif
            </td>
            <td>
                <span class="badge {{ $pref->cookie_consent == 'accepted' ? 'bg-success' : 'bg-danger' }}">
                    {{ ucfirst($pref->cookie_consent) }}
                </span>
            </td>
            <td>{{ $pref->time_spent }} წამი</td>
            <td>{{ gmdate('H:i:s', $pref->total_time_spent ?? 0) }}</td>

            <td>{{ $pref->date }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $userPreferences->links('pagination.custom-pagination') }}


 
@endsection

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Consent Chart
    const consentCtx = document.getElementById('consentChart').getContext('2d');
    new Chart(consentCtx, {
        type: 'pie',
        data: {
            labels: ['თანხმობა', 'უარყოფა'],
            datasets: [{
                data: [{{ $acceptedCount }}, {{ $rejectedCount }}],
                backgroundColor: ['#28a745', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });

    // Page Views Chart
    fetch("{{ route('admin.user.preferences.chartdata') }}")
        .then(res => res.json())
        .then(data => {
            const pathCtx = document.getElementById('userPathChart').getContext('2d');
            new Chart(pathCtx, {
                type: 'bar',
                data: {
                    labels: data.map(d => d.page),
                    datasets: [{
                        label: 'გვერდზე ვიზიტების რაოდენობა',
                        data: data.map(d => d.count),
                        backgroundColor: '#007bff'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            ticks: {
                                autoSkip: false,
                                maxRotation: 45,
                                minRotation: 0
                            }
                        }
                    }
                }
            });
        });
});
</script>

