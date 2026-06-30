@extends('admin.layouts.app')

@section('title', 'წიგნები')

@push('styles')
<style>
    .books-admin-page { color: #172033; padding-bottom: 28px; }
    .books-hero, .books-filter, .books-panel, .books-stat {
        background: #fff;
        border: 1px solid #e6eaf0;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
    }
    .books-hero { padding: 22px; }
    .books-title { font-size: 1.55rem; font-weight: 900; margin: 0; }
    .books-muted { color: #667085; font-size: .92rem; }
    .books-stat { padding: 16px; height: 100%; }
    .books-stat-label { color: #667085; font-size: .76rem; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; }
    .books-stat-value { color: #0f172a; font-size: 1.35rem; font-weight: 900; margin-top: 8px; }
    .books-filter { background: #f8fafc; box-shadow: none; padding: 16px; }
    .books-panel { padding: 0; overflow: hidden; }
    .books-table { margin-bottom: 0; min-width: 980px; }
    .books-table thead th { background: #111827; color: #fff; font-size: .78rem; letter-spacing: .03em; padding: 14px 16px; text-transform: uppercase; white-space: nowrap; }
    .books-table tbody td { border-color: #e6eaf0; padding: 16px; vertical-align: middle; }
    .book-cover { background: #f8fafc; border: 1px solid #e6eaf0; border-radius: 8px; height: 132px; object-fit: cover; width: 94px; }
    .book-cover-placeholder { align-items: center; background: #f2f4f7; border: 1px dashed #cbd5e1; border-radius: 8px; color: #94a3b8; display: flex; height: 132px; justify-content: center; width: 94px; }
    .book-title { color: #0f172a; font-size: 1rem; font-weight: 900; line-height: 1.35; }
    .book-author { color: #475467; margin-top: 5px; }
    .meta-line { color: #667085; font-size: .88rem; margin-top: 6px; }
    .badge-soft { border: 1px solid transparent; border-radius: 999px; display: inline-flex; font-size: .78rem; font-weight: 800; padding: .36rem .55rem; }
    .badge-stock { background: #ecfdf3; border-color: #abefc6; color: #067647; }
    .badge-low { background: #fffaeb; border-color: #fedf89; color: #b54708; }
    .badge-out { background: #fef3f2; border-color: #fecdca; color: #b42318; }
    .badge-hidden { background: #f2f4f7; border-color: #d0d5dd; color: #475467; }
    .action-buttons { display: flex; flex-wrap: wrap; gap: 8px; }
    .action-buttons .btn { align-items: center; display: inline-flex; gap: 5px; min-height: 36px; }
    .empty-state { padding: 46px 20px; text-align: center; }
    .empty-state i { color: #94a3b8; font-size: 2.4rem; }
    @media (max-width: 767.98px) {
        .books-hero { padding: 16px; }
        .books-title { font-size: 1.28rem; }
    }
</style>
@endpush

@section('content')
@php
    $outOfStockCount = $quantityCounts[0] ?? 0;
    $lowStockCount = ($quantityCounts[1] ?? 0) + ($quantityCounts[2] ?? 0) + ($quantityCounts[3] ?? 0);
    $manyStockCount = $quantityCounts['3plus'] ?? 0;
@endphp

<div class="books-admin-page">
    <div class="books-hero mb-4">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div>
                <div class="books-muted mb-1">კატალოგის მართვა</div>
                <h1 class="books-title">წიგნები</h1>
                <div class="books-muted mt-2">მართეთ მარაგი, ხილვადობა, კატეგორიები და წიგნების ძირითადი ინფორმაცია ერთ ადგილას.</div>
            </div>
            <a href="{{ route('admin.books.create') }}" class="btn btn-primary">
                <i class="bi bi-book"></i>
                დაამატე წიგნი
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3"><div class="books-stat"><div class="books-stat-label">ნაჩვენები ჩანაწერები</div><div class="books-stat-value">{{ number_format($books->total()) }}</div><div class="books-muted">მიმდინარე ფილტრებით</div></div></div>
        <div class="col-sm-6 col-xl-3"><div class="books-stat"><div class="books-stat-label">ამოწურულია</div><div class="books-stat-value">{{ number_format($outOfStockCount) }}</div><div class="books-muted">მარაგი 0</div></div></div>
        <div class="col-sm-6 col-xl-3"><div class="books-stat"><div class="books-stat-label">დაბალი მარაგი</div><div class="books-stat-value">{{ number_format($lowStockCount) }}</div><div class="books-muted">1-3 ერთეული</div></div></div>
        <div class="col-sm-6 col-xl-3"><div class="books-stat"><div class="books-stat-label">სტაბილური მარაგი</div><div class="books-stat-value">{{ number_format($manyStockCount) }}</div><div class="books-muted">3+ ერთეული</div></div></div>
    </div>

    <form method="GET" action="{{ route('admin.books.index') }}" class="books-filter mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-4 col-xl-3">
                <label for="quantity" class="form-label fw-semibold">მარაგით ფილტრი</label>
                <select name="quantity" id="quantity" class="form-select">
                    <option value="">ყველა წიგნი</option>
                    <option value="0" {{ request('quantity') == '0' ? 'selected' : '' }}>ამოწურულია ({{ $quantityCounts[0] ?? 0 }})</option>
                    <option value="1" {{ request('quantity') == '1' ? 'selected' : '' }}>მარაგშია 1 ({{ $quantityCounts[1] ?? 0 }})</option>
                    <option value="2" {{ request('quantity') == '2' ? 'selected' : '' }}>მარაგშია 2 ({{ $quantityCounts[2] ?? 0 }})</option>
                    <option value="3" {{ request('quantity') == '3' ? 'selected' : '' }}>მარაგშია 3 ({{ $quantityCounts[3] ?? 0 }})</option>
                    <option value="3plus" {{ request('quantity') == '3plus' ? 'selected' : '' }}>მარაგშია 3+ ({{ $quantityCounts['3plus'] ?? 0 }})</option>
                </select>
            </div>
            <div class="col-md-3 col-xl-2">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="sort" value="views" id="sortViews" {{ request('sort') === 'views' ? 'checked' : '' }}>
                    <label class="form-check-label" for="sortViews">ნახვების მიხედვით</label>
                </div>
            </div>
            <div class="col-md-3 col-xl-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="hidden" value="1" id="showHidden" {{ request('hidden') == '1' ? 'checked' : '' }}>
                    <label class="form-check-label" for="showHidden">მხოლოდ დამალული წიგნები</label>
                </div>
            </div>
            <div class="col-md-2 col-xl-2 d-grid">
                <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> გაფილტვრა</button>
            </div>
            <div class="col-md-2 col-xl-2 d-grid">
                <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i> გასუფთავება</a>
            </div>
        </div>
    </form>

    <div class="books-panel">
        @if ($books->count())
            <table class="table table-hover books-table">
                <thead>
                    <tr>
                        <th>ფოტო</th>
                        <th>წიგნი</th>
                        <th>კატეგორია / მეტა</th>
                        <th>ბუკინისტი</th>
                        <th>სტატუსი</th>
                        <th>ქმედება</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($books as $book)
                        @php
                            $adminImage = $book->thumb_image ?: $book->photo;
                            $stockClass = $book->quantity == 0 ? 'badge-out' : ($book->quantity <= 3 ? 'badge-low' : 'badge-stock');
                            $stockText = $book->quantity == 0 ? __('messages.outofstock') : __('messages.available') . ' ' . $book->quantity . ' ' . __('messages.items');
                        @endphp
                        <tr>
                            <td>
                                @if ($adminImage)
                                    <img src="{{ asset('storage/' . $adminImage) }}" alt="{{ $book->title }}" class="book-cover">
                                @else
                                    <div class="book-cover-placeholder"><i class="bi bi-image"></i></div>
                                @endif
                            </td>
                            <td>
                                <div class="book-title">{{ $book->title }}</div>
                                <div class="book-author">{{ optional($book->author)->name ?? 'უცნობი ავტორი' }}</div>
                                <div class="mt-2 d-flex flex-wrap gap-2">
                                    <span class="badge-soft {{ $stockClass }}">{{ $stockText }}</span>
                                    @if($book->hide)
                                        <span class="badge-soft badge-hidden">დამალულია</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if ($book->genres && $book->genres->count())
                                    <div class="table-name">{{ $book->genres->pluck('name')->implode(', ') }}</div>
                                @else
                                    <div class="books-muted">ჟანრი არ არის მითითებული</div>
                                @endif
                                <div class="meta-line"><i class="bi bi-eye"></i> ნახვა: {{ number_format($book->views) }}</div>
                                @if(isset($book->pages) && $book->pages)
                                    <div class="meta-line">გვერდები: {{ $book->pages }}</div>
                                @endif
                            </td>
                            <td>
                                @if ($book->publisher && $book->publisher->role === 'publisher')
                                    <strong>{{ $book->publisher->name }}</strong>
                                @else
                                    <span class="books-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge-soft {{ $book->hide ? 'badge-hidden' : 'badge-stock' }}">
                                    {{ $book->hide ? 'დამალული' : 'აქტიური' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <form action="{{ route('admin.books.toggleVisibility', $book->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn {{ $book->hide ? 'btn-outline-success' : 'btn-outline-warning' }} btn-sm">
                                            <i class="bi {{ $book->hide ? 'bi-eye' : 'bi-eye-slash' }}"></i>
                                            {{ $book->hide ? 'Show' : 'Hide' }}
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>
                                    @if(auth()->user()->hasAdminPermission('delete.use'))
                                        <form action="{{ route('admin.books.destroy', $book) }}" method="POST" onsubmit="return confirm('Delete this book?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i> Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-3 border-top">{{ $books->appends(request()->query())->links('pagination.custom-pagination') }}</div>
        @else
            <div class="empty-state">
                <i class="bi bi-journal-x"></i>
                <h5 class="mt-3">წიგნები ვერ მოიძებნა</h5>
                <div class="books-muted">შეცვალეთ ფილტრები ან დაამატეთ ახალი წიგნი.</div>
            </div>
        @endif
    </div>
</div>
@endsection
