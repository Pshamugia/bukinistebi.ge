@extends('layouts.app')
@section('title', 'ბუკინისტები | კალათა') 
@section('content')
<!-- jQuery (load before Chosen.js) -->
<!-- jQuery (make sure it's before Chosen.js) -->
 
<!-- Chosen.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>


<div class="container">

    <h5 class="section-title" style="position: relative;  padding-bottom:20px; align-items: left;
    justify-content: left;">
        <strong>
            <i class="bi bi-cart-check-fill"></i> შენი კალათა
        </strong>
    </h5>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(!$cart || $cart->cartItems->isEmpty())
    <div class="empty-cart text-center" style="padding: 50px;">
        <i class="bi bi-cart-x-fill" style="font-size: 64px; color: #ff6b6b;"></i> <!-- Cart icon from Bootstrap Icons -->
        <h3 style="color: #2c3e50; margin-top: 20px;">შენი კალათა ცარიელია.</h3>
        <p style="color: #7f8c8d;">შეგიძლია დაამატო წიგნები კალათაში</p>
        <a href="{{ route('books') }}" class="btn btn-primary mt-3">
            წიგნების ნახვა
        </a>
    </div>
    @else

    
     <!-- Make the table scrollable on mobile -->
     <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th style="text-align: left; vertical-align: middle;">პროდუქცია</th>
                    <th style="text-align: center; vertical-align: middle;">რაოდენობა</th>
                    <th style="text-align: center; vertical-align: middle;">ფასი</th>
                    <th class="d-none d-md-table-cell" style="text-align: center; vertical-align: middle;">ქმედება</th> <!-- Hidden on mobile -->
                </tr>
            </thead>
            <tbody>
                @foreach($cart->cartItems as $item)
                    <tr>
                        <td style="text-align: left; vertical-align: middle;">
                            <a href="{{ route('full', ['title' => Str::slug($item->book->title), 'id' => $item->book->id]) }}" class="card-link" style="text-decoration: none; max-width: 150px; ">
                                <img src="{{ asset('storage/' . $item->book->photo) }}" alt="{{ $item->book->title }}" width="80px" height="100px" align="left" class="img-fluid shadow" style="margin-right: 10px">
                                {{ $item->book->title }}
                            </a>
                            <br>{{ $item->book->author->name }}

                              <!-- Add a new row for the delete button on mobile -->
         <div class="d-md-none" style="top: 15px; position:relative">
            <form action="{{ route('cart.remove', ['book' => $item->book_id]) }}" method="POST" onsubmit="return confirm('ნამდვილად გსურს წაშლა?');">
               @csrf
               <button type="submit" class="btn btn-danger btn-sm btn-block deletion">წაშლა</button>
           </form>
       </div>
                        </td>
                        <td style="text-align: center; vertical-align: middle;">

                            <div style="display: block; margin-bottom: 15px; text-align: center;">
                                <span style="font-size: 13px; display: inline-block;">
                                    @if($item->book->quantity == 0)
                                    <span style="color:red;"> 
                                       <b> <i class="bi bi-x-circle text-danger"></i> </b>
                                        ამ წიგნის მარაგი ამოწურულია</span>
                                    @elseif($item->book->quantity == 1)
                                    <span>მარაგშია 1 ცალი</span>
                                    @else
                                    <span>მარაგშია {{ $item->book->quantity }} ცალი</span>
                                    @endif 
                                </span>
                            </div>
                            
                            <div class="input-group" style="width: 120px; margin: auto;">
                                <button class="btn btn-outline-secondary decrease-quantity btn-sm" type="button" data-book-id="{{ $item->book->id }}" style="width: 30px;">-</button>
                                <input type="text" class="form-control form-control-sm text-center quantity-input" value="{{ $item->quantity }}" readonly style="width: 40px;">
                                <button class="btn btn-outline-secondary increase-quantity btn-sm" type="button" data-book-id="{{ $item->book->id }}" style="width: 30px;">+</button>
                            </div>
                            
       
                            
                        </td>
                        <td style="text-align: center; vertical-align: middle;">{{ number_format($item->price * $item->quantity) }} ლარი</td>
                        <!-- Remove button on larger screens, hidden on mobile -->
                        <td class="d-none d-md-table-cell" style="text-align: center; vertical-align: middle;">
                            <form action="{{ route('cart.remove', ['book' => $item->book_id]) }}" method="POST" onsubmit="return confirm('ნამდვილად გსურს წაშლა?');">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm">წაშლა კალათიდან</button>
                            </form>
                        </td>
                    </tr>
                   
                @endforeach
                <tr style="background-color: #000000; color:white">
                    <td colspan="2" style="text-align: right; vertical-align: middle;"> 
                        <div style="color:wheat"> 
                            <span> პროდუქციის ფასი: <span id="product-price">{{ $total - 5 }}</span> ლარი </span> 
                        </div>
                        <!-- Initially hide the delivery price section -->
                        <div style="color:wheat; display:none;" id="delivery-price-container">
                            <span> მიწოდების ფასი: <span id="delivery-price">5</span> ლარი </span> 
                        </div> 
                    </td>
                    <td rowspan="{{ $cart->cartItems->count() }}" style="text-align: center; vertical-align: middle;">
                        <!-- Initially hide the total price -->
                        <h3 id="total-price" style="text-align: center; vertical-align: middle; top:2px; position: relative; font-size: 16px; color:wheat; display:none;">
                            <span> ჯამური: {{ number_format($total) }} ლარი</span>
                        </h3>
                    </td>
                </tr>
                
                
            </tbody>
        </table>
    </div>
    

      


        <div style="padding: 7px 33px 10px 33px; background-color: rgb(154, 181, 238); border:1px solid #837979"> 
        <!-- Payment Method and Personal Info Form -->
        <form action="{{ route('tbc-checkout') }}" method="POST" id="checkoutForm">
            @csrf
            <h4 class="mt-4"><strong> მონიშნე გადახდის ფორმა </strong></h4>
        
            <!-- Radio buttons for payment -->
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" id="payment_courier" value="courier" required>
                <label class="form-check-label" for="payment_courier">
                     <i class="bi bi-truck"></i> გადახდა კურიერთან</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" id="payment_bank" value="bank_transfer" required>
                <label class="form-check-label" for="payment_bank"> <i class="bi bi-credit-card"></i> საბანკო გადარიცხვა</label>
            </div>
        
            <!-- User details -->
            <div class="mt-4">
                <div class="mb-3">
                    <label for="name" class="form-label"><h4 style="position:relative; top:12px"><strong>სახელი და გვარი</strong></h4></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" placeholder="სახელი გვარი" id="name" name="name" required>
                    </div>
                </div>
              
                
                <div class="mb-3">
                    <label for="phone" class="form-label"><h4 style="position:relative; top:12px"><strong>ტელეფონის ნომერი</strong></h4></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                        <input type="text" class="form-control" id="phone" placeholder="შენი ტელეფონი" name="phone" required>
                    </div>
                </div>


                <div class="mb-3">
                    <label for="city" class="form-label"><h4 style="position:relative; top:12px"><strong>ქალაქი</strong></h4></label>
                    <div class="input-group">
                     <select name="city" class="form-control chosen-select" id="city" data-placeholder="მონიშნე ქალაქი" required style="height: 50px">
                        <option value="">მონიშნე ქალაქი</option> 
                        <option value="თბილისი">თბილისი</option>
                        <option value="ბათუმი">ბათუმი</option>
                        <option value="ქუთაისი">ქუთაისი</option>
                        <option value="გურჯაანის მუნიციპალიტეტი">გურჯაანის მუნიციპალიტეტი</option>
                        <option value="თელავის მუნიციპალიტეტი">თელავის მუნიციპალიტეტი</option>
                        <option value="ზუგდიდის მუნიციპალიტეტი">ზუგდიდის მუნიციპალიტეტი</option>
                        <option value="ბაკურიანი">ბაკურიანი</option>
                        <option value="გორის მუნიციპალიტეტი">გორის მუნიციპალიტეტი</option>
                        <option value="რუსთავი">რუსთავი</option>
                        <option value="ფოთი">ფოთი</option>
                        <option value="აბაშის მუნიციპალიტეტი">აბაშის მუნიციპალიტეტი</option>
                        <option value="ადიგენის მუნიციპალიტეტი">ადიგენის მუნიციპალიტეტი</option>
                        <option value="ამბროლაურის მუნიციპალიტეტი">ამბროლაურის მუნიციპალიტეტი</option>
                        <option value="ასპინძის მუნიციპალიტეტი">ასპინძის მუნიციპალიტეტი</option>
                        <option value="ახალგორის მუნიციპალიტეტი">ახალგორის მუნიციპალიტეტი</option>
                        <option value="ახალქალაქის მუნიციპალიტეტი">ახალქალაქის მუნიციპალიტეტი</option>
                        <option value="ახალციხის მუნიციპალიტეტი">ახალციხის მუნიციპალიტეტი</option>
                        <option value="ახმეტის მუნიციპალიტეტი">ახმეტის მუნიციპალიტეტი</option>
                        <option value="ბაღდათის მუნიციპალიტეტი">ბაღდათის მუნიციპალიტეტი</option>
                        <option value="ბოლნისის მუნიციპალიტეტი">ბოლნისის მუნიციპალიტეტი</option>
                        <option value="ბორჯომის მუნიციპალიტეტი">ბორჯომის მუნიციპალიტეტი</option>
                        <option value="გარდაბნის მუნიციპალიტეტი">გარდაბნის მუნიციპალიტეტი</option>
                        <option value="დედოფლისწყაროს მუნიციპალიტეტი">დედოფლისწყაროს მუნიციპალიტეტი</option>
                        <option value="დმანისის მუნიციპალიტეტი">დმანისის მუნიციპალიტეტი</option>
                        <option value="დუშეთის მუნიციპალიტეტი">დუშეთის მუნიციპალიტეტი</option>
                        <option value="ვანის მუნიციპალიტეტი">ვანის მუნიციპალიტეტი</option>
                        <option value="ზესტაფონის მუნიციპალიტეტი">ზესტაფონის მუნიციპალიტეტი</option>
                        <option value="თეთრი წყაროს მუნიციპალიტეტი">თეთრი წყაროს მუნიციპალიტეტი</option>
                        <option value="თერჯოლის მუნიციპალიტეტი">თერჯოლის მუნიციპალიტეტი</option>
                        <option value="თიანეთის მუნიციპალიტეტი">თიანეთის მუნიციპალიტეტი</option>
                        <option value="კასპის მუნიციპალიტეტი">კასპის მუნიციპალიტეტი</option>
                        <option value="ლაგოდეხის მუნიციპალიტეტი">ლაგოდეხის მუნიციპალიტეტი</option>
                        <option value="ლანჩხუთის მუნიციპალიტეტი">ლანჩხუთის მუნიციპალიტეტი</option>
                        <option value="ლენტეხის მუნიციპალიტეტი">ლენტეხის მუნიციპალიტეტი</option>
                        <option value="მარნეულის მუნიციპალიტეტი">მარნეულის მუნიციპალიტეტი</option>
                        <option value="მარტვილის მუნიციპალიტეტი">მარტვილის მუნიციპალიტეტი</option>
                        <option value="მესტიის მუნიციპალიტეტი">მესტიის მუნიციპალიტეტი</option>
                        <option value="მცხეთის მუნიციპალიტეტი">მცხეთის მუნიციპალიტეტი</option>
                        <option value="ნინოწმინდის მუნიციპალიტეტი">ნინოწმინდის მუნიციპალიტეტი</option>
                        <option value="ოზურგეთის მუნიციპალიტეტი">ოზურგეთის მუნიციპალიტეტი</option>
                        <option value="ონის მუნიციპალიტეტი">ონის მუნიციპალიტეტი</option>
                        <option value="საგარეჯოს მუნიციპალიტეტი">საგარეჯოს მუნიციპალიტეტი</option>
                        <option value="სამტრედიის მუნიციპალიტეტი">სამტრედიის მუნიციპალიტეტი</option>
                        <option value="საჩხერის მუნიციპალიტეტი">საჩხერის მუნიციპალიტეტი</option>
                        <option value="სენაკის მუნიციპალიტეტი">სენაკის მუნიციპალიტეტი</option>
                        <option value="სიღნაღის მუნიციპალიტეტი">სიღნაღის მუნიციპალიტეტი</option>
                        <option value="ტყიბულის მუნიციპალიტეტი">ტყიბულის მუნიციპალიტეტი</option>
                        <option value="ქარელის მუნიციპალიტეტი">ქარელის მუნიციპალიტეტი</option>
                        <option value="ქედის მუნიციპალიტეტი">ქედის მუნიციპალიტეტი</option>
                        <option value="ქობულეთის მუნიციპალიტეტი">ქობულეთის მუნიციპალიტეტი</option>
                        <option value="ყაზბეგის მუნიციპალიტეტი">ყაზბეგის მუნიციპალიტეტი</option>
                        <option value="ყვარლის მუნიციპალიტეტი">ყვარლის მუნიციპალიტეტი</option>
                        <option value="შუახევის მუნიციპალიტეტი">შუახევის მუნიციპალიტეტი</option>
                        <option value="ჩოხატაურის მუნიციპალიტეტი">ჩოხატაურის მუნიციპალიტეტი</option>
                        <option value="ჩხოროწყუს მუნიციპალიტეტი">ჩხოროწყუს მუნიციპალიტეტი</option>
                        <option value="ცაგერის მუნიციპალიტეტი">ცაგერის მუნიციპალიტეტი</option>
                        <option value="წალენჯიხის მუნიციპალიტეტი">წალენჯიხის მუნიციპალიტეტი</option>
                        <option value="წალკის მუნიციპალიტეტი">წალკის მუნიციპალიტეტი</option>
                        <option value="წყალტუბოს მუნიციპალიტეტი">წყალტუბოს მუნიციპალიტეტი</option>
                        <option value="ჭიათურის მუნიციპალიტეტი">ჭიათურის მუნიციპალიტეტი</option>
                        <option value="ხარაგაულის მუნიციპალიტეტი">ხარაგაულის მუნიციპალიტეტი</option>
                        <option value="ხაშურის მუნიციპალიტეტი">ხაშურის მუნიციპალიტეტი</option>
                        <option value="ხელვაჩაურის მუნიციპალიტეტი">ხელვაჩაურის მუნიციპალიტეტი</option>
                        <option value="ხობის მუნიციპალიტეტი">ხობის მუნიციპალიტეტი</option>
                        <option value="ხონის მუნიციპალიტეტი">ხონის მუნიციპალიტეტი</option>
                        <option value="ხულოს მუნიციპალიტეტი">ხულოს მუნიციპალიტეტი</option>
                        <option value="ჯავის მუნიციპალიტეტი">ჯავის მუნიციპალიტეტი</option> 




                    </select>  
                 </div>
            </div>

                 <!-- Address -->
            <div class="mb-3">
                <label for="address" class="form-label"><h4><strong style="position:relative; top:12px">მისამართი</strong></h4></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-house-door"></i></span>
                    <input type="text" class="form-control" id="address" name="address" placeholder="ზუსტი მისამართი" required>
                </div>
            </div>
        </div>

                
        
           <!-- Submit button -->
        <div class="mt-4 text-center">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> შეკვეთა</button>
        </div>
    </form>
</div>
    @endif
</div>

<script>
$(document).ready(function () {
    $(".chosen-select").chosen({
        no_results_text: "არაფერი მოიძებნა",
        placeholder_text_single: "მონიშნე ქალაქი",
        placeholder_text_multiple: "მონიშნე ქალაქები"
    });

    // Function to update total based on city selection
    function updateTotal(city) {
        let productPrice = {{ $total - 5 }}; // Assuming $total is passed from the backend, adjusted to remove delivery price
        let deliveryPrice = 5;

        if (city !== 'თბილისი') {
            deliveryPrice = 7; // For other cities, delivery price is 7
        }

        // Show the delivery price container and total price once a city is selected
        if (city) {
            $('#delivery-price-container').show();
            $('#total-price').show();  // Show the total price
        } else {
            $('#delivery-price-container').hide(); // Hide delivery price container if no city is selected
            $('#total-price').hide();  // Hide total price if no city is selected
        }

        // Update the product price, delivery price, and total dynamically
        $('#product-price').text(productPrice); // Update product price
        $('#delivery-price').text(deliveryPrice); // Update delivery price
        const total = productPrice + deliveryPrice; // Recalculate total
        $('#total-price').text('ჯამური: ' + total + ' ლარი'); // Update total price displayed
    }

    // Event listener for city selection change
    $('#city').on('change', function () {
        const selectedCity = $(this).val();  // Get the selected city
        updateTotal(selectedCity);  // Update the total based on city selection
    });

    // Initialize with the current city selection
    updateTotal($('#city').val());
});


</script>
 
<script>
 

    document.addEventListener('DOMContentLoaded', function () {
        // Increase quantity
        document.querySelectorAll('.increase-quantity').forEach(function(button) {
            button.addEventListener('click', function () {
                var bookId = this.getAttribute('data-book-id');
                updateQuantity(bookId, 'increase');
            });
        });

        // Decrease quantity
        document.querySelectorAll('.decrease-quantity').forEach(function(button) {
            button.addEventListener('click', function () {
                var bookId = this.getAttribute('data-book-id');
                updateQuantity(bookId, 'decrease');
            });
        });

        function updateQuantity(bookId, action) {
            $.ajax({
                url: '{{ route("cart.updateQuantity") }}',  // You need to define this route in your web.php
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    book_id: bookId,
                    action: action
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message || 'Unable to update quantity.');
                    }
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        }
    });
</script>

@endsection
