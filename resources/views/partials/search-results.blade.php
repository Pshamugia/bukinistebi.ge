@foreach ($books as $index => $book)
                <div class="col-lg-3 col-md-4 col-sm-6 col-12" style="position: relative; padding-bottom: 25px;">
                    <div class="card book-card shadow-sm" style="border: 1px solid #f0f0f0; border-radius: 8px;">
                        <a href="{{ route('full', ['title' => Str::slug($book->title), 'id' => $book->id]) }}{{ request('lang') ? '?lang=' . request('lang') : '' }}"
                            class="card-link">
                            <div class="image-container">
                              <img
    src="{{ asset('storage/' . ($book->thumb_image ?: $book->photo)) }}?v={{ $book->updated_at->timestamp }}"
    alt="{{ $book->title }}"
    class="cover img-fluid"
    style="border-radius: 8px 8px 0 0; object-fit: cover;"
    @if($index < 4)
        loading="eager"
        fetchpriority="high"
    @else
        loading="lazy"
        decoding="async"
    @endif
    width="265"
    height="360"
    sizes="(max-width: 768px) 50vw, 265px"
    onerror="this.onerror=null;this.src='{{ asset('images/default_image.png') }}'; this.alt='Default book image';">




                            </div>
                        </a>
                        <div class="card-body">
                            <h4 class="font-weight-bold">{{ \Illuminate\Support\Str::limit($book->title, 18) }}</h4>
                            {{-- Author --}}
                            <p class="text-muted mb-2" style="font-size: 14px;">
                                <i class="bi bi-person"></i>
                                <a href="{{ route('full_author', ['id' => $book->author_id, 'name' => Str::slug($book->author->name)]) }}{{ request('lang') ? '?lang=' . request('lang') : '' }}"
                                    class="text-decoration-none text-primary">
                                    {{ app()->getLocale() === 'en' ? $book->author->name_en : $book->author->name }}

                                </a>
                            </p>

                            {{-- PRICE --}}

                            <p style="font-size: 18px; color: #333;">
                                @if ($book->new_price)
                                    {{-- New (discounted) price first --}}
                                   <em style="position: relative; font-style: normal; font-size: 20px; top:3px;">&#8382;</em>
                                    <span class="text-dark fw-semibold" style="position: relative; top:3px;">
                                        {{ number_format($book->new_price) }}
                                    </span>
                                    &nbsp;
                                    {{-- Old price after (with strikethrough) --}}
                      
                                    <em class="text-secondary" style="text-decoration: line-through;  font-style: normal;  font-size: 16px; position: relative; top:3px;"> &#8382;
                                        {{ number_format($book->price) }}
                                    </em>
                                @else
                                    {{-- Normal price --}}
                                    <em style="position: relative; font-style: normal; font-size: 20px; top:3px;">&#8382;</em>
                                    <span class="text-dark fw-semibold" style="position: relative; top:3px;">
                                        {{ number_format($book->price) }}
                                    </span>
                                @endif

                                {{-- Availability badge --}}
                                <span style="position: relative; top:5px;">
                                    @if ($book->quantity == 0)
                                        <span class="badge bg-danger" style="font-weight: 100; float: right;">
                                            {{ __('messages.outofstock') }}
                                        </span>
                                    @elseif($book->quantity >= 1)
                                        <span class="badge bg-success"
                                            style="font-size: 13px; font-weight: 100; float: right;">
                                            {{ __('messages.available') }}
                                        </span>
                                    @endif
                                </span>
                            </p>



                            {{-- Cart Buttons --}}
                            @if($book->quantity >= 1)
                            @if (!auth()->check() || auth()->user()->role !== 'publisher')
                                @if (in_array($book->id, $cartItemIds))
                                    <button class="btn btn-success toggle-cart-btn w-100"
                                        data-product-id="{{ $book->id }}" data-in-cart="true">
                                        <i class="bi bi-check-circle"></i> <span class="cart-btn-text"
                                            data-state="added"></span>
                                    </button>
                                @else
                                    <button class="btn btn-primary toggle-cart-btn w-100"
                                        data-product-id="{{ $book->id }}" data-in-cart="false">
                                        <i class="bi bi-cart-plus"></i> <span class="cart-btn-text" data-state="add"></span>
                                    </button>
                                @endif
                            @endif
                            
                            @endif
                            @if ($book->quantity == 0)
                            <button class="btn btn-light w-100" style="color:#b9b9b9 !important"
                            data-product-id="{{ $book->id }}" data-in-cart="false">
                            <i class="bi bi-cart-plus"></i> <span class="cart-btn-text" data-state="add"></span>
                        </button>
                              @endif
                        </div>
                    </div>
                </div>
            @endforeach
