@extends('layouts.app')
@section('title', $bundle->title)

@section('content')
<div class="container" style="position:relative;top:50px">
  <div class="row g-4">
    <div class="col-md-5">
      @if($bundle->image)
      <img src="{{ asset('storage/' . $bundle->image) }}" alt="{{ $bundle->title }}" class="coverFull img-fluid"
      id="thumbnailImage" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#imageModal"
      loading="lazy">
      @endif
    </div>
    <div class="col-md-7">
      <h3>{{ $bundle->title }}</h3>
      @php
      $hasDiscount = $bundle->original_price && $bundle->original_price > $bundle->price;
      $percent = $hasDiscount
          ? round((1 - ($bundle->price / $bundle->original_price)) * 100)
          : 0;
    @endphp
    
    <p class="text-muted mb-2 d-flex align-items-center gap-2">
      @if($hasDiscount)
        <del class="me-2">{{ number_format($bundle->original_price) }} GEL</del>
      @endif
    
      <span class="h5 mb-0">{{ number_format($bundle->price) }} GEL</span>
    
      @if($hasDiscount)
        <span class="badge bg-danger ms-2">-{{ $percent }}%</span>
        <span class="badge bg-success ms-1">
          {{ __('messages.save') }} {{ number_format($bundle->original_price - $bundle->price) }} {{ __('messages.lari') }}
        </span>
      @endif
    </p>
    
    
      <div class="mb-3">
        @if($available > 0)
          <span class="badge bg-success">{{ __('messages.available') }}: {{ $available }}</span>
        @else
          <span class="badge bg-secondary">{{ __('messages.outofstock') }}</span>
        @endif
      </div>
    
     <div style="padding: 15px 15px 0px 15px; border:1px solid rgb(165, 165, 165); border-radius: 7px;"> @if($bundle->description)
      <span><p>{{ $bundle->description }}</p> </span>
      @endif </div>
    
      <h5 class="mt-4">{{ __('messages.includedBooks') }}</h5>
      @php use Illuminate\Support\Str; @endphp

<ul class="list-unstyled">
  @foreach ($bundle->books as $bk)
    @php
      $author = $bk->author ?? null;
      $authorName = $author
        ? (app()->getLocale() === 'en'
            ? ($author->name_en ?? $author->name)
            : $author->name)
        : null;
    @endphp

    <li class="mb-1"> 
      {{-- Book title --}}
      <i class="bi bi-star"></i>  <a href="{{ route('full', ['title' => Str::slug($bk->title), 'id' => $bk->id]) }}"
         class="text-decoration-none">
        {{ $bk->title }}
      </a>

      {{-- Author (if present) --}}
      @if ($author)
        <span class="text-muted"> — </span>
       
          {{ $authorName }}
         
      @endif

      {{-- Qty --}}
      <span class="text-muted"> × {{ $bk->pivot->qty }}</span>
    </li>
  @endforeach
</ul>

    
      {{-- SAFE inCart check (no where() on null) --}}
      @php
        $cart   = auth()->user()?->cart;
        $inCart = $cart
          ? $cart->cartItems()->where('bundle_id', $bundle->id)->exists()
          : false;
      @endphp
    
      {{-- Quantity selector (for Direct Pay and for user info) --}}
      @if($available > 0)
        <div class="mb-3">
          <div class="input-group" style="width:200px;">
            <button class="btn btn-outline-secondary btn-sm" type="button" id="bundle-dec">-</button>
            <input type="text" id="bundle-qty" class="form-control form-control-sm text-center" value="1" readonly>
            <button class="btn btn-outline-secondary btn-sm" type="button" id="bundle-inc">+</button>
          </div>
          <input type="hidden" id="bundle-max" value="{{ $available }}">
          <div id="bundle-qty-warn" class="text-danger mt-2" style="display:none;opacity:0;transition:opacity .5s;">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span class="msg"></span>
          </div>
        </div>
      @endif
    
      {{-- Add to cart / Added toggle --}}
      @if($available >= 1 && (!auth()->check() || auth()->user()->role !== 'publisher'))
        <button
          class="btn {{ $inCart ? 'btn-success' : 'btn-primary' }} toggle-bundle-btn mt-1"
          data-bundle-id="{{ $bundle->id }}"
          data-in-cart="{{ $inCart ? 'true' : 'false' }}"
          style="min-width: 200px;">
          <i class="bi {{ $inCart ? 'bi-check-circle' : 'bi-cart-plus' }}"></i>
          <span class="cart-btn-text">
            {{ $inCart ? __('messages.added') : __('messages.addtocart') }}
          </span>
        </button>
      @elseif($available < 1)
        <button class="btn btn-secondary mt-1" disabled style="min-width:200px;">
          {{ __('messages.outofstock') }}
        </button>
      @endif
    
      {{-- Direct Pay toggle --}}

      <div style="padding-bottom: 25px; ">
      @if($available >= 1)
        <button class="btn btn-warning mt-2" id="bundle-direct-pay-toggle" style="min-width:200px;">
          <i class="bi bi-credit-card"></i> {{ __('messages.directPayBundle') }}
        </button>
      @endif
      </div>
    
      {{-- Direct Pay form (same fields as book page) --}}
      <div class="w-100 mt-4" id="bundle-direct-pay-form" style="display:none;">
        <form action="{{ route('bundle.direct.pay', $bundle) }}" method="POST" id="bundleDirectCheckoutForm"
              style="padding:0 20px;margin-top:-10px;background:#fdcd47;border-radius:5px;">
          @csrf
          <div class="text-end" style="top:10px;position:relative;">
            <button type="button" class="btn-close" aria-label="Close"
                    onclick="document.getElementById('bundle-direct-pay-form').style.display='none';"></button>
          </div>
    
          {{-- Hidden data --}}
          <input type="hidden" name="bundle_id" value="{{ $bundle->id }}">
          <input type="hidden" name="quantity" id="bundle-direct-pay-quantity" value="1">
    
          <h5><strong>{{ __('messages.choosePayment') }}</strong></h5>
          <div class="form-check form-switch">
            <input class="form-check-input" type="radio" name="payment_method" id="pay_bank" value="bank_transfer" required>
            <label class="form-check-label" for="pay_bank">💳 {{ __('messages.payBankTransfer') }}</label>
          </div>
          <div class="form-check form-switch">
            <input class="form-check-input" type="radio" name="payment_method" id="pay_courier" value="courier" required>
            <label class="form-check-label" for="pay_courier">🚚 {{ __('messages.payDelivery') }}</label>
          </div>
    
          <div class="mb-3 mt-3">
            <label><h4><strong>{{ __('messages.nameSurname') }}</strong></h4></label>
            <input type="text" name="name" class="form-control" required>
          </div>
    
          <div class="mb-3 w-100">
            <label for="bundle_phone" class="form-label">
              <h4 style="position:relative;top:12px"><strong>{{ __('messages.phoneNumber') }}</strong></h4>
            </label>
            <div style="display:flex;width:100%;">
              <span style="background:#f0f0f0;height:40px;padding:10px 12px;border:1px solid #ccc;border-radius:4px;">+995</span>
              <input type="text" id="bundle_phone" name="phone" placeholder="5XX XXX XXX"
                     maxlength="9" required pattern="5\d{8}"
                     title="Phone number must start with 5 and be 9 digits long"
                     style="flex:1;padding:10px 12px;height:40px;font-size:16px;border:1px solid #ccc;border-radius:4px;">
            </div>
          </div>


            {{-- optional email for guests --}}
            @guest
            <div class="mb-3">
              <label for="bundle_phone" class="form-label">
                <h4 style="position:relative;top:12px"><strong>{{ __('messages.email') }}</strong> <span style="font-size: 12px">
                   (არასავალდებულოა, მაგრამ თუ ინვოისის მიღება გსურთ, მიუთითეთ) </span> </h4>
              </label>
 
                <input type="email" name="email" id="email" class="form-control"
                    value="{{ old('email') }}">
            </div>
        @endguest
    
          <div class="mb-3">
            <h4 class="form-label" style="position:relative;"><strong>{{ __('messages.city') }}</strong></h4>
            <select name="city" class="form-control chosen-select w-100" id="bundle_city"
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
    
          <div class="mb-3">
            <label><h4><strong>{{ __('messages.address') }}</strong></h4></label>
            <input type="text" name="address" class="form-control" required>
          </div>
    
          
          {{-- delivery --}}
          <div class="deliveryFull">
            <div class="border rounded p-3 mt-3 mt-md-0"
                style=" box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;">
                <span>
                    <p>🚚 <strong>მიწოდება</strong></p>
                    <p>თბილისი: 5 ლარი / 2 სამუშაო დღე</p>
                    <p>რეგიონი: 7 ლარი / 3-5 სამუშაო დღე</p>
                </span>
            </div>
        </div>

          <div class="text-center mt-3" style="padding: 20px;">
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-check-circle"></i> {{ __('messages.directPayBundle') }}
            </button>
          </div>
        </form>
      </div>
    </div>
    
    

 

    </div>
  </div>
</div>




<!-- Modal for Enlarged Image -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true"
style="z-index: 9999999999 !important">
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="imageModalLabel"> </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
           

            <!-- Modal Image -->
            <img src="{{ asset('storage/' . $bundle->image) }}" id="modalImage"
                class="img-fluid" loading="lazy">

        
        </div>
    </div>
</div>
</div>
</div>

@endsection

@push('scripts')
<link href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>

<script>
  (function () {
    const ADDED = @json(__('messages.added'));
    const ADD_TO_CART = @json(__('messages.addtocart'));

    // Chosen init
    $('.chosen-select').chosen({
      disable_search_threshold: 10,
      no_results_text: @json(__('messages.nocityfound') ?? 'No results matched'),
      width: '100%'
    });

    // Quantity +/- with guard
    const max = parseInt(document.getElementById('bundle-max')?.value || 0, 10);
    const qtyInput = $('#bundle-qty');
    const warn = $('#bundle-qty-warn'); const warnMsg = $('#bundle-qty-warn .msg');

    $('#bundle-inc').on('click', function () {
      let q = parseInt(qtyInput.val(), 10) || 1;
      if (q >= max) {
        warnMsg.text('მარაგში გვაქვს მხოლოდ ' + max + ' ეგზემპლარი.');
        warn.show().css('opacity', 1);
        return;
      }
      warn.css('opacity', 0); setTimeout(() => warn.hide(), 400);
      qtyInput.val(q + 1);
      $('#bundle-direct-pay-quantity').val(q + 1);
    });
    $('#bundle-dec').on('click', function () {
      let q = parseInt(qtyInput.val(), 10) || 1;
      q = Math.max(1, q - 1);
      qtyInput.val(q);
      $('#bundle-direct-pay-quantity').val(q);
      warn.css('opacity', 0); setTimeout(() => warn.hide(), 400);
    });

    // Numeric-only phone
    $('#bundle_phone').on('input', function () { this.value = this.value.replace(/\D/g,''); });

    // Direct pay toggle
    $('#bundle-direct-pay-toggle').on('click', function () {
      $('#bundle-direct-pay-form').slideToggle();
      $('#bundle-direct-pay-quantity').val($('#bundle-qty').val());
    });

    // Add/Remove bundle to cart (AJAX)
    $('.toggle-bundle-btn').on('click', function () {
      const btn = $(this);
      const id  = btn.data('bundle-id');

      $.post('{{ route('cart.toggleBundle') }}', {
        _token: '{{ csrf_token() }}',
        bundle_id: id
      })
      .done(function (resp) {
        if (!resp || !resp.success) { alert(resp?.message || 'Failed'); return; }

        if (resp.action === 'added') {
          btn.removeClass('btn-primary').addClass('btn-success');
          btn.find('i').removeClass('bi-cart-plus').addClass('bi-check-circle');
          btn.find('.cart-btn-text').text(ADDED);
          btn.data('in-cart', true);
        } else {
          btn.removeClass('btn-success').addClass('btn-primary');
          btn.find('i').removeClass('bi-check-circle').addClass('bi-cart-plus');
          btn.find('.cart-btn-text').text(ADD_TO_CART);
          btn.data('in-cart', false);
        }

        // update cart bubble if you have one
        const countEl = document.getElementById('cart-count');
        const bubble  = document.getElementById('cart-bubble');
        if (countEl && bubble) {
          countEl.textContent = resp.cart_count;
          bubble.style.display = resp.cart_count > 0 ? 'inline-grid' : 'none';
        }
      })
      .fail(function (xhr) {
        if (xhr.status === 401) { window.location.href = '{{ route('login') }}'; }
        else { alert('Something went wrong.'); }
      });
    });
  })();
</script>
@endpush
