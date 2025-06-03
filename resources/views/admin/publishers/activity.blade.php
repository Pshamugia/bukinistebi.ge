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
    <div class="container" style="position: relative; margin-top:55px;">
        <h1>ბუკინისტები</h1>
        <table class="table table-bordered  table-hover">
            <thead>
                <tr>
                    <th>სახელი</th>
                    <th>საკოტაქტო</th>
                    <th>მისამართი</th>
                    <th>საბანკო ანგარიში</th>
                    <th>ატვირთული</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($publishers as $publisher)
                    <tr>
                        <td>{{ $loop->iteration }}. {{ $publisher->name }}</td>
                        <td> <i class="bi bi-envelope-fill"></i> {{ $publisher->email }}
                            <br> <i class="bi bi-telephone-fill"></i> {{ $publisher->phone ?? 'N/A' }}
                        </td>

                        <td> {{ $publisher->address ?? 'N/A' }} </td>
                        <td> {{ $publisher->iban ?? 'N/A' }} </td>
                        <td>
                            @if ($publisher->books->isNotEmpty())
                                <ul class="list-group list-group-flush" id="books-list-{{ $publisher->id }}">
                                    @foreach ($publisher->books as $index => $book)
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center book-item {{ $index > 1 ? 'd-none more-item-' . $publisher->id : '' }}">
                                            <a href="{{ route('full', ['title' => Str::slug($book->title), 'id' => $book->id]) }}"
                                                class="text-decoration-none">
                                                {{ $index + 1 }}. {{ $book->title }}
                                            </a>
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

        hiddenItems.forEach(el => {
            el.classList.toggle('d-none');
        });

        if (btn.innerText.includes('მეტის ნახვა')) {
            btn.innerHTML = 'ნაკლების ნახვა <i class="bi bi-chevron-up"></i>';
        } else {
            btn.innerHTML = 'მეტის ნახვა <i class="bi bi-chevron-down"></i>';
        }
    }
</script>

