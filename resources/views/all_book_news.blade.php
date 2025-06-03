@extends('layouts.app')

@section('title', 'ბუკინისტები | ამბები') 
 
@section('content')

     <!-- Hero Section -->
<div class="hero-section" style="background: url('{{ asset('uploads/biblio.jpg') }}') no-repeat center center; background-size: cover; background-attachment: fixed; margin-top:-74px" loading="lazy">
    <div class="hero-content" style="position: relative; padding-top: 20px;">

        

        <h1> <i class="bi bi-bookmarks-fill"></i>{{ __('messages.bookstories')}}</h1> 
 

    </div>
</div>
 

    <!-- Featured Books -->
<div class="container mt-5" style="position:relative; ">
  
    <div class="row">
        @foreach($news as $item)
        <div class="col-md-6 col-lg-6"> <!-- Adjusted columns for responsiveness -->
            <div class="card mb-4 shadow-sm border-0"> <!-- Added shadow and border styling -->
                <a href="{{ route('full_news', ['title' => Str::slug($item->title), 'id' => $item->id]) }}" class="card-link text-decoration-none">
                    @if (isset($item->image))
                        <div class="image-container">
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="card-img-top rounded-top img-fluid cover_news" id="im_news" loading="lazy">
                        </div>
                    @endif
                    <div class="card-body">
                        <h4 class="card-title text-dark">{{ app()->getLocale() === 'en' ? $item->title_en : $item->title }}</h4> <!-- Limit title length -->
                    </div>
                </a>
            </div>
        </div>
        @endforeach
    </div>
    {{ $news->links('pagination.custom-pagination') }}
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
