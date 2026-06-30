@extends('layouts.app')
@php use Illuminate\Support\Str; @endphp
@section('title', 'ბუკინისტები | კალათა')

@section('content')
<style>
  .cart-page      { position:relative; top:30px; padding-bottom:5%; }
  .card-soft      { border-radius:18px; }
  .sticky-summary { position: sticky; top: 90px; }
  .thumb          { width:72px; height:96px; object-fit:cover; border-radius:8px; }
  .table > :not(caption) > * > * { vertical-align: middle; }
  .qty-btn        { width:32px; }
  .small-muted    { font-size:12px; color:#6c757d; }
  .summary-row    { display:flex; justify-content:space-between; align-items:center; }
  .summary-row + .summary-row { margin-top:.25rem; }
  .summary-total  { font-size:1.15rem; font-weight:700; }
  @media print {
    .btn, .alert, .sticky-summary, .payment-card { display:none !important; }
    .card, .table { box-shadow:none !important; }
  }
  .chosen-container-single .chosen-single span {
 
    margin-top: 8px !important;
  
}
</style>

<div class="container cart-page">

  <h5 class="section-title d-flex align-items-center gap-2 mb-4">
    <i class="bi bi-cart-check-fill"></i>
    <strong>{{ __('messages.yourCart') }}</strong>
  </h5>

  {{-- Flash --}}
  @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  {{-- Empty cart --}}
  @if (!$cart || $cart->cartItems->isEmpty())
    <div class="text-center p-5 card card-soft shadow-sm">
      <div class="mb-2"><i class="bi bi-cart-x-fill" style="font-size:64px; color:#ff6b6b;"></i></div>
      <h3 class="mb-2">{{ __('messages.emptyCart') }}</h3>
      <p class="text-muted">{{ __('messages.canAdd') }}</p>
      <a href="{{ route('books') }}" class="btn btn-primary">{{ __('messages.seeBooks') }}</a>
    </div>
  @else

  {{-- Clear cart --}}
  <form action="{{ route('cart.clear') }}" method="POST" class="mb-3"
        onsubmit="return confirm('{{ __('messages.confirmClearCart') }}')">
    @csrf
    <button type="submit" class="btn btn-outline-danger btn-sm">
      <i class="bi bi-trash-fill"></i> {{ __('messages.cleaCart') }}
    </button>
  </form>

  <div class="row g-4">
    {{-- Left: items --}}
    <div class="col-lg-8">
      <div class="card card-soft shadow-sm">
        <div class="card-header bg-light" style="border-top-left-radius:18px; border-top-right-radius:18px;">
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-bag"></i>
            <span class="fw-semibold">{{ __('messages.product') }}</span>
          </div>
        </div>

        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>{{ __('messages.product') }}</th>
                  <th class="text-center">{{ __('messages.quantity') }}</th>
                  <th class="text-center">{{ __('messages.price') }}</th>
                  <th class="text-center d-none d-md-table-cell">{{ __('messages.action') }}</th>
                </tr>
              </thead>
              <tbody>
              @foreach ($cart->cartItems as $item)
                @php
                  $isBundle = (bool)$item->bundle_id;
                  $rowId    = $isBundle ? $item->bundle_id : ($item->book?->id);
                  $maxQty   = $isBundle ? ($item->bundle?->availableQuantity() ?? 0) : ($item->book?->quantity ?? 0);
                @endphp
                <tr>
                  {{-- Product --}}
                  <td>
                    @if ($isBundle && $item->bundle)
                      <div class="d-flex gap-3">
                        @if ($item->bundle->image_url)
                          <img src="{{ $item->bundle->image_url }}" class="thumb shadow" alt="სტატიის სურათი">
                        @endif
                        <div>
                          <a href="{{ route('bundles.show', $item->bundle->slug) }}" class="text-decoration-none">
                            <span class="badge bg-info me-1">Bundle</span>
                            <span class="fw-semibold">{{ $item->bundle->title }}</span>
                          </a>
                          <ul class="small mb-0 mt-2 text-muted">
                            @foreach ($item->bundle->books as $b)
                              <li>{{ $b->title }} × {{ $b->pivot->qty }}</li>
                            @endforeach
                          </ul>

                          {{-- mobile delete --}}
                          <div class="d-md-none mt-2">
                            <form action="{{ route('cart.removeBundle', ['bundle' => $item->bundle_id]) }}" method="POST"
                                  onsubmit="return confirm('ნამდვილად გსურს წაშლა?');">
                              @csrf
                              <button type="submit" class="btn btn-outline-danger btn-sm">{{ __('messages.RemoveFomCart') }}</button>
                            </form>
                          </div>
                        </div>
                      </div>

                    @else
                      @if ($item->book)
                        <div class="d-flex gap-3">
                          @if ($item->book->photo)
                            <img src="{{ asset('storage/'.$item->book->photo) }}"
                                 onerror="this.onerror=null;this.src='{{ asset('images/default_image.png') }}';"
                                 class="thumb shadow" alt="{{ $item->book->title }}">
                          @endif
                          <div>
                            <a href="{{ route('full', ['title' => Str::slug($item->book->title), 'id' => $item->book->id]) }}"
                               class="fw-semibold text-decoration-none">{{ $item->book->title }}</a>
                            <div class="small-muted">{{ $item->book->author->name ?? '' }}</div>

                            {{-- mobile delete --}}
                            <div class="d-md-none mt-2">
                              <form action="{{ route('cart.remove', ['book' => $item->book_id]) }}" method="POST"
                                    onsubmit="return confirm('ნამდვილად გსურს წაშლა?');">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm">{{ __('messages.RemoveFomCart') }}</button>
                              </form>
                            </div>
                          </div>
                        </div>
                      @else
                        <span>—</span>
                      @endif
                    @endif
                  </td>

                  {{-- Quantity --}}
                  <td class="text-center">
                    <div class="small-muted mb-2">
                      @if ($maxQty <= 0)
                        <span class="text-danger"><i class="bi bi-x-circle"></i> {{ __('messages.outofstock') }}</span>
                      @elseif ($maxQty === 1)
                        {{ __('messages.available') }} 1 {{ __('messages.item') }}
                      @else
                        {{ __('messages.available') }} {{ $maxQty }} {{ __('messages.item') }}
                      @endif
                    </div>

                    @if ($rowId)
                    <input type="hidden" class="max-quantity" value="{{ $maxQty }}">
                    <div class="input-group input-group-sm justify-content-center" style="max-width:130px; margin:auto;">
                      <button class="btn btn-outline-secondary qty-btn decrease-quantity"
                              data-type="{{ $isBundle ? 'bundle':'book' }}"
                              data-id="{{ $rowId }}" type="button">−</button>

                      <input type="text" class="form-control text-center quantity-input" value="{{ $item->quantity }}" readonly>

                      <button class="btn btn-outline-secondary qty-btn increase-quantity"
                              data-type="{{ $isBundle ? 'bundle':'book' }}"
                              data-id="{{ $rowId }}" type="button">+</button>
                    </div>
                    <div class="text-danger mt-2 quantity-warning" style="display:none; opacity:0; transition:opacity .5s;">
                      <i class="bi bi-exclamation-triangle-fill"></i>
                      <span class="warning-text"></span>
                    </div>
                    @endif
                  </td>

                  {{-- Price --}}
                  <td class="text-center fw-semibold">
                    {{ number_format($item->price * $item->quantity) }} {{ __('messages.lari') }}
                  </td>

                  {{-- Desktop delete --}}
                  <td class="text-center d-none d-md-table-cell">
                    @if ($item->bundle_id)
                      <form action="{{ route('cart.removeBundle', ['bundle' => $item->bundle_id]) }}" method="POST"
                            onsubmit="return confirm('ნამდვილად გსურს წაშლა?');">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">{{ __('messages.RemoveFomCart') }}</button>
                      </form>
                    @elseif($item->book_id)
                      <form action="{{ route('cart.remove', ['book' => $item->book_id]) }}" method="POST"
                            onsubmit="return confirm('ნამდვილად გსურს წაშლა?');">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">{{ __('messages.RemoveFomCart') }}</button>
                      </form>
                    @endif
                  </td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    {{-- Right: summary + checkout --}}
    <div class="col-lg-4">
      {{-- Order summary --}}
      <div class="card card-soft shadow-sm sticky-summary mb-4">
        <div class="card-body">
          <h5 class="mb-3"><i class="bi bi-receipt me-1"></i> {{ __('messages.total') }}</h5>

          {{-- Subtotal / Shipping / Total (IDs kept for your JS compatibility) --}}
          <div class="summary-row small-muted">{{ __('messages.productPrice') }}
            <span><span id="product-price">{{ max(0, $total - 5) }}</span> {{ __('messages.lari') }}</span>
          </div>

          <div id="delivery-price-container" class="summary-row small-muted" style="display:none;">
            {{ __('messages.deliveryPrice') }}
            <span><span id="delivery-price">5</span> {{ __('messages.lari') }}</span>
          </div>

          <hr class="my-2">

          <div class="summary-row summary-total">
            <span id="total-price" style="display:none;">{{ __('messages.total') }}: {{ number_format($total) }} {{ __('messages.lari') }}</span>
          </div>

          <div class="mt-3 d-grid gap-2">
            <a href="{{ route('books') }}" class="btn btn-outline-secondary">
              <i class="bi bi-book-half"></i> {{ __('messages.seeBooks') }}
            </a>
          </div>
        </div>
      </div>

      {{-- Payment + shipping form --}}
      <div class="card card-soft shadow-sm payment-card">
        <div class="card-body">
          <form action="{{ route('tbc-checkout') }}" method="POST" id="checkoutForm">
            @csrf

            <h5 class="mb-3"><strong>{{ __('messages.choosePayment') }}</strong></h5>

            <div class="form-check">
              <input class="form-check-input" type="radio" name="payment_method" id="payment_bank"
                     value="bank_transfer" required>
              <label class="form-check-label" for="payment_bank">💳 {{ __('messages.payBankTransfer') }}</label>
            </div>
            <div class="form-check mb-3">
              <input class="form-check-input" type="radio" name="payment_method" id="payment_courier"
                     value="courier" required>
              <label class="form-check-label" for="payment_courier">🚚 {{ __('messages.payDelivery') }}</label>
            </div>

            {{-- Name --}}
            <div class="mb-3">
              <label for="name" class="form-label"><strong>{{ __('messages.nameSurname') }}</strong></label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" class="form-control" id="name" name="name"
                       placeholder="{{ __('messages.nameSurname') }}" required>
              </div>
            </div>

            {{-- Phone --}}
            <div class="mb-3">
              <label for="phone" class="form-label"><strong>{{ __('messages.phoneNumber') }}</strong></label>
              <div class="input-group">
                <span class="input-group-text">+995</span>
                <input type="text" class="form-control" id="phone" name="phone"
                       placeholder="5XX XXX XXX" maxlength="9" required pattern="5\d{8}"
                       title="Phone number must start with 5 and be 9 digits long">
              </div>
              @error('phone') <div class="text-danger mt-1">{{ $message }}</div> @enderror
            </div>

            {{-- City (Chosen) --}}
            <div class="mb-3">
              <label for="city" class="form-label"><strong>{{ __('messages.city') }}</strong></label>
              <select name="city" class="form-control chosen-select w-100" id="city"
                      data-placeholder="{{ __('messages.browseCity') }}" required>
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
                <option value="დედოფლისწყაროს მუნიციპალიტეტი">{{ __('messages.dedoflistskaro') }}</option>
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
                <option value="მარტვლის მუნიციპალიტეტი">{{ __('messages.martvili') }}</option>
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

            {{-- Address --}}
            <div class="mb-3">
              <label for="address" class="form-label"><strong>{{ __('messages.address') }}</strong></label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-house-door"></i></span>
                <input type="text" class="form-control" id="address" name="address"
                       placeholder="{{ __('messages.preciseAddress') }}" required>
              </div>
            </div>

            @include('partials.delivery-map-picker')

            <div class="d-grid">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> {{ __('messages.orderProduct') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div> {{-- /row --}}
  @endif
</div>

{{-- Chosen.js (assumes jQuery already included globally) --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>

<script>
  $(function () {
    // Chosen init
    $('.chosen-select').chosen({
      no_results_text: "{{ __('messages.nocityfound') ?? 'No results matched' }}",
      width: '100%'
    });

    // Phone numeric
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
      phoneInput.addEventListener('input', function(){ this.value = this.value.replace(/\D/g, ''); });
    }

    // Delivery calc (kept compatibility with your original IDs)
    function updateTotal(city) {
      let productPrice = {{ max(0, $total - 5) }}; // backend-provided subtotal (minus base 5)
      let deliveryPrice = 5;
      if (city && city !== 'თბილისი') deliveryPrice = 7;

      if (city) {
        $('#delivery-price-container').show();
        $('#total-price').show();
      } else {
        $('#delivery-price-container').hide();
        $('#total-price').hide();
      }

      $('#product-price').text(productPrice);
      $('#delivery-price').text(deliveryPrice);
      const total = productPrice + deliveryPrice;
      $('#total-price').text('{{ __('messages.total') }}: ' + total.toLocaleString('ka-GE') + ' {{ __('messages.lari') }}');
    }

    $('#city').on('change', function(){ updateTotal($(this).val()); });
    updateTotal($('#city').val());

    // Quantity +/- with AJAX
    $('.increase-quantity, .decrease-quantity').click(function() {
      const btn     = $(this);
      const row     = btn.closest('tr');
      const input   = row.find('.quantity-input');
      const maxQty  = parseInt(row.find('.max-quantity').val(), 10) || 0;
      const warn    = row.find('.quantity-warning');
      const warnTxt = row.find('.warning-text');

      let qty    = parseInt(input.val(), 10) || 1;
      const type = btn.data('type'); // 'book' or 'bundle'
      const id   = btn.data('id');
      const up   = btn.hasClass('increase-quantity');

      if (up && qty >= maxQty) {
        warnTxt.text('მარაგში გვაქვს მხოლოდ ' + maxQty + ' ეგზემპლარი.');
        warn.show().css('opacity', 1);
        return;
      }
      if (!up) { warn.css('opacity', 0); setTimeout(()=>warn.hide(), 500); }

      const payload = { _token: '{{ csrf_token() }}', action: up ? 'increase' : 'decrease' };
      if (type === 'bundle') payload.bundle_id = id; else payload.book_id = id;

      $.post('{{ route('cart.updateQuantity') }}', payload, function(resp){
        if (!resp || !resp.success) { alert(resp?.message || 'Update failed.'); return; }

        // Update row qty and price
        input.val(resp.newQuantity);
        // 3rd column is price col in this table
        row.find('td').eq(2).text(resp.updatedTotal.toLocaleString('ka-GE') + ' {{ __('messages.lari') }}');

        // Update summary (IDs kept)
        $('#product-price').text((resp.cartTotal - 5).toLocaleString('ka-GE'));
        const city = $('#city').val();
        const delivery = city && city !== 'თბილისი' ? 7 : 5;
        $('#delivery-price').text(delivery.toLocaleString('ka-GE'));
        $('#delivery-price-container').show();
        $('#total-price').show().text('{{ __('messages.total') }}: ' + resp.cartTotal.toLocaleString('ka-GE') + ' {{ __('messages.lari') }}');
      });
    });
  });
</script>
@endsection
