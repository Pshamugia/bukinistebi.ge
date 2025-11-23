@extends('admin.layouts.app')

@section('title', 'ბუკინისტები')

@section('content')
<style>
    .toggle-more-btn {
        border-radius: 20px;
        transition: all 0.3s ease;
    }
    .toggle-more-btn:hover {
        background-color: #f0f0f0;
        color: #000;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }
</style>

@php
  // Reuse current date filters for all export links
  $range = array_filter([
    'start_date' => request('start_date'),
    'end_date'   => request('end_date'),
  ]);
@endphp

<div class="container" style="position: relative; margin-top:55px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">ბუკინისტები</h1>

        <!-- Export ALL publishers (aggregated) -->
        <a href="{{ route('admin.publishers.export', $range) }}"
           class="btn btn-success">
           ექსპორტი ყველას (XLSX)
        </a>
    </div>

    <form method="GET" action="{{ route('admin.publishers.activity') }}" class="row mb-4">
        <div class="col-md-3">
            <label>ამ თარიღიდან</label>
            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
        </div>
        <div class="col-md-3">
            <label>ამ თარიღამდე</label>
            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>
        <div class="col-md-3 align-self-end d-flex gap-2">
            <button type="submit" class="btn btn-primary">ფილტრი</button>
            <a href="{{ route('admin.publishers.activity') }}" class="btn btn-secondary">გასუფთავება</a>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>სახელი</th>
                <th>საკოტაქტო</th>
                <th>მისამართი</th>
                <th>საბანკო ანგარიში</th>
                <th>ნავაჭრი</th>
                <th>ატვირთული</th>
                <th style="width:240px;">ექსპორტი</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($publishers as $publisher)
                <tr>
                    <td>{{ $loop->iteration }}. {{ $publisher->name }}</td>
                    <td>
                        <i class="bi bi-envelope-fill"></i> {{ $publisher->email }}<br>
                        <i class="bi bi-telephone-fill"></i> {{ $publisher->phone ?? 'N/A' }}
                    </td>
                    <td>{{ $publisher->address ?? 'N/A' }}</td>
                    <td>{{ $publisher->iban ?? 'N/A' }}</td>
                    <td>
                        <strong>{{ number_format($publisher->total_earned, 2) }} ლარი</strong>
                        @if(request('start_date') && request('end_date'))
                            <br><small>{{ request('start_date') }} - {{ request('end_date') }}</small>
                        @endif
                        @if($publisher->total_sold_quantity > 0)
                            <br><span style="color: red; font-weight: bold; font-size: 12px;">
                                {{ $publisher->total_sold_quantity }} წიგნი
                            </span>
                        @endif
                    </td>
                    <td>
                        @if ($publisher->books->isNotEmpty())
                            <ul class="list-group list-group-flush" id="books-list-{{ $publisher->id }}">
                                @foreach ($publisher->books as $index => $book)
                                    <li class="list-group-item d-flex justify-content-between align-items-center book-item {{ $index > 1 ? 'd-none more-item-' . $publisher->id : '' }}">
                                        <div>
                                            <a href="{{ route('full', ['title' => Str::slug($book->title), 'id' => $book->id]) }}" class="text-decoration-none">
                                                {{ $index + 1 }}. {{ $book->title }}
                                            </a>
                                            @php
                                                $soldQuantity = $book->orderItems
                                                    ->filter(function ($item) use ($startDate, $endDate) {
                                                        if ($startDate && $endDate) {
                                                            return $item->created_at >= $startDate && $item->created_at <= $endDate;
                                                        }
                                                        return true;
                                                    })
                                                    ->sum('quantity');
                                            @endphp

                                            @if ($soldQuantity > 0)
                                                <br><span style="color:red;">გაყიდული: {{ $soldQuantity }} ცალი</span>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            @if ($publisher->books->count() > 2)
                                <button type="button" class="btn btn-outline-secondary btn-sm mt-2 toggle-more-btn"
                                        onclick="toggleMore({{ $publisher->id }})" id="more-btn-{{ $publisher->id }}">
                                    მეტის ნახვა <i class="bi bi-chevron-down"></i>
                                </button>
                            @endif
                        @else
                            <span class="text-muted">არ აქვს ატვირთული წიგნები</span>
                        @endif
                    </td>

                    <!-- Export buttons per publisher -->
                    <td class="text-nowrap">
                        <a href="{{ route('admin.publisher.export', ['publisher' => $publisher->id] + $range) }}"
                           class="btn btn-sm btn-success mb-1">
                           სრული გაყიდვები (XLSX)
                        </a>
                        <br>
                        <a href="{{ route('admin.publisher.export.titles', ['publisher' => $publisher->id] + $range) }}"
                           class="btn btn-sm btn-outline-primary">
                           სათაურები – ფასი (XLSX)
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection

<script>
function toggleMore(publisherId) {
    const hiddenItems = document.querySelectorAll('.more-item-' + publisherId);
    const btn = document.getElementById('more-btn-' + publisherId);

    hiddenItems.forEach(el => el.classList.toggle('d-none'));

    if (btn.innerText.includes('მეტის ნახვა')) {
        btn.innerHTML = 'ნაკლების ნახვა <i class="bi bi-chevron-up"></i>';
    } else {
        btn.innerHTML = 'მეტის ნახვა <i class="bi bi-chevron-down"></i>';
    }
}
</script>
