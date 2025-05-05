@extends('layouts.app')

@section('title', 'Bukinistebi.ge - ონლაინ მაღაზია')

@section('content')

<!-- Hero Section -->
<div class="hero-section" style="background: url('{{ asset('uploads/book9.webp') }}') no-repeat center center; background-size: cover; background-attachment: fixed; top:-74px">
    <div class="hero-content" style="position: relative; padding-top: 15px;">

        

        <h1>ბუკინისტური მაღაზია</h1> 
        <h5><a href="{{ route('books')}}" class="btn btn-outline-light" style="font-size: 18px">რასაც ეძებ - აქაა</a></h5>

    </div>
</div>
 
<!-- Featured Books -->
<div class="container mt-5">
        
            <div class="hr-with-text" style="position: relative; margin-top:-54px; top:-10px">
                <h2 style="position: relative; font-size: 26px; ">
                    
               ახალი დამატებული  </h2>
                </div>
            
            
        
  
    <div class="row">
        @foreach ($books as $book)
        <div class="col-md-3" style="position: relative; padding-bottom: 25px;">
            <div class="card book-card shadow-sm" style="border: 1px solid #f0f0f0; border-radius: 8px;">
                <a href="{{ route('full', ['title' => Str::slug($book->title), 'id' => $book->id]) }}" class="card-link">
                    <div class="image-container" style="background-image: url('{{ asset('images/default_image.png') }}');">
                        <img src="{{ asset('storage/' . $book->photo) }}" 
                             alt="{{ $book->title }}" 
                             class="cover img-fluid" 
                             style="border-radius: 8px 8px 0 0; object-fit: cover;" 
                             onerror="this.onerror=null;this.src='{{ asset('images/default_image.png') }}';">
                    </div>
                </a>
                <div class="card-body">
                    <h4 class="text-primary font-weight-bold">{{ \Illuminate\Support\Str::limit($book->title, 18) }}</h4>
                    <p style="font-size: 14px; color: #555;">
                        <a href="{{ route('full_author', ['id' => $book->author_id, 'name' => Str::slug($book->author->name)]) }}" style="text-decoration: none; color: #007bff;">
                            <span>  {{ $book->author->name }} </span>
                        </a>
                    </p>
                    <p style="font-size: 18px; color: #333;">
                       <strong> {{ number_format($book->price) }} <a style="color: #b8b5b5;">&#x20BE; </strong></a> 
                       <span style="position: relative; top:5px">
                       @if($book->quantity == 0)
                       <span style="font-size: 13px; float: right; color:red">მარაგი ამოწურულია</span>
@elseif($book->quantity == 1)
<span style="font-size: 13px; float: right;">მარაგშია</span>
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
 
</div>

<!-- Overlay Section -->

<div class="overlay-section">
    <div class="fixed-background" style="background: url('{{ asset('uploads/book1.webp') }}') no-repeat center center; background-size: cover; background-attachment: fixed;"></div>
    <div class="overlay-content">
        <h2>გახდი ჩვენი პარტნიორი</h2>
        <p><span style="font-size: 20px"> გაყიდე ბუკინისტური წიგნები ჩვენი პლატფორმიდან </span></p>  
        <h2>

         

            @if(Auth::check() && Auth::user()->role === 'publisher')
                <a href="{{ route('publisher.dashboard') }}" class="btn btn-outline-light" style="font-size: 2vw">
                    ბუკინისტის  ოთახი
                </a>
            @else
                <a href="{{ route('login.publisher') }}" class="btn btn-outline-light" style="font-size: 2vw">
                    დარეგისტრირდი
                </a>
            @endif
        </h2>
    </div>
</div>
 

<!-- New Sections: Book News and Popular Books -->
<div class="container mt-5">
    <div class="row">
        <!-- Book News (Two Columns) -->
        <div class="col-md-8">
            <div class="hr-with-text" style="position: relative; top:-12px">
                <h2 style="position: relative; font-size: 26px;">
                    ბუკინისტური ამბები
                </h2>
            </div>
        
            <div class="row">
                @foreach($news as $item)
                <div class="col-md-6 col-lg-6"> <!-- Adjusted columns for responsiveness -->
                    <div class="card mb-4 shadow-sm border-0"> <!-- Added shadow and border styling -->
                        <a href="{{ route('full_news', ['title' => Str::slug($item->title), 'id' => $item->id]) }}" class="card-link text-decoration-none">
                            @if (isset($item->image))
                                <div class="image-container">
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" loading="lazy" class="card-img-top rounded-top img-fluid cover_news" id="im_news">
                                </div>
                            @endif
                            <div class="card-body">
                                <h4 class="card-title text-dark">{{ $item->title }}</h4> <!-- Limit title length -->
                            </div>
                        </a>
                    </div>
                </div>
                
                @endforeach
                
            </div>
        
        
            <!-- "Read All Book News" Button -->
            <div class="text-center mt-4" style="position: relative; top:-22px">
                <a href="{{ route('allbooksnews') }}" class="btn  btn-outline-secondary btn-lg" style="font-size: 18px; ">
                  <span>  <i class="bi bi-newspaper"></i> წაიკითხე მეტი </span>
                </a>
            </div>
        </div>
        
      
        <!-- Popular Books (One Block) -->
<div class="col-md-4">
    <h5 class="section-title" style="position: relative; margin-bottom: 20px; padding-bottom:20px; align-items: left;
    justify-content: left;"> 
     <i class="bi bi-fire"></i> ხშირად ნანახი</h5>
    <div class="card mb-3 p-3">
        @foreach($topRatedArticle as $book) <!-- ბესტსელერებზე უნდა იყოს $topBooks -->
    <div class="popular-book-item mb-2">
        <div class="book-details">
            <a href="{{ route('full', ['title' => Str::slug($book->title), 'id' => $book->id]) }}" class="card-link" style="text-decoration: none">
                
                @if (isset($book->photo))
                    <img src="{{ asset('storage/' . $book->photo) }}" 
                         alt="{{ $book->title }}" 
                         class="cover img-fluid" 
                         style="border-radius: 4px; object-fit: cover; height: 50px; width: 50px; float:left; padding-right: 10px" 
                         >
                @endif

                <span class="book-title" style="color:black">{{ $book->title }}</span> <br>

                <span>{{ $book->author->name }}</span>
            </a>
        </div>
    </div>
    
    @if (!$loop->last)
        <hr> <!-- Add horizontal line between books -->
    @endif
@endforeach
    </div>
</div>

        </div>
    </div>
</div>


<!-- Cookie Consent Notification -->
 


<!-- cookie consent HTML -->
<div id="cookie-consent" style="display: none; position: fixed; bottom: 0; left: 0; right: 0; background: #f8d7da; padding: 10px; text-align: center; z-index: 9999;">
    <span>{{ __('ვებსაიტი იყენებს ქუქი ჩანაწერებს, რათა გავაუმჯობესოთ მომხმარებლის გამოცდილება') }}</span>
    <button id="accept-cookies" style="margin-left: 10px;">{{ __('თანხმობა') }}</button>
    <button id="reject-cookies" style="margin-left: 10px;">{{ __('უარყოფა') }}</button>
</div>

<!-- Script to control the behavior of the consent popup -->
<script>document.addEventListener('DOMContentLoaded', function () {
    var userId = {{ Auth::check() ? Auth::id() : 'null' }};  // Get user ID if logged in

    const consentPopup = document.getElementById('cookie-consent');  // Get the consent popup

    if (userId !== null && !getCookie('cookie_consent')) {  // Check if user is logged in and no consent cookie
        console.log("Cookie consent not found, showing popup.");
        consentPopup.style.display = 'block';  // Show the popup
    } else {
        console.log("Cookie consent found, popup hidden.");
    }

    const acceptButton = document.getElementById('accept-cookies');
    const rejectButton = document.getElementById('reject-cookies');

    acceptButton.addEventListener('click', function () {
        setCookie('cookie_consent', 'accepted', 30);
        console.log('Accepted cookies. Cookie set as "accepted".');
        consentPopup.style.display = 'none';
        sendConsentToBackend('accepted');
    });

    rejectButton.addEventListener('click', function () {
        setCookie('cookie_consent', 'rejected', 30);
        console.log('Rejected cookies. Cookie set as "rejected".');
        consentPopup.style.display = 'none';
        sendConsentToBackend('rejected');
    });

    // Function to set a cookie
    function setCookie(name, value, days) {
    const d = new Date();
    d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000)); // Set expiration time
    const expires = "expires=" + d.toUTCString();
    document.cookie = name + "=" + value + ";" + expires + ";path=/"; // Set cookie for entire domain
    console.log(`Cookie set: ${name}=${value}`);  // Log the cookie value
}

    // Function to get a cookie value
    function getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i].trim();
            if (c.indexOf(nameEQ) === 0) {
                console.log(`Cookie found: ${name}=${c.substring(nameEQ.length, c.length)}`);
                return c.substring(nameEQ.length, c.length); // Return cookie value
            }
        }
        console.log("Cookie not found.");
        return null; // Return null if cookie not found
    }
});
</script>

@endsection


@section('scripts')



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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