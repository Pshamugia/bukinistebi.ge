@extends('layouts.app')

@section('title', $auction->book->title)

@section('content')
    <style>
        .list-group-item.border-primary {
            box-shadow: 0 0 4px #0d6efd;
        }
        .image-main-wrapper {
    width: 100%;
    height: 400px; /* EXACT old height */
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.main-image {
    max-height: 100%;
    width: auto;
    object-fit: contain;
    transition: none !important;
}

.image-main-wrapper {
    pointer-events: none; /* wrapper ignores clicks */
}

.main-image {
    pointer-events: auto; /* image receives clicks */
}

#imageModal .modal-dialog {
    max-width: 95vw;
}

#imageModal .modal-body {
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
}

#imageModal img {
    max-width: 100%;
    max-height: 85vh;
    width: auto;
    height: auto;
    object-fit: contain;
}


    </style>
 

    @php
        session(['auction_id' => $auction->id]);

        $lastBid = $auction->bids->max('amount');
        $basePrice = $lastBid ?? $auction->start_price;
        $formatPrice = fn ($price) => rtrim(rtrim(number_format((float) $price, 2, '.', ''), '0'), '.');
    @endphp


    <div class="container mt-5" style="position: relative; top:50px;">
        <h2>{{ $auction->book->title }}</h2>
        @if (session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif
        <div class="row mt-4">
            <!-- Left column: Photos -->
            <div class="col-md-6">
@php
    $images = $auction->book->images ?? collect();
    $mainImage =
        $images->first()->path
        ?? $auction->book->photo
        ?? null;
@endphp

<div class="position-relative border rounded shadow-sm p-2 text-center mb-3 image-main-wrapper">
    @if ($mainImage)
        <img src="{{ asset('storage/' . $mainImage) }}"
             id="thumbnailImage"
             class="img-fluid rounded main-image"
             data-bs-toggle="modal"
             data-bs-target="#imageModal"
             style="cursor: pointer;">
    @else
        <img src="{{ asset('public/uploads/default-book.jpg') }}"
             class="img-fluid rounded shadow main-image">
    @endif


   
</div>


                <!-- Thumbnails -->
                <div class="d-flex flex-wrap gap-2 justify-content-start mt-3">

    {{-- NEW auction images --}}
    @foreach ($auction->book->images ?? [] as $img)
        <img src="{{ asset('storage/' . $img->path) }}"
             class="img-thumbnail small-thumbnail"
             style="width: 70px; height: 70px; object-fit: cover; cursor: pointer;"
             onmouseover="updateMainImage('{{ asset('storage/' . $img->path) }}')">
    @endforeach

    {{-- OLD system fallback --}}
    @foreach (['photo', 'photo_2', 'photo_3', 'photo_4'] as $photo)
        @if ($auction->book?->$photo)
            <img src="{{ asset('storage/' . $auction->book->$photo) }}"
                 class="img-thumbnail small-thumbnail"
                 style="width: 70px; height: 70px; object-fit: cover; cursor: pointer;"
                 onmouseover="updateMainImage('{{ asset('storage/' . $auction->book->$photo) }}')">
        @endif
    @endforeach

</div> 


            </div>

            <!-- Right column: Info + bid form + bid history -->
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                     @php
    $hasVideo = !empty($auction->video);
@endphp

<ul class="nav nav-tabs mb-3" role="tablist">
    {{-- DESCRIPTION --}}
    <li class="nav-item" role="presentation">
        <button class="nav-link active"
                data-bs-toggle="tab"
                data-bs-target="#auction-desc"
                type="button"
                role="tab">
            <i class="bi bi-info-circle"></i> აღწერა
        </button>
    </li>

    {{-- RULES --}}
    <li class="nav-item" role="presentation">
        <button class="nav-link"
                data-bs-toggle="tab"
                data-bs-target="#auction-rules"
                type="button"
                role="tab">
            <i class="bi bi-shield-check"></i> წესები
        </button>
    </li>

    {{-- VIDEO (only if exists) --}}
    @if ($hasVideo)
        <li class="nav-item" role="presentation">
            <button class="nav-link"
                    data-bs-toggle="tab"
                    data-bs-target="#auction-video"
                    type="button"
                    role="tab">
                <i class="bi bi-youtube"></i> ვიდეო
            </button>
        </li>
    @endif
</ul>


{{-- CONTENT --}}
<div class="tab-content">

    {{-- DESCRIPTION --}}
    <div class="tab-pane fade show active"
         id="auction-desc"
         role="tabpanel">
        {!! $auction->book->description !!}
    </div>

    {{-- RULES --}}
    <div class="tab-pane fade"
         id="auction-rules"
         role="tabpanel">

        <div class="auction-rules-card mt-4">
            <div class="auction-rules-header">
                <i class="bi bi-shield-check"></i>
                <span>აუქციონის ძირითადი წესები</span>
            </div>

            <ul class="auction-rules-list">
                <li><i class="bi bi-currency-exchange"></i> ბიჯი უნდა იყოს მეტი მიმდინარე ფასზე</li>
                <li><i class="bi bi-clock-history"></i> დასრულების შემდეგ ბიჯის შეცვლა შეუძლებელია</li>
                <li><i class="bi bi-person-check"></i> ანონიმური ბიჯი დაშვებულია</li>
                <li><i class="bi bi-exclamation-triangle"></i> გამარჯვების შემთხვევაში გადახდა სავალდებულოა</li>
            </ul>

            <a href="{{ route('auction.rules') }}"
               class="btn btn-outline-dark btn-sm auction-rules-btn">
                წესების სრულად ნახვა
            </a>
        </div>
    </div>

    {{-- VIDEO --}}
    @if ($hasVideo)
        <div class="tab-pane fade"
             id="auction-video"
             role="tabpanel">

            @php
                preg_match(
                    '~(?:youtube\.com/watch\?v=|youtu\.be/|youtube\.com/shorts/)([A-Za-z0-9_-]{11})~',
                    $auction->video,
                    $matches
                );
                $videoId = $matches[1] ?? null;
            @endphp

            @if ($videoId)
                <div class="ratio ratio-16x9 mt-3">
                    <iframe
                        src="https://www.youtube.com/embed/{{ $videoId }}"
                        allowfullscreen>
                    </iframe>
                </div>
            @else
                <div class="text-danger mt-2">
                    არასწორი YouTube ბმული
                </div>
            @endif
        </div>
    @endif

</div>




                        <hr>

                        <p><i class="bi bi-currency-exchange"></i> <strong>საწყისი ფასი:</strong>
                            {{ $formatPrice($auction->start_price) }} ₾</p>
                        <p><i class="bi bi-graph-up-arrow"></i> <strong>მიმდინარე ფასი:</strong>
{{ $formatPrice($auction->effective_current_price) }} ₾
                        </p>
                        @if($auction->buy_now_price && $auction->is_active)
                            <div class="alert alert-warning border-0 shadow-sm">
                                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
                                    <div>
                                        <strong>⚡ ბლიც-ფასი:</strong>
                                        {{ $formatPrice($auction->buy_now_price) }} ₾
                                        <div class="small text-muted">ბლიც-ფასის გადახდის შემთხვევაში, აუქციონი მომენტალურად სრულდება და ფასის გადამხდელი ცხადდება გამარჯვებულად ვადაზე ადრე.</div>
                                    </div>
                                    @auth
                                        <form method="POST" action="{{ route('auction.buy-now', $auction) }}" onsubmit="return confirm('დარწმუნებული ხარ, რომ გსურს ბლიც-ფასად ყიდვა?')">
                                            @csrf
                                            <button type="submit" class="btn btn-warning fw-semibold">
                                                ⚡ მყისიერი ყიდვა
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-warning fw-semibold">
                                            შესვლა ბლიც-ყიდვისთვის
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        @endif
                        <p><i class="bi bi-clock-history"></i> <strong>დასრულების დრო:</strong> <span id="countdown"></span>
                        </p>

                        @if ($auction->is_active)
                            {{-- status  --}}
                            @auth
                                @if (Auth::user()->paidAuction($auction->id))
@php
    $lastBid = $auction->bids->max('amount');
    $basePrice = $lastBid ?? $auction->start_price;
@endphp
<form method="POST" action="{{ route('auction.bid', $auction->id) }}" class="mt-3" id="bidForm">
    @csrf
    <input type="hidden" name="allow_bid" id="allowBid" value="0">
    <label for="bid_amount" class="form-label">გააკეთე ბიჯი:</label>
    <input type="number" step="0.01" name="bid_amount" class="form-control mb-2" id="bidAmount" min="{{ $basePrice + 0.01 }}" required>
    <input type="hidden" id="minBid" value="{{ $auction->min_bid }}">
    <input type="hidden" id="maxBid" value="{{ $auction->max_bid }}">
    <input type="hidden" id="currentPrice" value="{{ $basePrice }}">

    <div class="form-check mt-2">
        <input class="form-check-input" type="checkbox" name="is_anonymous" value="1" id="isAnonymous">
        <label class="form-check-label" for="isAnonymous">
            ანონიმური ბიჯი
        </label>
    </div>

<button type="submit" class="btn btn-primary w-100 mt-2">დადება</button>
</form>






                                @else
                                    <div class="alert alert-warning mt-3">
                                       @auth
    @php
        $auctionId = $auction->id;
        $isPaid = Auth::user()->paidAuction($auctionId);
        $pending = session('auction_fee_pending') === $auctionId;
    @endphp

    @if (!$isPaid)

        @if ($pending)
            <div class="alert alert-info text-center">
                💳 გადახდა დაწყებულია. თუ ბანკიდან დაბრუნდით გადახდის გარეშე,
                შეგიძლიათ თავიდან სცადოთ.
            </div>
        @endif

        <form method="POST" action="{{ route('auction.fee.payment') }}">
            @csrf
            <input type="hidden" name="auction_id" value="{{ $auctionId }}">
            <button class="btn btn-warning w-100 mt-2">
                აუქციონში მონაწილეობის საფასური (1 ლარი)
            </button>
        </form>

    @else
        <div class="alert alert-success text-center mt-2">
            ✔ აუქციონში მონაწილეობა დადასტურებულია
        </div>
    @endif

@endauth


                                    </div>
                                @endif


                                <div class="alert alert-light border mb-3">

    @if ($auction->is_free_bid)
        <span class="badge bg-success">
            🔓 თავისუფალი ბიჯი (შეზღუდვა არ აქვს)
        </span>
    @else
        <div>
            @if ($auction->min_bid)
                <div>🔽 მინიმალური ბიჯი: <strong>{{ $auction->min_bid }} ₾</strong></div>
            @endif

            @if ($auction->max_bid)
                <div>🔼 მაქსიმალური ბიჯი: <strong>{{ $auction->max_bid }} ₾</strong></div>
            @endif
        </div>
    @endif

</div>

                            @else
                                <div class="alert alert-warning mt-3">
        <i class="bi bi-lock-fill"></i>
        <strong>ბიჯის გასაკეთებლად საჭიროა ავტორიზაცია.</strong>
        <br>
        თუ უკვე რეგისტრირებული ხართ, გაიარეთ ავტორიზაცია. თუ არ ხართ, შეგიძლიათ დარეგისტრირდეთ.
    </div>

    <a href="{{ route('login') }}" class="btn btn-primary w-100 mt-2">
        🔐 შესვლა ბიჯისთვის
    </a>
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
<!-- Modal -->
<div class="modal fade" id="profileWarningModal" tabindex="-1" aria-labelledby="profileWarningModalLabel" aria-hidden="true" style="z-index:1011 !important">
<div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">პროფილის შევსება აუცილებელია</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="დახურვა"></button>
      </div>
      <div class="modal-body">
        აუქციონში მონაწილეობისათვის პროფილის რედაქტირებაში უნდა მიუთითოთ მობილურის ნომერი და საცხოვრებელი მისამართი.
      </div>
      <div class="modal-footer">
        <a href="{{ route('account.edit') }}" class="btn btn-primary">პროფილის რედაქტირება</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">დახურვა</button>
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
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true" style="z-index:200000 !important">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="margin-top:20px;">
                    <h5 class="modal-title" id="imageModalLabel">{{ $auction->book->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <!-- Left Arrow -->
                    <button class="btn btn-dark" id="prevArrow"
                        style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); z-index: 100;">
                        <i class="bi bi-chevron-left"></i>
                    </button>

                    <!-- Modal Image -->
                   <img src="{{ $mainImage ? asset('storage/' . $mainImage) : asset('public/uploads/default-book.jpg') }}"
     id="modalImage"
     class="img-fluid"
     loading="lazy">


                    <!-- Right Arrow -->
                    <button class="btn btn-dark" id="nextArrow"
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
@php
    $photos = [];

    foreach ($auction->book->images ?? [] as $img) {
        $photos[] = asset('storage/' . $img->path);
    }

    foreach (['photo','photo_2','photo_3','photo_4'] as $key) {
        if ($auction->book?->$key) {
            $photos[] = asset('storage/' . $auction->book->$key);
        }
    }

    echo implode(",\n", array_map(fn($p) => '"' . $p . '"', $photos));
@endphp
];



            let currentIndex = 0; // Track the currently displayed image index

            const modalImage = document.getElementById('modalImage');
            const prevArrow = document.getElementById('prevArrow');
            const nextArrow = document.getElementById('nextArrow');


            const form = document.getElementById('auctionFeeForm');
            if (form) {
                form.addEventListener('submit', function() {
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

    mainImage.src = imageUrl;

    mainImage.onclick = function() {
        modalImage.src = imageUrl;
    };
}

             

                   document.addEventListener('DOMContentLoaded', function () {
    const bidForm = document.getElementById('bidForm');

    if (bidForm) {
        bidForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            // ✅ ADD THIS HERE
            const enteredBid = parseFloat(document.getElementById('bidAmount').value);
            const currentPrice = parseFloat(document.getElementById('currentPrice').value);
            const maxBid = parseFloat(document.getElementById('maxBid').value);

            if (enteredBid <= currentPrice) {
                if (!confirm(`შენი ბიჯი უნდა იყოს მეტი ვიდრე მიმდინარე ფასი (${currentPrice} ₾). მაინც გააგრძელო?`)) {
                    return;
                }
            }

            if (enteredBid > maxBid) {
                if (!confirm(`ბიჯი არ უნდა აღემატებოდეს მაქსიმალურ ზღვარს (${maxBid} ₾). მაინც გაგზავნო?`)) {
                    return;
                }
            }

            // Profile check
            try {
                const response = await fetch("{{ url('/check-user-profile') }}");
                const result = await response.json();

                if (result.missing_fields) {
                    const modal = new bootstrap.Modal(document.getElementById('profileWarningModal'));
                    modal.show();
                } else {
                    bidForm.submit(); // ✅ All good — submit the form
                }
            } catch (error) {
                console.error('Error checking user profile:', error);
                alert("დაფიქსირდა შეცდომა. სცადე მოგვიანებით.");
            }
        });
    }
});
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


    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const bidForm = document.getElementById('bidForm');

        if (bidForm) {
            bidForm.addEventListener('submit', async function (e) {
                e.preventDefault();

                try {
                    const response = await fetch("{{ url('/check-user-profile') }}");
                    const result = await response.json();

                    if (result.missing_fields) {
                        const modal = new bootstrap.Modal(document.getElementById('profileWarningModal'));
                        modal.show();
                    } else {
                        bidForm.submit(); // Continue submission only if profile is complete
                    }
                } catch (error) {
                    console.error('Error checking user profile:', error);
                    alert("დაფიქსირდა შეცდომა. სცადე მოგვიანებით.");
                }
            });
        }
    });
</script>


@endsection
