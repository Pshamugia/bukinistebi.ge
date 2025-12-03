@extends('layouts.app')

@section('title', $book->title)

@section('content')
    @php
        $jsTranslations = [
            'added' => __('messages.added'),
            'addToCart' => __('messages.addtocart'),
            'loginRequired' => __('messages.loginrequired'),
        ];
    @endphp
    <style>
        .book-detail-page .btn-primary {
            background-color: blue !important;
            color: white !important;
        }
    </style>
    <div class="container mt-5 book-detail-page" style="position: relative; padding-bottom: 5%; top:50px;">
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
                <div style="border:1px solid #ccc; border-radius:5px; padding:15px; margin-bottom:20px" class="d-none d-md-block">
                    @if ($averageRating)
                        <p> {{ number_format($averageRating) }} / 5 ({{ $ratingCount }} {{ __('messages.userRating') }})
                        </p>
                    @else
                        <p>{{ __('messages.notRated') }}</p>
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
                        <label for="rating">{{ __('messages.rate') }}</label>
                        <input type="radio" name="rating" value="1"> 1
                        <input type="radio" name="rating" value="2"> 2
                        <input type="radio" name="rating" value="3"> 3
                        <input type="radio" name="rating" value="4"> 4
                        <input type="radio" name="rating" value="5"> 5
                        <button type="submit">{{ __('messages.submit') }}</button>
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

                </div>


                @php
        $partners = [
            [
                'url' => 'https://intelekti.ge/',
                'image' =>
                    app()->getLocale() === 'ka'
                        ? asset('images/partners_logo/Intelekti Publishing_logo-geo.png')
                        : asset('images/partners_logo/Intelekti_Publishing_logo_Eng.png'),
                'alt' => 'Intelekti',
            ],
            [
                'url' => 'https://sulakauri.ge/',
                'image' =>
                    app()->getLocale() === 'en'
                        ? asset('images/partners_logo/sulakauri.png')
                        : asset('images/partners_logo/sulakauri_logo.svg'),
                'alt' => 'Sulakauri',
            ],
            [
                'url' => 'https://www.artanuji.ge/',
                'image' => app()->getLocale() === 'en'
                    ? asset('images/partners_logo/artanuji_logo_en.png')
                    : asset('images/partners_logo/artanuji_logo.png'),
                'alt' => 'Artanuji'
            ],
            [
                'url' => 'https://www.palitral.ge/',
                'image' => app()->getLocale() === 'en'
                    ? asset('images/partners_logo/palitra.png')
                    : asset('images/partners_logo/palitra.png'),
                'alt' => 'Palitra L'
            ],
        ];

        shuffle($partners);
    @endphp

    <!-- Partners Section -->
    <div class="container my-5 d-none d-md-block">
        <div class="hr-with-text text-center mb-4">
            <h2 style="font-size: 26px;">{{ __('messages.ourpartners') }}</h2>
        </div>
        <div class="row justify-content-center align-items-center">

            @foreach ($partners as $partner)
                <div class="col-6 col-md-3 mb-4">
                    <a href="{{ $partner['url'] }}" target="blank">
                        <img src="{{ $partner['image'] }}" alt="{{ $partner['alt'] }}" class="img-fluid partners"
                            style="max-height: 60px;">
                    </a>
                </div>
            @endforeach

        </div>
    </div>
            </div>

            <!-- Book Details -->
            <div class="col-md-7">
                <h2>{{ $book->title }}</h2>
                <p class="text-muted"><span>{{ __('messages.author') }}:</span>
                    <a href="{{ route('full_author', ['id' => $book->author_id, 'name' => Str::slug($book->author->name)]) }}"
                        style="text-decoration: none">
                        @php
                            $authorName =
                                app()->getLocale() === 'en'
                                    ? $book->author->name_en ?? $book->author->name
                                    : $book->author->name;
                        @endphp
                        <span> {{ $authorName }} </span>
                    </a>
                </p>
                <div class="row align-items-start" style="padding-bottom:20px">
                    <!-- Left side -->
                    <div class="col-md-6">
                        @if ($book->quantity > 0)
                            <p> <span id="price" style="font-size: 20px;">{{ number_format($book->price) }} </span>
                                <span> {{ __('messages.lari') }}</span>
                            </p>
                        @else
                            <div class="alert alert-warning mt-3">
                                <i class="bi bi-x-circle text-danger"></i>
                                {{ __('messages.useOrder') }} <a style="text-decoration: none"
                                    href="{{ route('order_us') }}"> {{ __('messages.theOrder') }} </a>
                                {{ __('messages.feature') }}
                            </div>
                        @endif

                        <!-- Quantity Selector -->
                        <div class="mb-3">
                            <div class="input-group" style="width: 200px;">
                                <button class="btn btn-outline-secondary decrease-quantity btn-sm"
                                    type="button">-</button>
                                <input type="text" class="form-control form-control-sm text-center quantity-input"
                                    id="quantity-{{ $book->id }}" value="{{ $book->quantity > 0 ? 1 : 0 }}"
                                    readonly>
                                <button class="btn btn-outline-secondary increase-quantity btn-sm"
                                    type="button">+</button>
                            </div>
                            <input type="hidden" id="max-quantity" value="{{ $book->quantity }}">
                            <div id="quantity-warning" class="text-danger mt-2"
                                style="display: none; opacity: 0; transition: opacity 0.5s;">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                <span id="warning-text"></span>
                            </div>
                        </div>

                        <!-- Add to Cart Button -->
                        @if ($book->quantity >= 1)
                            @if (!auth()->check() || auth()->user()->role !== 'publisher')
                                @if (in_array($book->id, $cartItemIds))
                                    <button class="btn btn-success toggle-cart-btn" data-product-id="{{ $book->id }}"
                                        data-in-cart="true" style="width: 200px; font-size: 15px">
                                        <i class="bi bi-check-circle"></i> <span
                                            class="cart-btn-text">{{ __('messages.added') }}</span>
                                    </button>
                                @else
                                    <button class="btn btn-primary forFull toggle-cart-btn"
                                        data-product-id="{{ $book->id }}" data-in-cart="false"
                                        style="width: 200px; font-size: 14px">
                                        <i class="bi bi-cart-plus"></i> <span
                                            class="cart-btn-text">{{ __('messages.addtocart') }}</span>
                                    </button>
                                @endif

                            @endif
                        @endif


                        @if ($book->quantity >= 1)
                            <!-- Direct Pay Button -->
                            <button class="btn btn-warning mt-2 direct-play-btn" id="direct-pay-toggle"
                                style="width: 200px;">
                                <i class="bi bi-credit-card"></i> {{ __('messages.directPay') }}
                            </button>
                        @endif




                    </div>



                    <!-- Direct Pay Form -->

                    <div class="w-100 mt-4" id="direct-pay-form" style="display: none; ">
                        <form action="{{ route('book.direct.pay') }}" method="POST" id="directCheckoutForm"
                            style="padding: 0 20px; margin-top:-20px !important;  background-color: rgb(253, 205, 71); 
                      border-radius: 5px;">
                            @csrf
                            <div class="text-end" style="top:20px; position: relative;">
                                <button type="button" class="btn-close" aria-label="Close"
                                    onclick="document.getElementById('direct-pay-form').style.display = 'none';"></button>
                            </div>
                            <!-- Hidden book data -->
                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                            <input type="hidden" name="quantity" id="direct-pay-quantity" value="1">

                            <!-- Payment Options -->
                            <h5><strong>{{ __('messages.choosePayment') }}</strong></h5>

                            <!-- Bank Transfer Switch -->
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_bank"
                                    value="bank_transfer" required>
                                <label class="form-check-label" for="payment_bank">
                                    💳 {{ __('messages.payBankTransfer') }}
                                </label>
                            </div>

                            <!-- Courier Switch -->
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="radio" name="payment_method"
                                    id="payment_courier" value="courier" required>
                                <label class="form-check-label" for="payment_courier">
                                    🚚 {{ __('messages.payDelivery') }}
                                </label>
                            </div>




                            <!-- Name -->
                            <div class="mb-3 mt-3">
                                <label>
                                    <h4><strong>{{ __('messages.nameSurname') }}</strong></h4>
                                </label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <!-- Phone -->
                            <div class="mb-3 w-100">
                                <label for="phone" class="form-label">
                                    <h4 style="position:relative; top:12px">
                                        <strong>{{ __('messages.phoneNumber') }}</strong>
                                    </h4>
                                </label>
                                <div style="display: flex;  width: 100%;">
                                    <span
                                        style="background: #f0f0f0; height: 40px; padding: 10px 12px; border: 1px solid #ccc; border-radius: 4px;">+995</span>
                                    <input type="text" id="phone" name="phone" placeholder="5XX XXX XXX"
                                        maxlength="9" required pattern="5\d{8}"
                                        title="Phone number must start with 5 and be 9 digits long" maxlength="9"
                                        required
                                        style="flex: 1; padding: 10px 12px; height: 40px; font-size: 16px; border: 1px solid #ccc; border-radius: 4px;">
                                </div>
                            </div>


                            {{-- optional email for guests --}}
                            @guest
                                <div class="mb-3">
                                    <label for="bundle_phone" class="form-label">
                                        <h4 style="position:relative;top:12px"><strong>{{ __('messages.email') }}</strong> <span style="font-size: 12px">
                                           (არასავალდებულოა, მაგრამ თუ ინვოისის მიღება გსურთ, მიუთითეთ) </span> </h4>
                                      </label>                                    <input type="email" name="email" id="email" class="form-control"
                                        value="{{ old('email') }}">
                                </div>
                            @endguest


                            <!-- City -->
                            <div class="mb-3">
                                <h4 for="city" class="form-label" style="position:relative;">
                                    <strong>{{ __('messages.city') }}</strong>
                                </h4>
                                <style>
                                    .chosen-container-single .chosen-single {
                                        position: relative;
                                        padding-top: 8px !important;
                                        width: 100% !important;
                                        background: white !important;
                                    }

                                    .chosen-container-single .chosen-single div b {
                                        margin-top: 9px;
                                    }
                                </style>
                                <div class="col-md-12 w-100">
                                    <select name="city" class="form-control col-md-12 chosen-select w-100"
                                        id="city" data-placeholder="{{ __('messages.browseCity') }}" required
                                        style="width: 100% !important; top:20px !important;">
                                        <option value="">{{ __('messages.browseCity') }}</option>
                                        <option value="თბილისი">{{ __('messages.tbilisi') }}</option>
                                        <option value="ბათუმი">{{ __('messages.batumi') }}</option>
                                        <option value="ქუთაისი">{{ __('messages.kutaisi') }}</option>
                                        <option value="გურჯაანის მუნიციპალიტეტი">{{ __('messages.gurjaani') }}</option>
                                        <option value="თელავის მუნიციპალიტეტი">{{ __('messages.telavi') }}</option>
                                        <option value="ზუგდიდის მუნიციპალიტეტი">{{ __('messages.zugdidi') }}</option>
                                        <option value="ბაკურიანი">{{ __('messages.bakuriani') }}</option>
                                        <option value="გორის მუნიციპალიტეტი">{{ __('messages.gori') }}</option>
                                        <option value="რუსთავი">{{ __('messages.rustavi') }}</option>
                                        <option value="ფოთი">{{ __('messages.poti') }}</option>
                                        <option value="აბაშის მუნიციპალიტეტი">{{ __('messages.abasha') }}</option>
                                        <option value="ადიგენის მუნიციპალიტეტი">{{ __('messages.adigeni') }}</option>
                                        <option value="ამბროლაურის მუნიციპალიტეტი">{{ __('messages.ambrolauri') }}
                                        </option>
                                        <option value="ასპინძის მუნიციპალიტეტი">{{ __('messages.aspindza') }}</option>
                                        <option value="ახალგორის მუნიციპალიტეტი">{{ __('messages.akhalgori') }}</option>
                                        <option value="ახალქალაქის მუნიციპალიტეტი">{{ __('messages.akhalkalaki') }}
                                        </option>
                                        <option value="ახალციხის მუნიციპალიტეტი">{{ __('messages.akhaltsikhe') }}</option>
                                        <option value="ახმეტის მუნიციპალიტეტი">{{ __('messages.akhmeta') }}</option>
                                        <option value="ბაღდათის მუნიციპალიტეტი">{{ __('messages.bagdati') }}</option>
                                        <option value="ბოლნისის მუნიციპალიტეტი">{{ __('messages.bolnisi') }}</option>
                                        <option value="ბორჯომის მუნიციპალიტეტი">{{ __('messages.borjomi') }}</option>
                                        <option value="გარდაბნის მუნიციპალიტეტი">{{ __('messages.gardabani') }}</option>
                                        <option value="დედოფლისწყაროს მუნიციპალიტეტი">{{ __('messages.dedoflistskaro') }}
                                        </option>
                                        <option value="დმანისის მუნიციპალიტეტი">{{ __('messages.dmanisi') }}</option>
                                        <option value="დუშეთის მუნიციპალიტეტი">{{ __('messages.dusheti') }}</option>
                                        <option value="ვანის მუნიციპალიტეტი">{{ __('messages.vani') }}</option>
                                        <option value="ზესტაფონის მუნიციპალიტეტი">{{ __('messages.zestafoni') }}</option>
                                        <option value="თეთრი წყაროს მუნიციპალიტეტი">{{ __('messages.tetritskaro') }}
                                        </option>
                                        <option value="თერჯოლის მუნიციპალიტეტი">{{ __('messages.terjola') }}</option>
                                        <option value="თიანეთის მუნიციპალიტეტი">{{ __('messages.tianeti') }}</option>
                                        <option value="კასპის მუნიციპალიტეტი">{{ __('messages.kaspi') }}</option>
                                        <option value="ლაგოდეხის მუნიციპალიტეტი">{{ __('messages.lagodekhi') }}</option>
                                        <option value="ლანჩხუთის მუნიციპალიტეტი">{{ __('messages.lanchkhuti') }}</option>
                                        <option value="ლენტეხის მუნიციპალიტეტი">{{ __('messages.lentekhi') }}</option>
                                        <option value="მარნეულის მუნიციპალიტეტი">{{ __('messages.marneuli') }}</option>
                                        <option value="მარტვილის მუნიციპალიტეტი">{{ __('messages.martvili') }}</option>
                                        <option value="მესტიის მუნიციპალიტეტი">{{ __('messages.mestia') }}</option>
                                        <option value="მცხეთის მუნიციპალიტეტი">{{ __('messages.mtskheta') }}</option>
                                        <option value="ნინოწმინდის მუნიციპალიტეტი">{{ __('messages.ninotsminda') }}
                                        </option>
                                        <option value="ოზურგეთის მუნიციპალიტეტი">{{ __('messages.ozurgeti') }}</option>
                                        <option value="ონის მუნიციპალიტეტი">{{ __('messages.oni') }}</option>
                                        <option value="საგარეჯოს მუნიციპალიტეტი">{{ __('messages.sagarejo') }}</option>
                                        <option value="სამტრედიის მუნიციპალიტეტი">{{ __('messages.samtredia') }}</option>
                                        <option value="საჩხერის მუნიციპალიტეტი">{{ __('messages.sachkhere') }}</option>
                                        <option value="სენაკის მუნიციპალიტეტი">{{ __('messages.senaki') }}</option>
                                        <option value="სიღნაღის მუნიციპალიტეტი">{{ __('messages.signagi') }}</option>
                                        <option value="ტყიბულის მუნიციპალიტეტი">{{ __('messages.tkibuli') }}</option>
                                        <option value="ქარელის მუნიციპალიტეტი">{{ __('messages.kareli') }}</option>
                                        <option value="ქედის მუნიციპალიტეტი">{{ __('messages.keda') }}</option>
                                        <option value="ქობულეთის მუნიციპალიტეტი">{{ __('messages.kobuleti') }}</option>
                                        <option value="ყაზბეგის მუნიციპალიტეტი">{{ __('messages.kazbegi') }}</option>
                                        <option value="ყვარლის მუნიციპალიტეტი">{{ __('messages.kvareli') }}</option>
                                        <option value="შუახევის მუნიციპალიტეტი">{{ __('messages.shuakhevi') }}</option>
                                        <option value="ჩოხატაურის მუნიციპალიტეტი">{{ __('messages.chokhatauri') }}
                                        </option>
                                        <option value="ჩხოროწყუს მუნიციპალიტეტი">{{ __('messages.chkhorotsku') }}
                                        </option>
                                        <option value="ცაგერის მუნიციპალიტეტი">{{ __('messages.tsageri') }}</option>
                                        <option value="წალენჯიხის მუნიციპალიტეტი">{{ __('messages.tsalejikha') }}
                                        </option>
                                        <option value="წალკის მუნიციპალიტეტი">{{ __('messages.tsalka') }}</option>
                                        <option value="წყალტუბოს მუნიციპალიტეტი">{{ __('messages.tskaltubo') }}</option>
                                        <option value="ჭიათურის მუნიციპალიტეტი">{{ __('messages.chiatura') }}</option>
                                        <option value="ხარაგაულის მუნიციპალიტეტი">{{ __('messages.kharagauli') }}
                                        </option>
                                        <option value="ხაშურის მუნიციპალიტეტი">{{ __('messages.khashuri') }}</option>
                                        <option value="ხელვაჩაურის მუნიციპალიტეტი">{{ __('messages.khelvachaui') }}
                                        </option>
                                        <option value="ხობის მუნიციპალიტეტი">{{ __('messages.khobi') }}</option>
                                        <option value="ხონის მუნიციპალიტეტი">{{ __('messages.khoni') }}</option>
                                        <option value="ხულოს მუნიციპალიტეტი">{{ __('messages.khulo') }}</option>
                                        <option value="ჯავის მუნიციპალიტეტი">{{ __('messages.java') }}</option>




                                    </select>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="mb-3">
                                <label>
                                    <h4><strong>{{ __('messages.address') }}</strong></h4>
                                </label>
                                <input type="text" name="address" class="form-control" required>
                            </div>

                            <div class="text-center mt-3" style="padding: 20px;">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> {{ __('messages.directPay') }}
                                </button>
                            </div>
                        </form>
                    </div>


                    <!-- Right side: Delivery info -->
                    <div class="col-md-6 deliveryFull">
                        <div class="border rounded p-3 mt-3 mt-md-0"
                            style=" box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;">
                            <span>
                                <p>🚚 <strong>მიწოდება</strong></p>
                                <p>თბილისი: 5 ლარი / 2 სამუშაო დღე</p>
                                <p>რეგიონი: 7 ლარი / 3-5 სამუშაო დღე</p>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Quantity Selector -->






                <!-- Book Description -->
                <div class="mt-4" style="position: relative; top:-20px">
                    <h4 style="position: relative; top: 8px"><i class="bi bi-file-text"></i>
                        {{ __('messages.description') }}</h4>
                    <p style="border:1px solid rgb(226, 226, 226); padding: 20px; margin-top:20px; border-radius: 3px">
                        <span>
                            {{ $book->description ?? 'აღწერა არ არის დამატებული.' }}
                        </span>
                    </p>




                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                                aria-selected="true">
                                <h4 style="position: relative; top: 8px">
                                    <i class="bi bi-clipboard-data"></i>
                                    {{ __('messages.details') }}
                                </h4>
                            </button>
                            @if ($book->video)
                                <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-profile" type="button" role="tab"
                                    aria-controls="nav-profile" aria-selected="false">
                                    <h4 style="position: relative; top: 8px">
                                        <i class="bi bi-youtube"></i>
                                        {{ __('messages.video') }}
                                    </h4>

                                </button>
                            @endif
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                            aria-labelledby="nav-home-tab">
                            <table class="table table-hover" style="margin-top:20px; position: relative; top:-20px;">

                                <tbody style="border:1px solid rgb(226, 226, 226); border-top:none">
                                    <tr>
                                        <td class="nowrap" style="border-top:none !Important"><strong>
                                                {{ __('messages.price') }}</strong></td>
                                        <td><span>{{ number_format($book->price) }} {{ __('messages.lari') }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="nowrap"><strong> {{ __('messages.numberOfPages') }}</strong></td>
                                        <td><span>{{ $book->pages }}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="nowrap"><strong>{{ __('messages.yearofpublicaion') }}</strong></td>
                                        <td><span>{{ $book->publishing_date }}</span> </td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('messages.cover') }}</strong></td>
                                        <td><span>{{ $book->cover }}</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('messages.bookCondition') }}</strong></td>
                                        <td><span>{{ $book->status }}</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                            <br>
                            @if ($book->video)
                                @php
                                    preg_match(
                                        '~(?:youtube\.com/watch\?v=|youtu\.be/|youtube\.com/shorts/)([A-Za-z0-9_-]{11})~',
                                        $book->video,
                                        $matches,
                                    );
                                    $videoId = $matches[1] ?? null;
                                @endphp

                                @if ($videoId)
                                    <div class="ratio ratio-16x9">
                                        <iframe src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen>
                                        </iframe>
                                    </div>
                                @else
                                    <small class="text-danger">არასწორი YouTube ბმული</small>
                                @endif
                            @endif



                        </div>
                    </div>


                    @if ($book->genres->count())
                        <div class="mt-4">

                            <div class="d-flex flex-wrap gap-2 tags">
                                @foreach ($book->genres as $genre)
                                    @php
                                        $genreName =
                                            app()->getLocale() === 'en'
                                                ? $genre->name_en ?? $genre->name
                                                : $genre->name;
                                    @endphp
                                    <a href="{{ route('genre.books', ['id' => $genre->id, 'slug' => Str::slug($genreName)]) }}"
                                        class="text-decoration-none">
                                        <span
                                            class="badge genre-badge bg-light border border-dark text-dark px-3 py-2 shadow-sm">
                                            <i class="bi bi-tag"></i> {{ $genreName }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>


                    @endif

                </div>
            </div>
        </div>



        @if ($relatedBooks->count())
            <div class="container mt-5" style="position: relative; top:-30px">
                <h2 class="mb-3">
                    <i class="bi bi-book-half me-1"></i> {{ __('messages.related') }}
                </h2>
                <div class="row">
                    @foreach ($relatedBooks as $related)
                        <div class="col-md-3" style="position: relative; ">
                            <div class="card book-card shadow-sm" style="border: 1px solid #f0f0f0; border-radius: 8px;">
                                <a href="{{ route('full', ['title' => Str::slug($related->title), 'id' => $related->id]) }}"
                                    class="card-link">
                                    <div class="image-container"
                                        style="background-image: url('{{ asset('images/default_image.png') }}');">
                                        <img src="{{ asset('storage/' . $related->photo) }}"
                                            alt="{{ $related->title }}" class="cover img-fluid"
                                            style="border-radius: 8px 8px 0 0; object-fit: cover;"
                                            onerror="this.onerror=null;this.src='{{ asset('images/default_image.png') }}';">
                                    </div>
                                </a>
                                <div class="card-body">
                                    <h4 class="font-weight-bold">
                                        {{ \Illuminate\Support\Str::limit($related->title, 18) }}
                                    </h4>
                                    {{-- Author --}}
                                    <p class="text-muted mb-2" style="font-size: 14px;">
                                        <i class="bi bi-person"></i>
                                        <a href="{{ route('full_author', ['id' => $related->author_id, 'name' => Str::slug($related->author->name)]) }}"
                                            class="text-decoration-none text-primary">
                                            @php
                                                $relatedAuthorName =
                                                    app()->getLocale() === 'en'
                                                        ? $related->author->name_en ?? $related->author->name
                                                        : $related->author->name;
                                            @endphp
                                            {{ $relatedAuthorName }}
                                        </a>
                                    </p>
                                    <p style="font-size: 18px; color: #333;">
                                        <em style="position: relative; font-style: normal; font-size: 20px; top:3px;">
                                            &#8382; </em> <span class="text-dark fw-semibold"
                                            style="position: relative; top:3px;">
                                            {{ number_format($related->price) }}
                                        </span>
                                        <span style="position: relative; top:5px; ">
                                            @if ($related->quantity == 0)
                                                <span class="badge bg-danger"
                                                    style="font-weight: 100; float: right;">{{ __('messages.outofstock') }}</span>
                                            @elseif($related->quantity == 1)
                                                <span class="badge bg-warning text-dark"
                                                    style="font-size: 13px; font-weight: 100; float: right;">{{ __('messages.available') }}</span>
                                            @else
                                                <span class="badge bg-success"
                                                    style="font-size: 13px; font-weight: 100; float: right;">{{ __('messages.available') }}
                                                    {{ $related->quantity }} {{ __('messages.items') }}</span>
                                            @endif
                                        </span>
                                    </p>

                                    {{-- Cart Buttons --}}
                                    @if (!auth()->check() || auth()->user()->role !== 'publisher')
                                        @if (in_array($related->id, $cartItemIds))
                                            <button class="btn btn-success toggle-cart-btn w-100"
                                                data-product-id="{{ $related->id }}" data-in-cart="true">
                                                <i class="bi bi-check-circle"></i>
                                                <span class="cart-btn-text"
                                                    data-state="added">{{ __('messages.added') }}</span>
                                            </button>
                                        @else
                                            <button class="btn btn-primary toggle-cart-btn w-100"
                                                data-product-id="{{ $related->id }}" data-in-cart="false">
                                                <i class="bi bi-cart-plus"></i>
                                                <span class="cart-btn-text"
                                                    data-state="add">{{ __('messages.addtocart') }}</span>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>


    <!-- Modal for Enlarged Image -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true"
        style="z-index: 9999999999 !important">
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
    </div>


    @push('scripts')
        <!-- JavaScript -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
        <script>
            $('.chosen-select').chosen({
                disable_search_threshold: 10,
                no_results_text: "{{ __('messages.nocityfound') ?? 'No results matched' }}",
                width: '100%' // Ensure full width
            });
        </script>
        <script>
            const translations = {
                added: @json(__('messages.added')),
                addToCart: @json(__('messages.addtocart'))
            };
        </script>

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


            $(document).ready(function() {


                $('#direct-pay-toggle').click(function() {
                    $('#direct-pay-form').slideToggle();

                    // Sync quantity to hidden input
                    $('#direct-pay-quantity').val($('#quantity-{{ $book->id }}').val());
                });

                // Allow only digits in phone input
                $('#phone').on('input', function() {
                    this.value = this.value.replace(/\D/g, '');
                });

            });
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
                const maxQuantity = {{ $book->quantity }}; // Max from DB
                const pricePerUnit = {{ $book->price }}; // Price per unit

                const quantityInput = document.querySelector('.quantity-input');
                const priceElement = document.getElementById('price');
                const decreaseButton = document.querySelector('.decrease-quantity');
                const increaseButton = document.querySelector('.increase-quantity');
                const warningDiv = document.getElementById('quantity-warning');
                const warningText = document.getElementById('warning-text');

                function updatePrice() {
                    const quantity = parseInt(quantityInput.value);
                    const totalPrice = pricePerUnit * quantity;
                    priceElement.textContent = totalPrice.toFixed();
                }

                function showWarning(text) {
                    warningText.textContent = text;
                    warningDiv.style.display = 'block';
                    setTimeout(() => {
                        warningDiv.style.opacity = 1;
                    }, 10); // Short delay to trigger CSS transition
                }

                function hideWarning() {
                    warningDiv.style.opacity = 0;
                    setTimeout(() => {
                        warningDiv.style.display = 'none';
                    }, 500); // Match transition duration
                }

                increaseButton.addEventListener('click', function() {
                    let currentQuantity = parseInt(quantityInput.value);

                    if (currentQuantity < maxQuantity) {
                        currentQuantity += 1;
                        quantityInput.value = currentQuantity;
                        updatePrice();

                        // Hide warning if still within limit
                        hideWarning();
                    } else {
                        // User tries to go above max
                        showWarning('მარაგში გვაქვს მხოლოდ ' + maxQuantity + ' ეგზემპლარი.');
                    }
                });


                decreaseButton.addEventListener('click', function() {
                    let currentQuantity = parseInt(quantityInput.value);
                    if (currentQuantity > 1) {
                        currentQuantity -= 1;
                        quantityInput.value = currentQuantity;
                        updatePrice();
                    }
                    // Always hide warning on minus click
                    hideWarning();
                });

                // Initial price update
                updatePrice();
            });
        </script>




        <!-- jQuery and CSRF Setup Script -->
        <script>
            $('.toggle-cart-btn').click(function() {
                var button = $(this);
                var bookId = button.data('product-id');
                var inCart = button.data('in-cart');
                var quantity = $('#quantity-' + bookId).val() || 1;

                $.ajax({
                    url: '{{ route('cart.toggle') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        book_id: bookId,
                        quantity: quantity // ✅ pass quantity
                    },
                    success: function(response) {
                        if (response.success) {
                            if (response.action === 'added') {
                                button.removeClass('btn-primary').addClass('btn-success');
                                button.find('i').removeClass('bi-cart-plus').addClass('bi-check-circle');
                                button.find('.cart-btn-text').text(translations.added);
                                button.data('in-cart', true);
                            } else if (response.action === 'removed') {
                                button.removeClass('btn-success').addClass('btn-primary');
                                button.find('i').removeClass('bi-check-circle').addClass('bi-cart-plus');
                                button.find('.cart-btn-text').text(translations.addToCart);
                                button.data('in-cart', false);
                            }

                            const cartCount = response.cart_count;
                            const countEl = document.getElementById('cart-count');
                            const bubble = document.getElementById('cart-bubble');

                            if (countEl && bubble) {
                                countEl.textContent = cartCount;
                                bubble.style.display = cartCount > 0 ? 'inline-block' : 'none';
                            }

                            if (cartCount > 0) {
                                document.cookie = "abandoned_cart=true; max-age=86400; path=/";
                            } else {
                                document.cookie =
                                    "abandoned_cart=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('{{ __('messages.loginrequired') }}');
                    }
                });
            });
        </script>
    @endpush
@endsection
