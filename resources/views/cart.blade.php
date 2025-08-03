@extends('layouts.app')
@section('title', 'ბუკინისტები | კალათა')
@section('content')
    <!-- jQuery (load before Chosen.js) -->
    <!-- jQuery (make sure it's before Chosen.js) -->

    <!-- Chosen.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>


    <div class="container">

        {{-- {{ dd($cart) }} --}}
        <h5 class="section-title"
            style="position: relative; top:30px; margin-bottom:40px; align-items: left;
    justify-content: left;">
            <strong>
                <i class="bi bi-cart-check-fill"></i> {{ __('messages.yourCart') }}
            </strong>
        </h5>

        @if ($cart && $cart->cartItems->isNotEmpty())
            <form action="{{ route('cart.clear') }}" method="POST"
                onsubmit="return confirm('{{ __('messages.confirmClearCart') }}')" class="mb-3">
                @csrf

                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash-fill"></i> {{ __('messages.cleaCart') }}
                </button>
            </form>
        @endif


        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- empty cart --}}
        @if (!$cart || $cart->cartItems->isEmpty())
            <div class="empty-cart text-center" style="padding: 50px;">
                <i class="bi bi-cart-x-fill" style="font-size: 64px; color: #ff6b6b;"></i>
                <!-- Cart icon from Bootstrap Icons -->
                <h3 style="color: #2c3e50; margin-top: 20px;"> {{ __('messages.emptyCart') }}</h3>
                <p style="color: #7f8c8d;">{{ __('messages.canAdd') }}</p>
                <a href="{{ route('books') }}" class="btn btn-primary mt-3">
                    {{ __('messages.seeBooks') }}
                </a>
            </div>
        @else
            <!-- Make the table scrollable on mobile -->
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th style="text-align: left; vertical-align: middle;">{{ __('messages.product') }}</th>
                            <th style="text-align: center; vertical-align: middle;">{{ __('messages.quantity') }}</th>
                            <th style="text-align: center; vertical-align: middle;">{{ __('messages.price') }}</th>
                            <th class="d-none d-md-table-cell" style="text-align: center; vertical-align: middle;">
                                {{ __('messages.action') }}</th> <!-- Hidden on mobile -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cart->cartItems as $item)
                            <tr>
                                <td style="text-align: left; vertical-align: middle;">
                                    <a href="{{ route('full', ['title' => Str::slug($item->book->title), 'id' => $item->book->id]) }}"
                                        class="card-link" style="text-decoration: none; max-width: 150px; ">
                                        <img src="{{ asset('storage/' . $item->book->photo) }}"
                                            alt="{{ $item->book->title }}" width="80px" height="100px" align="left"
                                            class="img-fluid shadow" style="margin-right: 10px">
                                        {{ $item->book->title }}
                                    </a>
                                    <br>{{ $item->book->author->name }}

                                    <!-- Add a new row for the delete button on mobile -->
                                    <div class="d-md-none" style="top: 15px; position:relative">
                                        <form action="{{ route('cart.remove', ['book' => $item->book_id]) }}"
                                            method="POST" onsubmit="return confirm('ნამდვილად გსურს წაშლა?');">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-danger btn-sm btn-block deletion">წაშლა</button>
                                        </form>
                                    </div>
                                </td>
                                <td style="text-align: center; vertical-align: middle;">

                                    <div style="display: block; margin-bottom: 15px; text-align: center;">
                                        <span style="font-size: 13px; display: inline-block;">
                                            @if ($item->book->quantity == 0)
                                                <span style="color:red;">
                                                    <b> <i class="bi bi-x-circle text-danger"></i> </b>
                                                    {{ __('messages.outofstock') }}</span>
                                            @elseif($item->book->quantity == 1)
                                                <span>{{ __('messages.available') }} 1 {{ __('messages.item') }}</span>
                                            @else
                                                <span>{{ __('messages.available') }} {{ $item->book->quantity }}
                                                    {{ __('messages.item') }}</span>
                                            @endif
                                        </span>
                                        <input type="hidden" class="max-quantity" value="{{ $item->book->quantity }}">
<div class="text-danger mt-2 quantity-warning" style="display: none; opacity: 0; transition: opacity 0.5s;">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <span class="warning-text"></span>
</div>

                                    </div>

                                    <div class="input-group" style="width: 120px; margin: auto;">
                                        <button class="btn btn-outline-secondary decrease-quantity btn-sm" type="button"
                                            data-book-id="{{ $item->book->id }}" style="width: 30px;">-</button>
                                        <input type="text"
                                            class="form-control form-control-sm text-center quantity-input"
                                            value="{{ $item->quantity }}" readonly style="width: 40px;">
                                        <button class="btn btn-outline-secondary increase-quantity btn-sm" type="button"
                                            data-book-id="{{ $item->book->id }}" style="width: 30px;">+</button>
                                    </div>



                                </td>
                                <td style="text-align: center; vertical-align: middle;">
                                    {{ number_format($item->price * $item->quantity) }} {{ __('messages.lari') }}</td>
                                <!-- Remove button on larger screens, hidden on mobile -->
                                <td class="d-none d-md-table-cell" style="text-align: center; vertical-align: middle;">
                                    <form action="{{ route('cart.remove', ['book' => $item->book_id]) }}" method="POST"
                                        onsubmit="return confirm('ნამდვილად გსურს წაშლა?');">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-outline-danger btn-sm">{{ __('messages.RemoveFomCart') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        <tr style="background-color: #000000; color:white">
                            <td colspan="2" style="text-align: right; vertical-align: middle;">
                                <div style="color:wheat">
                                    <span> {{ __('messages.productPrice') }}: <span
                                            id="product-price">{{ $total - 5 }}</span> {{ __('messages.lari') }}
                                    </span>
                                </div>
                                <!-- Initially hide the delivery price section -->
                                <div style="color:wheat; display:none;" id="delivery-price-container">
                                    <span> {{ __('messages.deliveryPrice') }} : <span id="delivery-price">5</span>
                                        {{ __('messages.lari') }} </span>
                                </div>
                            </td>
                            <td rowspan="{{ $cart->cartItems->count() }}"
                                style="text-align: center; vertical-align: middle;">
                                <!-- Initially hide the total price -->
                                <h3 id="total-price"
                                    style="text-align: center; vertical-align: middle; top:2px; position: relative; font-size: 16px; color:wheat; display:none;">
                                    <span> {{ __('messages.total') }}: {{ number_format($total) }}
                                        {{ __('messages.lari') }}</span>
                                </h3>
                            </td>
                        </tr>


                    </tbody>
                </table>
            </div>





            <div class="payment-option" style="padding: 7px 33px 10px 33px; background-color: rgb(154, 181, 238); border:1px solid #837979">
                <!-- Payment Method and Personal Info Form -->
                <form action="{{ route('tbc-checkout') }}" method="POST" id="checkoutForm">
                    @csrf
                    <h4 class="mt-4"><strong> {{ __('messages.choosePayment') }} </strong></h4>

                    <!-- Radio buttons for payment -->
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="radio" 
                               name="payment_method" id="payment_bank"
                               value="bank_transfer" required>
                        <label class="form-check-label" for="payment_bank">
                          💳 {{ __('messages.payBankTransfer') }}
                        </label>
                      </div>
                      
                      <!-- Courier Switch -->
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="radio" 
                               name="payment_method" id="payment_courier"
                               value="courier" required>
                        <label class="form-check-label" for="payment_courier">
                          🚚 {{ __('messages.payDelivery') }}
                        </label>
                      </div>


                    
                 

                    <!-- User details -->
                    <div class="mt-4">
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <h4 style="position:relative; top:12px"><strong>{{ __('messages.nameSurname') }}</strong>
                                </h4>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control"
                                    placeholder="{{ __('messages.nameSurname') }}" id="name" name="name"
                                    required>
                            </div>
                        </div>


                        <div class="mb-3">
                            <label for="phone" class="form-label">
                                <h4 style="position:relative; top:12px"><strong>{{ __('messages.phoneNumber') }}</strong>
                                </h4>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">+995</span>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    placeholder="5XX XXX XXX" maxlength="9" required pattern="5\d{8}"
                                    title="Phone number must start with 5 and be 9 digits long">
                            </div>
                            @error('phone')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        @error('phone')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror


                        <div class="mb-3">
                            <label for="city" class="form-label">
                                <h4 style="position:relative; top:12px"><strong>{{ __('messages.city') }}</strong></h4>
                            </label>
                            <div class="input-group">
                                <select name="city" class="form-control chosen-select w-100" id="city"
                                    data-placeholder="{{ __('  messages.browseCity') }}" required style="height: 50px;">
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
                                    <option value="ამბროლაურის მუნიციპალიტეტი">{{ __('messages.ambrolauri') }}</option>
                                    <option value="ასპინძის მუნიციპალიტეტი">{{ __('messages.aspindza') }}</option>
                                    <option value="ახალგორის მუნიციპალიტეტი">{{ __('messages.akhalgori') }}</option>
                                    <option value="ახალქალაქის მუნიციპალიტეტი">{{ __('messages.akhalkalaki') }}</option>
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
                                    <option value="თეთრი წყაროს მუნიციპალიტეტი">{{ __('messages.tetritskaro') }}</option>
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
                                    <option value="ნინოწმინდის მუნიციპალიტეტი">{{ __('messages.ninotsminda') }}</option>
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
                                    <option value="ჩოხატაურის მუნიციპალიტეტი">{{ __('messages.chokhatauri') }}</option>
                                    <option value="ჩხოროწყუს მუნიციპალიტეტი">{{ __('messages.chkhorotsku') }}</option>
                                    <option value="ცაგერის მუნიციპალიტეტი">{{ __('messages.tsageri') }}</option>
                                    <option value="წალენჯიხის მუნიციპალიტეტი">{{ __('messages.tsalejikha') }}</option>
                                    <option value="წალკის მუნიციპალიტეტი">{{ __('messages.tsalka') }}</option>
                                    <option value="წყალტუბოს მუნიციპალიტეტი">{{ __('messages.tskaltubo') }}</option>
                                    <option value="ჭიათურის მუნიციპალიტეტი">{{ __('messages.chiatura') }}</option>
                                    <option value="ხარაგაულის მუნიციპალიტეტი">{{ __('messages.kharagauli') }}</option>
                                    <option value="ხაშურის მუნიციპალიტეტი">{{ __('messages.khashuri') }}</option>
                                    <option value="ხელვაჩაურის მუნიციპალიტეტი">{{ __('messages.khelvachaui') }}</option>
                                    <option value="ხობის მუნიციპალიტეტი">{{ __('messages.khobi') }}</option>
                                    <option value="ხონის მუნიციპალიტეტი">{{ __('messages.khoni') }}</option>
                                    <option value="ხულოს მუნიციპალიტეტი">{{ __('messages.khulo') }}</option>
                                    <option value="ჯავის მუნიციპალიტეტი">{{ __('messages.java') }}</option>




                                </select>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <label for="address" class="form-label">
                                <h4><strong style="position:relative; top:12px">{{ __('messages.address') }}</strong>
                                </h4>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-house-door"></i></span>
                                <input type="text" class="form-control" id="address" name="address"
                                    placeholder="{{ __('messages.preciseAddress') }}" required>
                            </div>
                        </div>
                    </div>



                    <!-- Submit button -->
                    <div class="mt-4 text-center">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i>
                            {{ __('messages.orderProduct') }}</button>
                    </div>
                </form>
            </div>
        @endif
    </div>

    <script>
        $(document).ready(function() {
            $(".chosen-select").chosen({
                no_results_text: "არაფერი მოიძებნა",
                placeholder_text_single: "მონიშნე ქალაქი",
                placeholder_text_multiple: "მონიშნე ქალაქები"
            });

            // phone validate
            document.getElementById('phone').addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, ''); // remove non-numeric characters
            });

            // Function to update total based on city selection
            function updateTotal(city) {
                let productPrice =
                    {{ $total - 5 }}; // Assuming $total is passed from the backend, adjusted to remove delivery price
                let deliveryPrice = 5;

                if (city !== 'თბილისი') {
                    deliveryPrice = 7; // For other cities, delivery price is 7
                }

                // Show the delivery price container and total price once a city is selected
                if (city) {
                    $('#delivery-price-container').show();
                    $('#total-price').show(); // Show the total price
                } else {
                    $('#delivery-price-container').hide(); // Hide delivery price container if no city is selected
                    $('#total-price').hide(); // Hide total price if no city is selected
                }

                // Update the product price, delivery price, and total dynamically
                $('#product-price').text(productPrice); // Update product price
                $('#delivery-price').text(deliveryPrice); // Update delivery price
                const total = productPrice + deliveryPrice; // Recalculate total
                $('#total-price').text('ჯამური: ' + total + ' ლარი'); // Update total price displayed
            }

            // Event listener for city selection change
            $('#city').on('change', function() {
                const selectedCity = $(this).val(); // Get the selected city
                updateTotal(selectedCity); // Update the total based on city selection
            });

            // Initialize with the current city selection
            updateTotal($('#city').val());
        });
    </script>

    <script>
       $('.increase-quantity, .decrease-quantity').click(function() {
    const button = $(this);
    const row = button.closest('tr');
    const inputField = row.find('.quantity-input');
    const maxQuantity = parseInt(row.find('.max-quantity').val());
    const warningDiv = row.find('.quantity-warning');
    const warningText = row.find('.warning-text');

    let currentQuantity = parseInt(inputField.val());

    const action = button.hasClass('increase-quantity') ? 'increase' : 'decrease';

    // Handle increase locally first
    if (action === 'increase') {
        if (currentQuantity >= maxQuantity) {
            // ❗ User tries to exceed limit
            warningText.text('მარაგში გვაქვს მხოლოდ ' + maxQuantity + ' ეგზემპლარი.');
            warningDiv.show().css('opacity', 1);
            return; // Do not send AJAX
        }
    }

    // On decrease always hide
    if (action === 'decrease') {
        warningDiv.css('opacity', 0);
        setTimeout(() => warningDiv.hide(), 500);
    }

    $.ajax({
        url: '{{ route('cart.updateQuantity') }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            book_id: button.data('book-id'),
            action: action
        },
        success: function(response) {
            if (response.success) {
                inputField.val(response.newQuantity);
                row.find('td:nth-child(3)').text(response.updatedTotal + ' ლარი');
                $('#product-price').text(response.cartTotal - 5);
                $('#total-price').text('ჯამური: ' + response.cartTotal + ' ლარი');

                $('#delivery-price').text(5);
                $('#delivery-price-container').show();
                $('#total-price').show();
            } else {
                alert(response.message || 'Update failed.');
            }
        }
    });
});

    </script>

@endsection
