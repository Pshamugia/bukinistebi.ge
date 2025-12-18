@foreach ($books as $book)
<div class="col-12 col-md-6 col-lg-4" style="position: relative; padding-bottom: 25px">
    <div class="card book-card">
        <a href="{{ route('full', ['title' => Str::slug($book->title), 'id' => $book->id]) }}">
            @if ($book->photo)
                <img src="{{ asset('storage/' . $book->photo) }}" class="cover" id="im">
            @endif
        </a>

        <div class="card-body">
            <h4><strong>{{ $book->title }}</strong></h4>

            <p style="font-size:14px">
                {{ app()->getLocale() === 'en' ? $book->author->name_en : $book->author->name }}
            </p>

            <p style="font-size:18px">
                <strong>{{ number_format($book->price) }} ₾</strong>
            </p>

            @if ($book->quantity >= 1)
                @if (!auth()->check() || auth()->user()->role !== 'publisher')
                    @if (in_array($book->id, $cartItemIds))
                        <button class="btn btn-success toggle-cart-btn w-100"
                                data-product-id="{{ $book->id }}"
                                data-in-cart="true">
                            <i class="bi bi-check-circle"></i>
                            <span class="cart-btn-text" data-state="added"></span>
                        </button>
                    @else
                        <button class="btn btn-primary toggle-cart-btn w-100"
                                data-product-id="{{ $book->id }}"
                                data-in-cart="false">
                            <i class="bi bi-cart-plus"></i>
                            <span class="cart-btn-text" data-state="add"></span>
                        </button>
                    @endif
                @endif
            @else
                <button class="btn btn-light w-100" disabled>
                    {{ __('messages.outofstock') }}
                </button>
            @endif
        </div>
    </div>
</div>
@endforeach
