@extends('layouts.app')

@section('title', $auction->book->title)

@section('content')
    <style>
        .list-group-item.border-primary {
            box-shadow: 0 0 4px #0d6efd;
        }
    </style>


@php
    session(['auction_id' => $auction->id]);
@endphp


    <div class="container mt-5">
        <h2>{{ $auction->book->title }}</h2>
        @if (session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif
        <div class="row mt-4">
            <!-- Left column: Photos -->
            <div class="col-md-6">
                <div class="position-relative border rounded shadow-sm p-2 text-center mb-3">
                    @if ($auction->book->photo)
                        <img src="{{ asset('storage/' . $auction->book->photo) }}" alt="{{ $auction->book->title }}"
                            class="img-fluid rounded" id="thumbnailImage" style="cursor: pointer;" data-bs-toggle="modal"
                            data-bs-target="#imageModal" loading="lazy">
                    @else
                        <img src="{{ asset('public/uploads/default-book.jpg') }}" class="img-fluid rounded shadow"
                            loading="lazy">
                    @endif
                </div>

                <!-- Thumbnails -->
                <div class="d-flex flex-wrap gap-2 justify-content-start mt-3">
                    @foreach (['photo', 'photo_2', 'photo_3', 'photo_4'] as $photo)
                        @if ($auction->book->$photo)
                            <img src="{{ asset('storage/' . $auction->book->$photo) }}"
                                class="img-thumbnail small-thumbnail"
                                style="width: 70px; height: 70px; object-fit: cover; cursor: pointer;"
                                onmouseover="updateMainImage('{{ asset('storage/' . $auction->book->$photo) }}')"
                                loading="lazy">
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Right column: Info + bid form + bid history -->
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h4 class="card-title"><i class="bi bi-info-circle"></i> აღწერა</h4>
                        <p class="card-text">{!! $auction->book->description !!}</p>

                        <hr>

                        <p><i class="bi bi-currency-exchange"></i> <strong>საწყისი ფასი:</strong>
                            {{ number_format($auction->start_price, 2) }} ₾</p>
                        <p><i class="bi bi-graph-up-arrow"></i> <strong>მიმდინარე ფასი:</strong>
                            <span id="currentPrice">{{ number_format($auction->current_price, 2) }}</span> ₾
                        </p>
                        <p><i class="bi bi-clock-history"></i> <strong>დასრულების დრო:</strong> <span id="countdown"></span>
                        </p>

                        @if ($auction->is_active)
                            @auth
                                @if (Auth::user()->has_paid_auction_fee)
                                    <form method="POST" action="{{ route('auction.bid', $auction->id) }}" class="mt-3">
                                        @csrf
                                        <label for="bid_amount" class="form-label">გააკეთე ბიჯი:</label>
                                        <input type="number" step="0.01" name="bid_amount" class="form-control mb-2"
                                            id="bidAmount" required>
                                        <input type="hidden" id="minBid" value="{{ $auction->min_bid }}">
                                        <input type="hidden" id="maxBid" value="{{ $auction->max_bid }}">
                                        <input type="hidden" id="currentPrice" value="{{ $auction->current_price }}">
                                        <button type="submit" class="btn btn-primary w-100">დადება</button>
                                    </form>
                                @else
                                    <div class="alert alert-warning mt-3">
                                        @if (Auth::check() && !Auth::user()->has_paid_auction_fee)
                                        <form method="POST" action="{{ route('auction.fee.payment') }}">
                                            @csrf
                                            <input type="hidden" name="auction_id" value="{{ $auction->id }}">
                                            <button type="submit" class="btn btn-warning w-100 mt-2">
                                                აუქციონში მონაწილეობის სიმბოლური ფასი (1 ლარი)
                                            </button>
                                        </form>
                                        
                                        
                                    @endif
                                    </div>
                                @endif
                            @else
                                <p class="text-danger mt-3">
                                    ბიჯის გასაკეთებლად <a href="{{ route('login') }}">შესვლა</a> აუცილებელია. აუქციონში
                                    მონაწილეობის სიმბოლური საფასურია 1 ლარი.
                                </p>
                            @endauth
                        @endif


                        @if (session('success'))
                            <div class="alert alert-success mt-3">{{ session('success') }}</div>
                        @endif


                        @if (Auth::check() && $auction->winner_id === Auth::id() && !$auction->is_paid)
                            <div class="alert alert-success mt-4">
                                🎉 შენ მოიგე აუქციონი!
                                <form action="{{ route('auction.payment') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="auction_id" value="{{ $auction->id }}">
                                    <button type="submit" class="btn btn-success mt-2">💰 გადაიხადე</button>
                                </form>
                            </div>
                        @endif


                        @auth
                            @php
                                $topBid = $auction->bids->sortByDesc('amount')->first();
                            @endphp
                            @if ($topBid && $topBid->user_id === Auth::id())
                                <div class="alert alert-success mt-3">
                                    ✅ შენ ამწუთას ყველაზე მაღალი ბიჯი გაქვს!
                                </div>
                            @endif
                        @endauth

                        <hr>
                        <h5><i class="bi bi-list-ul"></i> ბიჯის ისტორია</h5>
                        @php
                            $sortedBids = $auction->bids->sortByDesc('amount')->values(); // Get top bids by amount
                        @endphp

                        @php
                            $latestBids = $auction->bids->sortByDesc('created_at')->values();
                            $topBidders = $auction->bids->sortByDesc('amount')->pluck('user_id')->take(3)->toArray();
                        @endphp

                        <div id="bidHistory">
                            @include('auction.partials.bid_history', ['auction' => $auction])
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    @if (!$auction->is_active)
        <div class="alert alert-info mt-4">
            @if ($auction->winner_id === optional(Auth::user())->id)
                🎉 You won this auction!
            @elseif($auction->winner_id)
                <p class="mb-0">
                    🏆 <strong>აუქციონი დასრულდა.</strong>
                </p>
                <p>
                    გამარჯვებული:
                    <span class="badge bg-success text-white">{{ $auction->winner->name }} 👑</span>
                </p>
            @else
                This auction ended without any bids.
            @endif
        </div>
    @endif




    <!-- Modal for Enlarged Image -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">{{ $auction->book->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <!-- Left Arrow -->
                    <button class="btn btn-light" id="prevArrow"
                        style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); z-index: 100;">
                        <i class="bi bi-chevron-left"></i>
                    </button>

                    <!-- Modal Image -->
                    <img src="{{ asset('storage/' . $auction->book->photo) }}" alt="{{ $auction->book->title }}"
                        id="modalImage" class="img-fluid" loading="lazy">

                    <!-- Right Arrow -->
                    <button class="btn btn-light" id="nextArrow"
                        style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); z-index: 100;">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    </div>


    <script>
        const endTime = new Date("{{ $auction->end_time }}").getTime();
        const countdownEl = document.getElementById("countdown");

        const timer = setInterval(function() {
            const now = new Date().getTime();
            const distance = endTime - now;

            if (distance <= 0) {
                clearInterval(timer);
                countdownEl.innerHTML = "⛔ აუქციონი დასრულებულია";
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            countdownEl.innerHTML =
                (days > 0 ? days + " დღე " : "") +
                hours + ":" +
                minutes + ":" +
                seconds + "";
        }, 1000);
    </script>


    <script>
        setInterval(function() {
            $.get("{{ route('auction.bids', $auction->id) }}", function(data) {
                // Only inject if data starts with <ul> (means it's the correct partial)
                if (typeof data === 'string' && data.trim().startsWith('<ul')) {
                    $('#bidHistory').html(data);
                } else {
                    console.warn('❌ Ignored unexpected layout response in AJAX:', data);
                }
            }).fail(function(xhr) {
                console.error('❌ Failed to load bid history:', xhr.status);
            });
        }, 5000);
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // List of all images to navigate through
            const images = [
                @if ($auction->book->photo)
                    "{{ asset('storage/' . $auction->book->photo) }}",
                @endif
                @if ($auction->book->photo_2)
                    "{{ asset('storage/' . $auction->book->photo_2) }}",
                @endif
                @if ($auction->book->photo_3)
                    "{{ asset('storage/' . $auction->book->photo_3) }}",
                @endif
                @if ($auction->book->photo_4)
                    "{{ asset('storage/' . $auction->book->photo_4) }}",
                @endif
            ];

            let currentIndex = 0; // Track the currently displayed image index

            const modalImage = document.getElementById('modalImage');
            const prevArrow = document.getElementById('prevArrow');
            const nextArrow = document.getElementById('nextArrow');


            const form = document.getElementById('auctionFeeForm');
    if (form) {
        form.addEventListener('submit', function () {
            console.log('🟢 Auction fee form submitted');
        });
    }

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
            const bidForm = document.querySelector('form[action*="auction/bid"]');
            const bidInput = document.getElementById('bidAmount');

            if (bidForm && bidInput) {
                bidForm.addEventListener('submit', function(e) {
                    const enteredBid = parseFloat(bidInput.value);
                    const minBid = parseFloat(document.getElementById('minBid').value) || 0;
                    const maxBid = parseFloat(document.getElementById('maxBid').value) || Infinity;
                    const currentPrice = parseFloat(document.getElementById('currentPrice').value);

                    if (enteredBid < minBid) {
                        if (!confirm(`მინიმალური ბიჯის თანხაა ${minBid} ₾. დარწმუნებული ხარ რომ გსურს გაგზავნა?`)) {
                            e.preventDefault(); // Only prevent if user cancels
                        }
                    }

                    if (enteredBid <= currentPrice) {
                        if (!confirm(
                                `შენი ბიჯი უნდა იყოს მეტი ვიდრე მიმდინარე ფასი (${currentPrice} ₾). მაინც გააგრძელო?`
                                )) {
                            e.preventDefault();
                        }
                    }

                    if (enteredBid > maxBid) {
                        if (!confirm(
                            `ბიჯი არ უნდა აღემატებოდეს მაქსიმალურ ზღვარს (${maxBid} ₾). მაინც გაგზავნო?`)) {
                            e.preventDefault();
                        }
                    }

                });
            }

        }
    </script>

    <script>
        setTimeout(() => {
            const myBid = document.querySelector('.fw-bold.border-primary');
            if (myBid) {
                myBid.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        }, 500); // wait for DOM to render
    </script>


    <script>
        function updateMainImage(imageUrl) {
            const mainImage = document.getElementById('thumbnailImage');
            const modalImage = document.getElementById('modalImage');
            mainImage.src = imageUrl;
            modalImage.src = imageUrl;
        }
    </script>


    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let errors = @json($errors->all());

                if (errors.length > 0) {
                    // Use native alert or replace with SweetAlert later
                    alert("❌ შეცდომა:\n\n" + errors.join('\n'));
                }
            });
        </script>
    @endif

@endsection
