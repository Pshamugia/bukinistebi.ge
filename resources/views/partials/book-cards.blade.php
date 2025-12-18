@foreach ($books as $book)
    <div class="col-lg-3 col-md-4 col-sm-6 col-12" style="position: relative; padding-bottom: 25px;">
        <div class="card book-card shadow-sm" style="border: 1px solid #f0f0f0; border-radius: 8px;">

            <a href="{{ route('full', ['title' => Str::slug($book->title), 'id' => $book->id]) }}" class="card-link">
                <div class="image-container" style="background-image:url('{{ asset('images/default_image.png') }}');">
                    <img src="{{ asset('storage/' . $book->photo) }}"
                         alt="{{ $book->title }}"
                         class="cover img-fluid"
                         style="border-radius: 8px 8px 0 0; object-fit: cover;"
                         onerror="this.onerror=null;this.src='{{ asset('images/default_image.png') }}';">
                </div>
            </a>

            <div class="card-body">
                <h4>{{ \Illuminate\Support\Str::limit($book->title, 18) }}</h4>

                <p class="text-muted mb-2" style="font-size: 14px;">
                    {{ app()->getLocale() === 'en' ? $book->author->name_en : $book->author->name }}
                </p>

                <p style="font-size:18px">{{ number_format($book->price) }} ₾</p>

                @if($book->quantity >= 1)
                    @if (!auth()->check() || auth()->user()->role !== 'publisher')
                        @if (in_array($book->id, $cartItemIds))
                            <button class="btn btn-success toggle-cart-btn w-100"
                                    data-product-id="{{ $book->id }}" data-in-cart="true">
                                <i class="bi bi-check-circle"></i>
                                <span class="cart-btn-text" data-state="added"></span>
                            </button>
                        @else
                            <button class="btn btn-primary toggle-cart-btn w-100"
                                    data-product-id="{{ $book->id }}" data-in-cart="false">
                                <i class="bi bi-cart-plus"></i>
                                <span class="cart-btn-text" data-state="add"></span>
                            </button>
                        @endif
                    @endif
                @else
                    <button class="btn btn-light w-100" disabled>{{ __('messages.outofstock') }}</button>
                @endif
            </div>
        </div>
    </div>
@endforeach
