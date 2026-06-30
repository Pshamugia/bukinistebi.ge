@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@push('styles')
<style>
    .dashboard-page {
        color: #172033;
        max-width: 100%;
        min-width: 0;
        overflow-x: hidden;
        padding-bottom: 28px;
    }
    .dashboard-page * {
        min-width: 0;
    }
    .dashboard-hero, .dashboard-panel, .dashboard-filter, .kpi-card {
        background: #fff;
        border: 1px solid #e6eaf0;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
    }
    .dashboard-hero { padding: 22px; }
    .dashboard-title { font-size: 1.45rem; font-weight: 800; margin: 0; overflow-wrap: anywhere; }
    .dashboard-muted { color: #667085; font-size: .92rem; }
    .kpi-card { padding: 18px; height: 100%; position: relative; overflow: hidden; }
    .kpi-card::after { content: ""; position: absolute; inset: 0 0 auto 0; height: 3px; background: #2563eb; }
    .kpi-label { color: #667085; font-size: .78rem; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; }
    .kpi-value { color: #0f172a; font-size: 1.42rem; font-weight: 900; line-height: 1.15; margin-top: 10px; overflow-wrap: anywhere; }
    .kpi-sub { color: #667085; font-size: .88rem; margin-top: 8px; }
    .dashboard-filter { background: #f8fafc; padding: 16px; box-shadow: none; }
    .dashboard-panel { padding: 18px; height: 100%; }
    .panel-heading { align-items: center; display: flex; justify-content: space-between; gap: 12px; margin-bottom: 16px; }
    .panel-title { font-size: 1.02rem; font-weight: 800; margin: 0; }
    .orders-chart { height: 260px; display: flex; align-items: flex-end; gap: 12px; max-width: 100%; overflow-x: auto; padding: 24px 10px 8px; border-bottom: 1px solid #e6eaf0; }
    .chart-col { flex: 1 0 34px; text-align: center; }
    .chart-track { height: 190px; display: flex; align-items: flex-end; }
    .chart-bar { width: 100%; min-height: 3px; background: linear-gradient(180deg, #3b82f6 0%, #1d4ed8 100%); border-radius: 6px 6px 0 0; }
    .chart-month { color: #667085; font-size: .78rem; margin-top: 9px; }
    .chart-value { color: #1f2937; font-size: .82rem; font-weight: 700; }
    .dashboard-table { margin-bottom: 0; }
    .dashboard-table thead th { background: #f8fafc; color: #475467; font-size: .78rem; letter-spacing: .03em; text-transform: uppercase; white-space: nowrap; }
    .dashboard-table tbody td { color: #1f2937; vertical-align: middle; }
    .rank-badge { align-items: center; background: #eff6ff; border-radius: 999px; color: #1d4ed8; display: inline-flex; font-size: .75rem; font-weight: 800; height: 26px; justify-content: center; width: 26px; }
    .money { color: #0f172a; font-weight: 800; white-space: nowrap; }
    .table-name { font-weight: 700; max-width: 460px; }
    .dashboard-scroll { overflow-x: auto; }
    @media (max-width: 767.98px) {
        .dashboard-hero { padding: 16px; }
        .dashboard-title { font-size: 1.22rem; }
        .dashboard-muted,
        .kpi-sub {
            overflow-wrap: anywhere;
        }
        .orders-chart { gap: 7px; padding-left: 2px; padding-right: 2px; }
        .chart-col { flex-basis: 24px; }
    }
</style>
@endpush

@section('content')
@php
    $monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    $dateRangeText = request('start_date') || request('end_date')
        ? trim((request('start_date') ?: 'დასაწყისი') . ' - ' . (request('end_date') ?: 'დღემდე'))
        : 'ყველა პერიოდი';
@endphp

<div class="dashboard-page">
    <div class="dashboard-hero mb-4">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div>
                <div class="dashboard-muted mb-1">ადმინისტრაციის ანალიტიკა</div>
                <h1 class="dashboard-title">ბუღალტერია და გაყიდვების სურათი</h1>
                <div class="dashboard-muted mt-2">სწრაფად ნახეთ მარაგის ღირებულება, რეალური გაყიდვები, საუკეთესო მომხმარებლები და ბესტსელერები.</div>
            </div>
            <span class="badge text-bg-light border px-3 py-2">{{ $dateRangeText }}</span>
        </div>
    </div>

    @if(auth()->user()->hasAdminPermission(permission: 'orders.manage'))
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3"><div class="kpi-card"><div class="kpi-label">პროდუქციის სრული ფასი</div><div class="kpi-value">{{ number_format($totalValueOfProducts, 2) }} ლარი</div><div class="kpi-sub">მარაგის სრული ღირებულება</div></div></div>
            <div class="col-sm-6 col-xl-3"><div class="kpi-card"><div class="kpi-label">საშუალო ფასი</div><div class="kpi-value">{{ number_format($averagePriceOfProducts, 2) }} ლარი</div><div class="kpi-sub">ერთ პროდუქტზე</div></div></div>
            <div class="col-sm-6 col-xl-3"><div class="kpi-card"><div class="kpi-label">პროდუქციის რაოდენობა</div><div class="kpi-value">{{ number_format($totalQuantityOfProducts) }}</div><div class="kpi-sub">საწყობში არსებული ერთეული</div></div></div>
            <div class="col-sm-6 col-xl-3"><div class="kpi-card"><div class="kpi-label">პოტენციური მოგება</div><div class="kpi-value">{{ number_format($potentialProfit, 2) }} ლარი</div><div class="kpi-sub">acquisition price-ის გამოკლებით</div></div></div>
            <div class="col-sm-6 col-xl-3"><div class="kpi-card"><div class="kpi-label">საშუალო მოგება</div><div class="kpi-value">{{ number_format($averageProfitPerUnit, 2) }} ლარი</div><div class="kpi-sub">ერთ გაყიდულ ერთეულზე</div></div></div>
            <div class="col-sm-6 col-xl-3"><div class="kpi-card"><div class="kpi-label">გაყიდული პროდუქცია</div><div class="kpi-value">{{ number_format($totalPurchasedPrice, 2) }} ლარი</div><div class="kpi-sub">დადასტურებული შეკვეთებიდან</div></div></div>
            <div class="col-sm-6 col-xl-3"><div class="kpi-card"><div class="kpi-label">რეალური მოგება</div><div class="kpi-value">{{ number_format($totalSalesProfit, 2) }} ლარი</div><div class="kpi-sub">გაყიდული პროდუქციიდან</div></div></div>
        </div>
    @endif

    @if(auth()->user()->hasAdminPermission(permission: 'exports.manage'))
        <form action="{{ route('admin') }}" method="GET" class="dashboard-filter mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="start_date" class="form-label fw-semibold">თარიღიდან</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-5">
                    <label for="end_date" class="form-label fw-semibold">თარიღამდე</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> გაფილტვრა</button>
                </div>
            </div>
        </form>
    @endif

    @if(auth()->user()->hasAdminPermission(permission: 'analytics.view'))
        <div class="dashboard-panel mb-4">
            <div class="panel-heading">
                <h2 class="panel-title"><i class="bi bi-bar-chart-fill text-primary"></i> გაყიდვების ჩარტი</h2>
                <span class="dashboard-muted">{{ date('Y') }}</span>
            </div>
            <div class="orders-chart">
                @foreach($ordersData as $index => $ordersCount)
                    <div class="chart-col">
                        <div class="chart-track" title="{{ $monthLabels[$index] }}: {{ $ordersCount }}">
                            <div class="chart-bar" style="height: {{ max(3, round(($ordersCount / $maxOrders) * 190)) }}px;"></div>
                        </div>
                        <div class="chart-month">{{ $monthLabels[$index] }}</div>
                        <div class="chart-value">{{ $ordersCount }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-xl-6">
                <div class="dashboard-panel">
                    <div class="panel-heading"><h2 class="panel-title"><i class="bi bi-people-fill text-primary"></i> Top 10 მომხმარებელი</h2></div>
                    <div class="dashboard-scroll">
                        <table class="table dashboard-table table-hover">
                            <thead><tr><th>#</th><th>სახელი</th><th>შეკვეთები</th><th>ჯამური დანახარჯი</th></tr></thead>
                            <tbody>
                                @foreach ($topCustomers as $customer)
                                    <tr>
                                        <td><span class="rank-badge">{{ $loop->iteration }}</span></td>
                                        <td class="table-name">{{ $customer->name ?? 'Unknown' }}</td>
                                        <td>{{ number_format($customer->total_orders) }}</td>
                                        <td class="money">{{ number_format($customer->total_spent, 2) }} ლარი</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="dashboard-panel">
                    <div class="panel-heading"><h2 class="panel-title"><i class="bi bi-trophy-fill text-primary"></i> Top 10 ბესტსელერი</h2></div>
                    <div class="dashboard-scroll">
                        <table class="table dashboard-table table-hover">
                            <thead><tr><th>#</th><th>დასახელება</th><th>რაოდენობა</th></tr></thead>
                            <tbody>
                                @foreach ($topBooks as $book)
                                    <tr>
                                        <td><span class="rank-badge">{{ $loop->iteration }}</span></td>
                                        <td class="table-name">{{ optional($book->author)->name ?? 'Unknown' }} - {{ $book?->title ?? 'No title available' }}</td>
                                        <td class="money">{{ number_format($book->total_sold) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-panel mb-4">
            <div class="panel-heading"><h2 class="panel-title"><i class="bi bi-star-fill text-primary"></i> Top 10 რეიტინგული წიგნი</h2></div>
            <div class="dashboard-scroll">
                <table class="table dashboard-table table-hover">
                    <thead><tr><th>#</th><th>სათაური</th><th>ხმების რაოდენობა</th><th>საერთო ქულა</th></tr></thead>
                    <tbody>
                        @foreach($topRatedArticles as $topRated)
                            <tr>
                                <td><span class="rank-badge">{{ $loop->iteration }}</span></td>
                                <td class="table-name">{{ $topRated->title ?? 'No title' }}</td>
                                <td>{{ number_format($topRated->rating_count) }}</td>
                                <td class="money">{{ number_format($topRated->total_rating ?? 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
