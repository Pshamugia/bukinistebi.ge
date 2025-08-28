@extends('layouts.app')

@section('title', 'ბუკინისტები | სუვენირები')

@section('content')

    <h5 class="section-title"
        style="position: relative; margin-bottom:25px; top:30px; padding-bottom:25px; align-items: left;
    justify-content: left;">
        <strong>
            <i class="bi bi-gift"></i> {{ __('messages.souvenirs') }}
        </strong>
    </h5>



<!-- Featured Books -->
<div class="container mt-5" style="position:relative; margin-top: -15px !important">
    <!-- Controls row: side-by-side on md+, stacked on mobile -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">

<!-- Left: Exclude sold-out -->
<label class="m-0">
<h6 class="m-0">
  <input type="checkbox" id="excludeSoldOut" {{ request('exclude_sold') ? 'checked' : '' }}>
  {{ __('messages.instock') }}
</h6>
</label>

<!-- Right: Sort by Price -->
<div class="d-flex align-items-center gap-2">
 <select id="sortBooks" class="form-select form-select-sm w-auto">
  <option value="">{{ __('messages.sortPriceDefault') ?? 'Default' }}</option>
  <option value="price_asc"  {{ request('sort') === 'price_asc'  ? 'selected' : '' }}>
    {{ __('messages.sorPriceAsc') ?? 'Price: Low to High' }}
  </option>
  <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>
    {{ __('messages.sorPriceDesc') ?? 'Price: High to Low' }}
  </option>
</select>
</div>

</div>
        <div class="row">
            @foreach ($books as $book)
                <div class="col-lg-4 col-md-4 col-sm-6 col-12" style="position: relative; padding-bottom: 25px;">
                    <div class="card book-card shadow-sm" style="border: 1px solid #f0f0f0; border-radius: 8px;">
                        <a href="{{ route('full_souvenir', ['title' => Str::slug($book->title), 'id' => $book->id]) }}"
                            class="card-link">
                            <div class="image-container"
                                style="background-image: url('{{ asset('images/default_image.png') }}');">
                                <img src="{{ asset('storage/' . $book->photo) }}" alt="{{ $book->title }}"
                                    class="cover img-fluid" style="border-radius: 8px 8px 0 0; object-fit: cover;"
                                    onerror="this.onerror=null;this.src='{{ asset('images/default_image.png') }}';">
                            </div>
                        </a>
                        <div class="card-body">
                            <h4 class="font-weight-bold">{{ \Illuminate\Support\Str::limit($book->title, 128) }}</h4>
                           
                            <p style="font-size: 18px; color: #333;">
                                <em style="position: relative; font-style: normal; font-size: 20px; top:3px;"> &#8382; </em>
                                <span class="text-dark fw-semibold" style="position: relative; top:3px;">
                                    {{ number_format($book->price) }}
                                </span>
                                <span style="position: relative; top:5px">
                                    @if ($book->quantity == 0)
                                        <span class="badge bg-danger" style="font-weight: 100; float: right;">მარაგი
                                            ამოწურულია</span>
                                    @elseif($book->quantity >= 1)
                                        <span class="badge bg-success"
                                            style="font-size: 13px; font-weight: 100; float: right;">{{ __('messages.available') }}</span>
                                    @endif
                                </span>
                            </p>

                            {{-- Cart Buttons --}}
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


        </div>

        <span style="position: relative; top:11px">
            {{ $books->appends(request()->query())->links('pagination.custom-pagination') }}
        </span>
    </div>
@endsection


@section('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>






    <script>
        $('#excludeSoldOut').change(function() {
            const url = new URL(window.location.href);
            if ($(this).is(':checked')) {
                url.searchParams.set('exclude_sold', 1);
            } else {
                url.searchParams.delete('exclude_sold');
            }
            window.location.href = url.toString();
        });
    </script>

<script>
    $('#sortBooks').change(function(){
        const url = new URL(window.location.href);
        const sort = $(this).val();
        if (sort) {
            url.searchParams.set('sort', sort);
        } else {
            url.searchParams.delete('sort');
        }
        window.location.href = url.toString();
    });
    </script>
    

@endsection
