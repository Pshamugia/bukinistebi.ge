@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
        <div>
            <h2 class="mb-1">გამოცემა (Publishing)</h2>
            <p class="text-muted mb-0">მართეთ publishing.bukinistebi.ge-ზე გამოჩენილი ნამუშევრები და ბლოკები.</p>
        </div>

        <a href="{{ route('admin.publishing.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg"></i> ახალი ჩანაწერი
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th style="width: 110px;">სურათი</th>
                        <th>სათაური</th>
                        <th>კატეგორია</th>
                        <th>მაღაზიის ბმული</th>
                        <th style="width: 170px;">თარიღი</th>
                        <th style="width: 170px;">ქმედებები</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>
                                @if($item->image_1)
                                    <img src="{{ asset('storage/'.$item->image_1) }}" alt="{{ $item->title }}" class="rounded" style="width: 72px; height: 72px; object-fit: cover;">
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $item->title }}</td>
                            <td>{{ $item->category ?: '—' }}</td>
                            <td>
                                @if($item->shop_url)
                                    <a href="{{ $item->shop_url }}" target="_blank" rel="noopener">ბმული</a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $item->created_at?->format('Y-m-d H:i') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.publishing.edit', $item) }}" class="btn btn-primary btn-sm">რედაქტირება</a>
                                    <form method="POST" action="{{ route('admin.publishing.destroy', $item) }}" onsubmit="return confirm('ნამდვილად გსურთ წაშლა?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">წაშლა</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">Publishing ჩანაწერები ჯერ არ არის დამატებული.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $items->links() }}
    </div>
</div>
@endsection
