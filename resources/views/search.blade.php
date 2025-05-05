@extends('layouts.app')
@section('title', 'ბუკინისტები | ძიება')
@section('content')

<div class="container" style="position: relative; ">
    
    
    <div style="background-color: #b1a9f7; padding:20px 20px 1px 20px; margin-bottom: 26px;">
    <h4> ძიების გაფილტვრა </h4>
    
    <!-- Filter Form -->
    <form action="{{ route('search') }}" method="GET" class="row g-3 mb-4">

        <div class="col-md-2">
            <input class="form-control me-2 styled-input" name="title" type="search" value="{{ request()->get('title') }}" placeholder="საძიებო სიტყვა..." aria-label="Search" id="searchInput">
        </div>

        <div class="col-md-2">
             <input type="number" name="price_from" class="form-control" id="price_from" placeholder="ფასი (დან)" value="{{ request('price_from') }}">
        </div>
        <div class="col-md-2">
             <input type="number" name="price_to" class="form-control" id="price_to" placeholder="ფასი (მდე)" value="{{ request('price_to') }}">
        </div>
        <div class="col-md-3">
            <input type="number" name="publishing_date" class="form-control" id="publishing_date" placeholder="გამოცემის წელი" min="1800" max="{{ date('Y') }}" value="{{ request('publishing_date') }}">
        </div>
        <div class="col-md-3">

            <select name="genre_id" class="form-select categoria" id="genre_id">
                <option value="" style="color: #ccc"><span>კატეგორია</span></option>
                @foreach ($genres as $genre)
                    <option value="{{ $genre->id }}" {{ request('genre_id') == $genre->id ? 'selected' : '' }}>
                        {{ $genre->name }}
                    </option>
                @endforeach
            </select>

            <script>
                 $('.form-select').chosen({
            no_results_text: "Oops, nothing found!"
        });</script>


       
            
        </div>
        <div class="col-md-12">
            <button type="submit" class="btn btn-danger"><span style="top: 3px !important; position: relative;">
                <i class="bi bi-filter"></i> გაფილტრე </span>
            </button>
        </div>
    </form>
</div>
    <!-- Displaying Search Results -->
    <h4><p> 
        @if ($search_count>0)
        <i class="bi bi-check-square-fill"></i> 
        მოიძებნა 
        <span style="background-color: #dc3545; color:white; padding:4px 0px 0px 3px; border-radius: 3px; margin-right:5px">
            {{ $search_count }} 
        </span> 
            @if ($search_count == 1)
           მასალა
            @else
            მასალა
            @endif
        @else
        <i class="bi bi-dash-circle-fill"></i> {{ "ბაზაში ვერ მოიძებნა"}} 
            <span style="background-color: rgb(177, 20, 20) !important; color:white; padding:6px;">
                 {{ $searchTerm }} 
            </span>  &nbsp; დარწმუნდი მართლწერის სისწორეში
        @endif
    </p></h4>
 

    <div class="row">
        @foreach ($books as $book)
        <div class="col-md-6" style="position: relative; padding-bottom: 25px">
            <div class="card book-card">
                <a href="{{ route('full', ['title' => Str::slug($book->title), 'id' => $book->id]) }}" class="card-link">
                    @if (isset($book->photo))
                        <img src="{{ asset('storage/' . $book->photo) }}" alt="{{ $book->title }}" class="cover" id="im" loading="lazy">
                    @endif
                </a>
                <div class="card-body">
                    <h4><strong> {{ $book->title }} </strong></h4>
                    <p style="font-size: 14px">
                        <a href="{{ route('full_author', ['id' => $book->author_id, 'name' => Str::slug($book->author->name)])}}" style="text-decoration: none">
                            <span> {{ $book->author->name }} </span>
                        </a> 
                    </p>
                    <p style="font-size: 18px; color: #333;">
                        <strong> {{ number_format($book->price) }} <a style="color: #b8b5b5;">&#x20BE; </strong></a> 
                        <span style="position: relative; top:5px">
                        @if($book->quantity == 0)
                        <span style="font-size: 13px; float: right; color:red"> <i class="bi bi-x-circle text-danger"></i> მარაგი ამოწურულია</span>
 @elseif($book->quantity == 1)
 <span style="font-size: 13px; float: right;">მარაგშია 1 ცალი</span>
 @else
 <span style="font-size: 13px; float: right;">მარაგშია {{ $book->quantity }} ცალი</span>
 @endif
                        </span>  </p>
                    @if (!auth()->check() || auth()->user()->role !== 'publisher')
                        @if (in_array($book->id, $cartItemIds))
                            <button class="btn btn-success toggle-cart-btn w-100" data-product-id="{{ $book->id }}" data-in-cart="true">
                              <i class="bi bi-check-circle"></i> დამატებულია 
                            </button>
                        @else
                            <button class="btn btn-primary toggle-cart-btn w-100" data-product-id="{{ $book->id }}" data-in-cart="false">
                                <i class="bi bi-cart-plus"></i> დაამატე კალათაში  
                            </button>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <br><br>
    {{ $books->links('pagination.custom-pagination') }}
</div>

@endsection

 
@section('scripts')
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
