@extends('admin.layouts.app')

@section('title', 'მომხმარებლების ქცევა')

@push('styles')
<style>
    .analytics-page { color: #172033; }
    .analytics-header, .metric-card, .panel, .filter-panel {
        background: #fff;
        border: 1px solid #e6eaf0;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
    }
    .analytics-header { padding: 22px; }
    .analytics-title { font-size: 1.35rem; font-weight: 700; margin: 0; }
    .analytics-muted { color: #667085; font-size: .92rem; }
    .metric-card { padding: 16px; height: 100%; }
    .metric-label { color: #667085; font-size: .78rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; }
    .metric-value { color: #111827; font-size: 1.45rem; font-weight: 800; line-height: 1.1; margin-top: 8px; }
    .panel { padding: 18px; height: 100%; }
    .filter-panel { background: #f8fafc; padding: 16px; box-shadow: none; }
    .user-quick-search {
        background: #fff;
        border: 1px solid #dbe4f0;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
        padding: 16px;
    }
    .user-quick-search .form-control {
        min-height: 46px;
    }
    .panel-title { font-size: .98rem; font-weight: 700; margin-bottom: 14px; }
    .user-cell-title { color: #1d4ed8; font-weight: 700; text-decoration: none; }
    .user-cell-title:hover { text-decoration: underline; }
    .page-path { display: inline-block; max-width: 320px; overflow: hidden; text-overflow: ellipsis; vertical-align: bottom; white-space: nowrap; }
    .table thead th { background: #f8fafc; color: #475467; font-size: .78rem; letter-spacing: .03em; text-transform: uppercase; white-space: nowrap; }
    .table tbody td { color: #1f2937; }
    .badge-soft { border: 1px solid transparent; border-radius: 999px; font-weight: 700; padding: .4rem .55rem; }
    .badge-accepted { background: #ecfdf3; border-color: #abefc6; color: #067647; }
    .badge-rejected { background: #fef3f2; border-color: #fecdca; color: #b42318; }
    .badge-neutral { background: #f2f4f7; border-color: #d0d5dd; color: #475467; }
    @media (max-width: 767.98px) {
        .analytics-header { padding: 16px; }
        .page-path { max-width: 220px; }
    }
</style>
@endpush

@section('content')
@php
    $formatSeconds = function ($seconds) {
        $seconds = (int) $seconds;

        if ($seconds < 60) {
            return $seconds . ' წმ';
        }

        if ($seconds < 3600) {
            return floor($seconds / 60) . ' წთ ' . ($seconds % 60) . ' წმ';
        }

        return gmdate('H:i:s', $seconds);
    };

    $cleanPath = function ($page) {
        if (!$page) {
            return 'უცნობი გვერდი';
        }

        return parse_url($page, PHP_URL_PATH) ?: $page;
    };

    $consentLabels = [
        'accepted' => 'თანხმობა',
        'rejected' => 'უარი',
        'not_given' => 'არ არის მითითებული',
    ];
@endphp

<div class="analytics-page pb-4">
    <div class="analytics-header mb-4">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div>
                <h1 class="analytics-title">მომხმარებლების ქცევა და შეძენები</h1>
                <div class="analytics-muted mt-2">ნახეთ ვინ შემოდის საიტზე, რომელ გვერდებზე ჩერდება, აქვს თუ არა ქუქიზე თანხმობა და უკავშირდება თუ არა აქტივობა შეკვეთებს.</div>
            </div>
            <a href="{{ route('admin.user.preferences.purchases') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-clockwise"></i> განახლება</a>
        </div>
    </div>

    <form method="GET" class="user-quick-search mb-4">
        <label class="form-label fw-semibold">მომხმარებლის სწრაფი ძებნა</label>
        <div class="input-group">
            <input type="search" name="search" class="form-control" value="{{ request('search') }}" placeholder="სახელი, ელფოსტა, ტელეფონი, ქალაქი, მისამართი ან order ID">
            <input type="hidden" name="consent" value="{{ request('consent', 'all') }}">
            <input type="hidden" name="type" value="{{ request('type') }}">
            <input type="hidden" name="date_from" value="{{ request('date_from') }}">
            <input type="hidden" name="date_to" value="{{ request('date_to') }}">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i>
                ძებნა
            </button>
            @if(request('search'))
                <a href="{{ route('admin.user.preferences.purchases', request()->except(['search', 'page'])) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </div>
        @if(request('search'))
            <div class="analytics-muted mt-2">
                ნაპოვნია {{ $userPreferences->total() }} მომხმარებელი: "{{ request('search') }}"
            </div>
        @endif
    </form>

    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3"><div class="metric-card"><div class="metric-label">მომხმარებლები</div><div class="metric-value">{{ number_format($totals['users'] ?? 0) }}</div><div class="analytics-muted mt-2">{{ $totals['registered'] ?? 0 }} რეგისტრირებული / {{ $totals['guests'] ?? 0 }} სტუმარი</div></div></div>
        <div class="col-6 col-xl-3"><div class="metric-card"><div class="metric-label">ვიზიტები</div><div class="metric-value">{{ number_format($totals['visits'] ?? 0) }}</div><div class="analytics-muted mt-2">{{ number_format($totals['unique_pages'] ?? 0) }} უნიკალური გვერდი</div></div></div>
        <div class="col-6 col-xl-3"><div class="metric-card"><div class="metric-label">ჯამური დრო</div><div class="metric-value">{{ $formatSeconds($totals['time_spent'] ?? 0) }}</div><div class="analytics-muted mt-2">დაგროვილი ჩართულობა</div></div></div>
        <div class="col-6 col-xl-3"><div class="metric-card"><div class="metric-label">შეკვეთები</div><div class="metric-value">{{ number_format($totals['orders'] ?? 0) }}</div><div class="analytics-muted mt-2">{{ number_format($totals['revenue'] ?? 0, 2) }} ლარი</div></div></div>
    </div>

    <form method="GET" class="filter-panel mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-4 col-xl-3">
                <label class="form-label fw-semibold">ძებნა</label>
                <input type="search" name="search" class="form-control" value="{{ request('search') }}" placeholder="სახელი, ელფოსტა, ტელეფონი, ქალაქი, მისამართი ან order ID">
            </div>
            <div class="col-md-4 col-xl-2">
                <label class="form-label fw-semibold">ქუქი</label>
                <select name="consent" class="form-select">
                    <option value="all">ყველა სტატუსი</option>
                    <option value="accepted" {{ request('consent') == 'accepted' ? 'selected' : '' }}>თანხმობა</option>
                    <option value="rejected" {{ request('consent') == 'rejected' ? 'selected' : '' }}>უარი</option>
                    <option value="not_given" {{ request('consent') == 'not_given' ? 'selected' : '' }}>არ არის მითითებული</option>
                </select>
            </div>
            <div class="col-md-4 col-xl-2">
                <label class="form-label fw-semibold">ტიპი</label>
                <select name="type" class="form-select">
                    <option value="">ყველა</option>
                    <option value="registered" {{ request('type') == 'registered' ? 'selected' : '' }}>რეგისტრირებული</option>
                    <option value="guest" {{ request('type') == 'guest' ? 'selected' : '' }}>სტუმარი</option>
                </select>
            </div>
            <div class="col-md-4 col-xl-2"><label class="form-label fw-semibold">თარიღიდან</label><input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}"></div>
            <div class="col-md-4 col-xl-2"><label class="form-label fw-semibold">თარიღამდე</label><input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}"></div>
            <div class="col-md-4 col-xl-1 d-grid"><button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i></button></div>
        </div>
    </form>

    <div class="row g-3 mb-4">
        <div class="col-lg-4"><div class="panel"><div class="panel-title">ქუქის თანხმობა</div><div style="height: 260px;"><canvas id="consentChart"></canvas></div></div></div>
        <div class="col-lg-8"><div class="panel"><div class="panel-title">ყველაზე ხშირად ნანახი გვერდები</div><div style="height: 260px;"><canvas id="userPathChart"></canvas></div></div></div>
    </div>

    <div class="panel">
        <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
            <div><div class="panel-title mb-1">მომხმარებლების სია</div><div class="analytics-muted">დააჭირეთ მომხმარებელს სრული გზის სანახავად.</div></div>
            <div class="analytics-muted">{{ $userPreferences->total() }} ჩანაწერი</div>
        </div>

        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>მომხმარებელი</th>
                    <th>ქუქი</th>
                    <th>ვიზიტები</th>
                    <th>დრო</th>
                    <th>გვერდები</th>
                    <th>შეკვეთები</th>
                    <th>ბოლო აქტივობა</th>
                </tr>
            </thead>
            <tbody>
                @forelse($userPreferences as $pref)
                    <tr>
                        <td>
                            @if(!empty($pref->identifier))
                                <a class="user-cell-title" href="{{ route('admin.user.preferences.journey', $pref->identifier) }}">{{ $pref->label }}</a>
                            @else
                                <span class="text-danger fw-semibold">იდენტიფიკატორი არ არის</span>
                            @endif
                            <div class="analytics-muted mt-1">
                                {{ $pref->type === 'registered' ? 'რეგისტრირებული' : 'სტუმარი' }}
                                @if($pref->phone) · {{ $pref->phone }} @endif
                                @if($pref->city) · {{ $pref->city }} @endif
                            </div>
                            @if($pref->address)
                                <div class="analytics-muted mt-1">{{ $pref->address }}</div>
                            @endif
                        </td>
                        <td>
                            @php
                                $badgeClass = $pref->cookie_consent === 'accepted' ? 'badge-accepted' : ($pref->cookie_consent === 'rejected' ? 'badge-rejected' : 'badge-neutral');
                            @endphp
                            <span class="badge-soft {{ $badgeClass }}">{{ $consentLabels[$pref->cookie_consent] ?? $pref->cookie_consent }}</span>
                        </td>
                        <td><strong>{{ number_format($pref->visits) }}</strong><div class="analytics-muted">{{ number_format($pref->unique_pages) }} უნიკალური</div></td>
                        <td><strong>{{ $formatSeconds($pref->total_time_spent) }}</strong><div class="analytics-muted">საშ. {{ $formatSeconds($pref->average_time_spent) }}</div></td>
                        <td>
                            <div title="{{ $pref->latest_page }}">ბოლო: <span class="page-path">{{ $cleanPath($pref->latest_page) }}</span></div>
                            <div class="analytics-muted" title="{{ $pref->top_page }}">ხშირი: <span class="page-path">{{ $cleanPath($pref->top_page) }}</span></div>
                        </td>
                        <td><strong>{{ number_format($pref->orders_count) }}</strong><div class="analytics-muted">{{ number_format($pref->total_spent, 2) }} ლარი</div></td>
                        <td>{{ $pref->date }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-5"><div class="fw-semibold">მონაცემები ვერ მოიძებნა</div><div class="analytics-muted mt-1">შეცვალეთ ფილტრები ან თარიღის დიაპაზონი.</div></td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">{{ $userPreferences->links('pagination.custom-pagination') }}</div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const consentCanvas = document.getElementById('consentChart');
    const pathCanvas = document.getElementById('userPathChart');

    if (consentCanvas) {
        new Chart(consentCanvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['თანხმობა', 'უარი', 'არ არის მითითებული'],
                datasets: [{
                    data: [{{ $acceptedCount }}, {{ $rejectedCount }}, {{ $notGivenCount ?? 0 }}],
                    backgroundColor: ['#12b76a', '#f04438', '#98a2b3'],
                    borderColor: '#ffffff',
                    borderWidth: 3
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '68%', plugins: { legend: { position: 'bottom' } } }
        });
    }

    fetch("{{ route('admin.user.preferences.chartdata') }}")
        .then(res => res.json())
        .then(data => {
            if (!pathCanvas) return;

            new Chart(pathCanvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: data.map(d => d.page || 'უცნობი'),
                    datasets: [{ label: 'ვიზიტები', data: data.map(d => d.count), backgroundColor: '#2563eb', borderRadius: 6, maxBarThickness: 34 }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { ticks: { autoSkip: false, maxRotation: 25, minRotation: 0 }, grid: { display: false } },
                        y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#eef2f7' } }
                    }
                }
            });
        });
});
</script>
@endpush
