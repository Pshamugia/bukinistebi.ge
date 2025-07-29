@extends('layouts.app')

@section('title', $author->name . ' - Author Details')

@section('content')
 
<div class="container mt-5">
  

    <!-- Display Author's Bio or Description if available -->
  

    <h5 style="position: relative; padding-bottom: 25px"> 
        <i class="bi bi-person-circle"></i>
        {{ $author->name }}
    </h5>


<!-- Exclude sold-out checkbox -->
    <div class="mb-4">
        <label>
            <h6>
                <input type="checkbox" id="excludeSoldOut" {{ request('exclude_sold') ? 'checked' : '' }}>
                {{ __('messages.instock') }}
            </h6>
        </label>
    </div>

    <div class="row">
        @foreach($author->books as $book)
        <div class="col-md-4" style="position: relative; padding-bottom: 25px;">
            <div class="card book-card">
                <a href="{{ route('full', ['title' => Str::slug($book->title), 'id' => $book->id]) }}" class="card-link">

            @if (isset($book->photo))
                <img src="{{ asset('storage/' . $book->photo) }}" alt="{{ $book->title }}" class="cover" id="im">
            @endif
                </a>
            <div class="card-body">
                <h5 class="card-title">{{ app()->getLocale() === 'en' && $book->title_en ? $book->title_en : $book->title }}</h5>
                <p class="card-text">{{ number_format($book->price) }} {{ __('messages.lari')}}
                
                    <span style="position: relative; top:5px">
                        @if($book->quantity == 0)
                        <span style="font-size: 13px; float: right; color:red"> <i class="bi bi-x-circle text-danger"></i> მარაგი ამოწურულია</span>
 @elseif($book->quantity >= 1)
 <span style="font-size: 13px; float: right;">{{ __('messages.available')}}</span>
 
 @endif
                        </span>
                </p>

                @if (in_array($book->id, $cartItemIds))
    <button class="btn btn-success toggle-cart-btn" data-product-id="{{ $book->id }}" data-in-cart="true">
        {{ __('messages.added')}}
    </button>
@else
    <button class="btn btn-primary toggle-cart-btn" data-product-id="{{ $book->id }}" data-in-cart="false">
        {{ __('messages.addtocart')}}
    </button>
@endif
 

            </div>
        </div>
    </div>
        @endforeach
    </div>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
                          button.text('დამატებულია');
                          button.data('in-cart', true);
                      } else if (response.action === 'removed') {
                          button.removeClass('btn-success').addClass('btn-primary');
                          button.text('დაამატე კალათაში');
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