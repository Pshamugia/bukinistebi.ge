@extends('layouts.app')

@section('title', $book->title)

@section('content')
    <div class="container mt-5" style="position: relative; padding-bottom: 5%">
        <div class="row">
            <!-- Book Image -->
            <div class="col-md-5">

                <!-- Main Image -->
                <div class="main-image-container mb-3">
                    @if ($book->photo)
                        <img src="{{ asset('storage/' . $book->photo) }}" alt="{{ $book->title }}" class="coverFull img-fluid"
                            id="thumbnailImage" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#imageModal"
                            loading="lazy">
                    @else
                        <img src="{{ asset('public/uploads/default-book.jpg') }}" alt="Default Image"
                            class="img-fluid rounded shadow" loading="lazy">
                    @endif
                </div>

                <!-- Thumbnails for Additional Photos -->
                <div class="row g-2">
                    @if ($book->photo)
                        <div class="col-3">
                            <img src="{{ asset('storage/' . $book->photo) }}" height="80px" alt="Main Photo"
                                class="img-thumbnail small-thumbnail" style="cursor: pointer;"
                                onmouseover="updateMainImage('{{ asset('storage/' . $book->photo) }}')" loading="lazy">
                        </div>
                    @endif

                    @if ($book->photo_2)
                        <div class="col-3">
                            <img src="{{ asset('storage/' . $book->photo_2) }}" alt="Additional Photo 1"
                                class="img-thumbnail small-thumbnail" style="cursor: pointer;"
                                onmouseover="updateMainImage('{{ asset('storage/' . $book->photo_2) }}')" loading="lazy">
                        </div>
                    @endif

                    @if ($book->photo_3)
                        <div class="col-3">
                            <img src="{{ asset('storage/' . $book->photo_3) }}" height="30px" alt="Additional Photo 2"
                                class="img-thumbnail small-thumbnail" style="cursor: pointer;"
                                onmouseover="updateMainImage('{{ asset('storage/' . $book->photo_3) }}')" loading="lazy">
                        </div>
                    @endif

                    @if ($book->photo_4)
                        <div class="col-3">
                            <img src="{{ asset('storage/' . $book->photo_4) }}" alt="Additional Photo 3"
                                class="img-thumbnail small-thumbnail" style="cursor: pointer;"
                                onmouseover="updateMainImage('{{ asset('storage/' . $book->photo_4) }}')" loading="lazy">
                        </div>
                    @endif
                </div>




                <div class="share-buttons col-md-12" style="text-align:left; margin-top: 20px; margin-bottom:20px">
                    <!-- Facebook -->
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::fullUrl()) }}"
                        target="_blank" class="btn facebook-btn">
                        <i class="bi bi-facebook"></i>
                    </a>

                    <!-- Twitter -->
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(Request::fullUrl()) }}&text=Check this out!"
                        target="_blank" class="btn twitter-btn">
                        <i class="bi bi-twitter"></i>
                    </a>

                    <!-- WhatsApp -->
                    <a href="https://api.whatsapp.com/send?text=Check this out! {{ urlencode(Request::fullUrl()) }}"
                        target="_blank" class="btn whatsapp-btn">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                </div>


                <!-- Display average rating -->
                <div style="border:1px solid #ccc; border-radius:5px; padding:15px"> 
@if($averageRating)
<p> {{ number_format($averageRating) }} / 5 ({{ $ratingCount }} მომხ.)</p>
@else
<p>ჯერ არაა რეიტინგი.</p>
@endif

<!-- Display individual star ratings (if needed) -->
<div>
@for ($i = 1; $i <= 5; $i++)
    <span style="color: {{ $i <= $averageRating ? 'orange' : 'gray' }};">&#9733;</span>
@endfor
</div>

<!-- Rating Form -->
<form id="rating-form" method="POST" action="{{ route('article.rate', $book->id) }}">
@csrf
<label for="rating">შეფასება:</label>
<input type="radio" name="rating" value="1"> 1
<input type="radio" name="rating" value="2"> 2
<input type="radio" name="rating" value="3"> 3
<input type="radio" name="rating" value="4"> 4
<input type="radio" name="rating" value="5"> 5
<button type="submit">მიბმა</button>
</form>



<!-- JavaScript to check if user is logged in -->
<script>
    document.getElementById('rating-form').addEventListener('submit', function(event) {
        @auth
            // If the user is logged in, submit the form
            return true;
        @else
            // If the user is not logged in, prevent form submission and show a popup
            event.preventDefault();
            alert('თქვენ უნდა გაიაროთ ავტორიზაცია, რათა შეძლოთ შეფასება.');
            window.location.href = "{{ route('login') }}"; // Optionally redirect to login page
            return false;
        @endauth
    });
</script>

            </div>  </div>

            <!-- Book Details -->
            <div class="col-md-7">
                <h2>{{ $book->title }}</h2>
                <p class="text-muted">ავტორი:
                    <a href="{{ route('full_author', ['id' => $book->author_id, 'name' => Str::slug($book->author->name)]) }}"
                        style="text-decoration: none">
                        <span> {{ $book->author->name }} </span>
                    </a>
                </p>
                @if ($book->quantity > 0)
                    <p><strong>
                            <h4><span style="font-size: 20px" id="price">{{ number_format($book->price) }} </span>
                                <span> ლარი </h4> </span>
                        </strong> </p>
                        @else
                        <div class="alert alert-warning mt-3"> <i class="bi bi-x-circle text-danger"></i> ამ წიგნის
                            მარაგი ამოწურულია. შეგიძლიათ ისარგებლოთ <a style="text-decoration: none" href="{{ route('order_us') }}"> შეკვეთის </a> ფუნქციით</div>

                @endif
                <!-- Quantity Selector -->
                <div class="mb-3">
                    <div class="mb-3">
                        <div class="input-group" style="width: 200px; height: 37px;">
                            <button class="btn btn-outline-secondary decrease-quantity btn-sm" type="button">-</button>
                            <input type="text" class="form-control form-control-sm text-center quantity-input"
                                id="quantity" value="{{ $book->quantity > 0 ? 1 : 0 }}" readonly>
                            <button class="btn btn-outline-secondary increase-quantity btn-sm" type="button">+</button>
                        </div>
                        <input type="hidden" id="max-quantity" value="{{ $book->quantity }}">
                        <!-- Hidden input for max quantity -->

                   
                    </div>
                    <input type="hidden" id="max-quantity" value="{{ $book->quantity }}">
                    <!-- Hidden input for max quantity -->

                </div>

                <!-- Add to Cart Button -->
                <!-- Add to Cart Button -->
                @if (!auth()->check() || auth()->user()->role !== 'publisher')
                    @if (in_array($book->id, $cartItemIds))
                        <button class="btn btn-success toggle-cart-btn" data-product-id="{{ $book->id }}"
                            data-in-cart="true" style="width: 200px; font-size: 15px">
                            <i class="bi bi-check-circle"></i> დამატებულია
                        </button>
                    @else
                        <button class="btn btn-primary toggle-cart-btn" data-product-id="{{ $book->id }}"
                            data-in-cart="false" style="width: 200px; font-size: 15px">
                            <i class="bi bi-cart-plus"></i> დაამატე კალათაში
                        </button>
                    @endif
                @endif

                <!-- Book Description -->
                <div class="mt-4">
                    <h4 style="position: relative; top: 8px"><i class="bi bi-file-text"></i> აღწერა</h4>
                    <p style="border:1px solid rgb(202, 200, 200); padding: 20px; margin-top:20px; border-radius: 3px">
                        <span>
                            {{ $book->description ?? 'აღწერა არ არის დამატებული.' }}
                        </span>
                    </p>

                    <h4 style="position: relative; top: 8px"><i class="bi bi-clipboard-data"></i> დეტალები </h4>

                    <table class="table table-bordered table-hover" style="margin-top:20px; position: relative;">

                        <tbody>
                            <tr>
                                <td><strong> გვერდების რაოდენობა</strong></td>
                                <td>{{ $book->pages }}</td>
                            </tr>
                            <tr>
                                <td><strong>გამოცემის თარიღი</strong></td>
                                <td>{{ $book->publishing_date }} წელი </td>
                            </tr>
                            <tr>
                                <td><strong>გარეკანი</strong></td>
                                <td>{{ $book->cover }}</td>
                            </tr>
                            <tr>
                                <td><strong>მდგომარეობა</strong></td>
                                <td>{{ $book->status }}</td>
                            </tr>
                        </tbody>
                    </table>
                    @if($book->genres->count())
                    <div class="mt-4">
                      
                    
                        <div class="d-flex flex-wrap gap-2">
                            
                            @foreach($book->genres as $genre)
                                <a href="{{ route('genre.books', ['id' => $genre->id, 'slug' => Str::slug($genre->name)]) }}"
                                   class="text-decoration-none">
                                    <span class="badge rounded-pill bg-light border border-dark text-dark px-3 py-2 shadow-sm">
                                        <i class="bi bi-tag"></i> {{ $genre->name }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    
                @endif
                
                </div>
            </div>
        </div>
    </div>


    <!-- Modal for Enlarged Image -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">{{ $book->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <!-- Left Arrow -->
                    <button class="btn btn-light" id="prevArrow"
                        style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); z-index: 100;">
                        <i class="bi bi-chevron-left"></i>
                    </button>

                    <!-- Modal Image -->
                    <img src="{{ asset('storage/' . $book->photo) }}" alt="{{ $book->title }}" id="modalImage"
                        class="img-fluid" loading="lazy">

                    <!-- Right Arrow -->
                    <button class="btn btn-light" id="nextArrow"
                        style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); z-index: 100;">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // List of all images to navigate through
            const images = [
                @if ($book->photo)
                    "{{ asset('storage/' . $book->photo) }}",
                @endif
                @if ($book->photo_2)
                    "{{ asset('storage/' . $book->photo_2) }}",
                @endif
                @if ($book->photo_3)
                    "{{ asset('storage/' . $book->photo_3) }}",
                @endif
                @if ($book->photo_4)
                    "{{ asset('storage/' . $book->photo_4) }}",
                @endif
            ];

            let currentIndex = 0; // Track the currently displayed image index

            const modalImage = document.getElementById('modalImage');
            const prevArrow = document.getElementById('prevArrow');
            const nextArrow = document.getElementById('nextArrow');

            // Update the modal image source
            function updateModalImage(index) {
                currentIndex = index;
                modalImage.src = images[currentIndex];
            }

            // Handle clicking the left (previous) arrow
            prevArrow.addEventListener('click', function() {
                if (currentIndex > 0) {
                    updateModalImage(currentIndex - 1);
                } else {
                    updateModalImage(images.length - 1); // Loop to the last image
                }
            });

            // Handle clicking the right (next) arrow
            nextArrow.addEventListener('click', function() {
                if (currentIndex < images.length - 1) {
                    updateModalImage(currentIndex + 1);
                } else {
                    updateModalImage(0); // Loop back to the first image
                }
            });

            // Sync modal image with the main image on click
            const thumbnails = document.querySelectorAll('.small-thumbnail');
            thumbnails.forEach((thumbnail, index) => {
                thumbnail.addEventListener('click', function() {
                    updateModalImage(index); // Update modal image to match clicked thumbnail
                });
            });
        });

        /**
         * Updates the main image (hover effect) and sets it for the modal.
         */
        function updateMainImage(imageUrl) {
            const mainImage = document.getElementById('thumbnailImage');
            const modalImage = document.getElementById('modalImage');

            // Update the main image source
            mainImage.src = imageUrl;

            // Update the modal image source to match the main image
            mainImage.onclick = function() {
                modalImage.src = imageUrl;
            };
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function updateMainImage(imageUrl) {
            const mainImage = document.getElementById('thumbnailImage');
            const modalImage = document.getElementById('modalImage');
            mainImage.src = imageUrl;
            modalImage.src = imageUrl;
        }
    </script>
    <!-- Quantity Function Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const maxQuantity = {{ $book->quantity }}; // Maximum quantity from the database
            const pricePerUnit = {{ $book->price }}; // The price per unit from the database

            const quantityInput = document.querySelector('.quantity-input');
            const priceElement = document.getElementById('price');
            const decreaseButton = document.querySelector('.decrease-quantity');
            const increaseButton = document.querySelector('.increase-quantity');

            function updatePrice() {
                const quantity = parseInt(quantityInput.value);
                const totalPrice = pricePerUnit * quantity;
                priceElement.textContent = totalPrice.toFixed();
            }

            increaseButton.addEventListener('click', function() {
                let currentQuantity = parseInt(quantityInput.value);
                if (currentQuantity < maxQuantity) {
                    currentQuantity += 1;
                    quantityInput.value = currentQuantity;
                    updatePrice();
                }
            });

            decreaseButton.addEventListener('click', function() {
                let currentQuantity = parseInt(quantityInput.value);
                if (currentQuantity > 1) {
                    currentQuantity -= 1;
                    quantityInput.value = currentQuantity;
                    updatePrice();
                }
            });

            // Initial price calculation (if the quantity is set initially)
            updatePrice();
        });
    </script>

    <!-- jQuery and CSRF Setup Script -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <!-- Toggle Cart Button Script -->

    <script>
        $(document).ready(function() {
            $('.toggle-cart-btn').click(function() {
                var button = $(this);
                var bookId = button.data('product-id');
                var inCart = button.data('in-cart');
                var quantity = parseInt($('#quantity').val()); // Get the selected quantity

                $.ajax({
                    url: '{{ route('cart.toggle') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        book_id: bookId,
                        quantity: quantity // Pass the quantity to the server
                    },
                    success: function(response) {
                        if (response.success) {
                            if (response.action === 'added') {
                                button.removeClass('btn-primary').addClass('btn-success');
                                button.html('<i class="bi bi-check-circle"></i> დამატებულია');
                                button.data('in-cart', true);
                            } else if (response.action === 'removed') {
                                button.removeClass('btn-success').addClass('btn-primary');
                                button.html(
                                    '<i class="bi bi-cart-plus"></i>  დაამატე კალათაში ');
                                button.data('in-cart', false);
                            }
                            $('#cart-count').text(response.cart_count);
                        }
                    },
                    error: function(xhr) {
                        alert('Please log in to use the cart');
                    }
                });
            });
        });
    </script>

@endsection
