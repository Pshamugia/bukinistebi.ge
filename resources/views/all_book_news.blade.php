@extends('layouts.app')

@section('title', 'ბუკინისტები | ამბები') 
 
@section('content')

     <!-- Hero Section -->
<div class="hero-section" style="background: url('{{ asset('uploads/biblio.jpg') }}') no-repeat center center; background-size: cover; background-attachment: fixed; margin-top:-24px" loading="lazy">
    <div class="hero-content" style="position: relative; padding-top: 20px;">

        

        <h1> <i class="bi bi-bookmarks-fill"></i>{{ __('messages.bookstories')}}</h1> 
 

    </div>
</div>
 

<style>
    /* GRID */
.news-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 22px 28px;
}

/* ITEM */
.news-item {
    display: flex;
    gap: 14px;
    text-decoration: none;
    padding-bottom: 14px;
    border-bottom: 1px solid #e6e6e6;
    transition: background 0.2s ease;
}

.news-item:hover {
    background: rgba(0, 0, 0, 0.02);
}

/* THUMB */
.news-thumb {
    width: 120px;
    height: 120px;
    flex-shrink: 0;
    border-radius: 6px;
    overflow: hidden;
    background: #f4f4f4;
}

.news-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* CONTENT */
.news-content {
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.news-title {
    font-size: 16px;
    font-weight: 600;
    color: #111;
    margin: 0 0 6px 0;
    line-height: 1.35;

    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.news-meta {
    font-size: 13px;
    color: #777;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .news-grid {
        grid-template-columns: 1fr;
    }
}

    </style>

    <!-- news -->
<div class="container mt-5" style="position:relative; ">
  
 <div class="news-grid">
    @foreach ($news as $item)
        <a href="{{ route('full_news', ['title' => Str::slug(app()->getLocale() === 'en' && $item->title_en ? $item->title_en : $item->title), 'id' => $item->id]) }}"
           class="news-item">

            <div class="news-thumb">
                <img
                    src="{{ asset('storage/' . $item->image) }}"
                    alt="{{ $item->title }}"
                    loading="lazy">
            </div>

            <div class="news-content">
                <h4 class="news-title">
                    {{ app()->getLocale() === 'en' && $item->title_en ? $item->title_en : $item->title }}
                </h4>

                <div class="news-meta">
                    <i class="bi bi-book"></i> {{ __('messages.bookstories') }}
                </div>
            </div>
        </a>
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
