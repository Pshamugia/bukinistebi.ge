@extends('layouts.app')

@section('title', 'ბუკინისტები | წიგნები') 
 
@section('content')

     
<h5 class="section-title" style="position: relative; margin-bottom:25px; top:-10px; padding-bottom:25px; align-items: left;
    justify-content: left;">     <strong>
        <i class="bi bi-bookmarks-fill"></i> წიგნები
    </strong>
</h5>

    <!-- Featured Books -->
<div class="container mt-5" style="position:relative; margin-top: -15px !important">
  
    <div class="row">
        @foreach ($books as $book)
                <div class="col-md-3" style="position: relative; padding-bottom: 25px;">
                    <div class="card book-card shadow-sm" style="border: 1px solid #f0f0f0; border-radius: 8px;">
                        <a href="{{ route('full', ['title' => Str::slug($book->title), 'id' => $book->id]) }}"
                            class="card-link">
                            <div class="image-container"
                                style="background-image: url('{{ asset('images/default_image.png') }}');">
                                <img src="{{ asset('storage/' . $book->photo) }}" alt="{{ $book->title }}"
                                    class="cover img-fluid" style="border-radius: 8px 8px 0 0; object-fit: cover;"
                                    onerror="this.onerror=null;this.src='{{ asset('images/default_image.png') }}';">
                            </div>
                        </a>
                        <div class="card-body">
                            <h4 class="font-weight-bold">{{ \Illuminate\Support\Str::limit($book->title, 18) }}</h4>
                            {{-- Author --}}
                            <p class="text-muted mb-2" style="font-size: 14px;">
                                <i class="bi bi-person"></i>
                                <a href="{{ route('full_author', ['id' => $book->author_id, 'name' => Str::slug($book->author->name)]) }}"
                                    class="text-decoration-none text-primary">
                                    {{ $book->author->name }}
                                </a>
                            </p>
                            <p style="font-size: 18px; color: #333;">
                                <img src="{{ asset('images/GEL.png') }}" width="23px"> <span
                                    class="text-dark fw-semibold" style="position: relative; top:3px;">
                                    {{ number_format($book->price) }}
                                </span>
                                <span style="position: relative; top:5px">
                                    @if ($book->quantity == 0)
                                        <span class="badge bg-danger" style="font-weight: 100; float: right;">მარაგი ამოწურულია</span>
                                    @elseif($book->quantity == 1)
                                        <span class="badge bg-warning text-dark"
                                            style="font-size: 13px; font-weight: 100; float: right;">მარაგშია</span>
                                    @else
                                        <span class="badge bg-success"
                                            style="font-size: 13px; font-weight: 100; float: right;">მარაგშია
                                            {{ $book->quantity }} ცალი</span>
                                    @endif
                                </span>
                            </p>

                            {{-- Cart Buttons --}}
                            @if (!auth()->check() || auth()->user()->role !== 'publisher')
                            @if (in_array($book->id, $cartItemIds))
                                <button class="btn btn-success toggle-cart-btn w-100"
                                    data-product-id="{{ $book->id }}" data-in-cart="true">
                                    <i class="bi bi-check-circle"></i> დამატებულია
                                </button>
                            @else
                                <button class="btn btn-primary toggle-cart-btn w-100"
                                    data-product-id="{{ $book->id }}" data-in-cart="false">
                                    <i class="bi bi-cart-plus"></i> დაამატე კალათაში
                                </button>
                            @endif
                        @endif
                        </div>
                    </div>
                </div>
            @endforeach

       
    </div>

    <span style="position: relative; top:11px">
    {{ $books->links('pagination.custom-pagination') }} </span>
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
    $(document).ready(function() {
      $('.toggle-cart-btn').click(function() {
          var button = $(this);
          var bookId = button.data('product-id');
          var inCart = button.data('in-cart');
  
          $.ajax({
              url: '{{ route("cart.toggle") }}',
              method: 'POST',
              data: {
                  _token: '{{ csrf_token() }}',
                  book_id: bookId
              },
              success: function(response) {
                  if (response.success) {
                      if (response.action === 'added') {
                          button.removeClass('btn-primary').addClass('btn-success');
                          button.html('<i class="bi bi-check-circle"></i> დამატებულია'); // Adds icon with text
                          button.data('in-cart', true);
                      } else if (response.action === 'removed') {
                          button.removeClass('btn-success').addClass('btn-primary');
                          button.html('<i class="bi bi-cart-plus"></i>  დაამატე კალათაში '); // Adds icon with text
                          button.data('in-cart', false);
                      }
  
                      // Update the cart count in the navbar
                      $('#cart-count').text(response.cart_count);
                  }
              },
              error: function(xhr, status, error) {
                  console.error('AJAX Error:', error);
                  alert('კალათის გამოსაყენებლად გაიარეთ ავტორიზაცია');
              }
          });
      });
  });
  
  
  
  </script>

@endsection
