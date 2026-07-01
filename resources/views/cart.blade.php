@extends('layouts.app')
@php use Illuminate\Support\Str; @endphp
@section('title', 'ბუკინისტები | კალათა')

@section('content')
<style>
  .cart-page      { position:relative; top:30px; padding-bottom:5%; }
  .card-soft      { border-radius:8px; }
  .sticky-summary { position: sticky; top: 90px; }
  .thumb          { width:84px; height:116px; object-fit:cover; border-radius:8px; background:#f4f5f7; }
  .cart-product .thumb { box-shadow:0 8px 20px rgba(17, 24, 39, .12) !important; }
  .table > :not(caption) > * > * { vertical-align: middle; }
  .qty-btn        { width:40px; height:40px; border-color:#cfd6df; color:#273241; font-weight:700; }
  .small-muted    { font-size:12px; color:#6c757d; }
  .summary-row    { display:flex; justify-content:space-between; align-items:center; }
  .summary-row + .summary-row { margin-top:.25rem; }
  .summary-total  { font-size:1.15rem; font-weight:700; }
  .cart-page-heading { display:flex; align-items:center; justify-content:center; gap:14px; margin:0 0 34px; padding:18px 0 22px; color:#1f2937; border-bottom:1px solid #edf1f5; }
  .cart-page-heading::before { display:none !important; }
  .cart-page-heading .cart-heading-icon { width:42px; height:42px; display:inline-flex; align-items:center; justify-content:center; border:1px solid #dce3ea; border-radius:8px; background:#fff; color:#64748b; box-shadow:0 8px 18px rgba(17, 24, 39, .07); }
  .cart-page-heading .cart-heading-icon i { margin:0; font-size:20px; color:#64748b; }
  .cart-page-heading strong { font-size:26px; line-height:1.2; font-weight:800; color:#1f2937; }
  .cart-empty-card { min-height:320px; display:flex; align-items:center; justify-content:center; padding:54px 28px; border:1px solid #e2e7ee; border-radius:8px; background:#fff; box-shadow:0 12px 28px rgba(18, 38, 63, .08); }
  .cart-empty-content { width:100%; max-width:560px; margin:auto; text-align:center; }
  .cart-empty-icon { width:76px; height:76px; display:inline-flex; align-items:center; justify-content:center; margin-bottom:22px; border:1px solid #fee2e2; border-radius:8px; background:#fff5f5; color:#dc3545; }
  .cart-empty-icon i { font-size:38px; line-height:1; color:#dc3545; }
  .cart-empty-title { margin:0 0 8px; font-size:30px; line-height:1.25; font-weight:800; color:#1f2937; }
  .cart-empty-text { margin:0 auto 24px; color:#6b7280; font-size:16px; line-height:1.6; }
  .cart-empty-action { display:inline-flex; align-items:center; justify-content:center; gap:8px; min-width:260px; min-height:46px; padding:11px 22px; border:1px solid #273241; border-radius:8px; background:#273241; color:#fff; font-weight:500; text-decoration:none; box-shadow:0 10px 20px rgba(39, 50, 65, .16); transition:transform .2s ease, box-shadow .2s ease, background-color .2s ease, border-color .2s ease; }
  .cart-empty-action:hover { border-color:#1f2937; background:#1f2937; color:#fff; text-decoration:none; transform:translateY(-2px); box-shadow:0 14px 26px rgba(39, 50, 65, .22); }
  .cart-empty-action:focus { color:#fff; text-decoration:none; }
  .cart-toolbar   { display:flex; justify-content:flex-start; margin-bottom:18px; }
  .cart-clear-btn { border-radius:8px; padding:10px 14px; font-weight:600; background:#fff; }
  .cart-items-card { border:1px solid #e2e7ee; border-radius:8px; overflow:hidden; background:#fff; box-shadow:0 12px 28px rgba(18, 38, 63, .08); }
  .cart-items-header { display:flex; align-items:center; gap:10px; padding:16px 20px; background:#fbfcfd; border-bottom:1px solid #e5eaf0; color:#1f2937; }
  .cart-items-header i { width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center; border:1px solid #dce3ea; border-radius:8px; background:#fff; color:#273241; }
  .cart-table { color:#111827; }
  .cart-table thead th { padding:14px 20px; background:#f7f9fb; border-bottom:1px solid #d9e0e8; color:#111827; font-size:14px; font-weight:800; white-space:nowrap; }
  .cart-table tbody td { padding:18px 20px; border-color:#edf1f5; }
  .cart-table tbody tr:hover { background:#fcfdff; }
  .cart-product { display:flex; align-items:center; gap:18px; min-width:330px; }
  .cart-product-title { color:#0d6efd; font-size:18px; line-height:1.45; font-weight:800; }
  .cart-product-title:hover { color:#0a58ca; }
  .cart-author { margin-top:4px; color:#6b7280; font-size:13px; }
  .cart-stock { color:#6b7280; font-size:13px; }
  .cart-qty-control { width:132px; max-width:132px; margin:auto; display:inline-flex; flex-wrap:nowrap; align-items:stretch; justify-content:center; border:1px solid #cfd6df; border-radius:8px; overflow:hidden; background:#fff; }
  .cart-qty-control .btn { flex:0 0 40px; border:0; border-radius:0; background:#f8fafc; }
  .cart-qty-control .btn:hover { background:#edf2f7; color:#111827; }
  .cart-qty-control .quantity-input { flex:0 0 50px; width:50px; min-width:50px; height:40px; border:0; border-left:1px solid #d8dee6; border-right:1px solid #d8dee6; font-weight:800; color:#111827; background:#fff; box-shadow:none; }
  .cart-price { font-size:18px; font-weight:800; color:#111827; white-space:nowrap; }
  .cart-price span { display:block; margin-top:2px; font-size:13px; font-weight:700; color:#111827; }
  .cart-remove-btn { min-width:156px; border-radius:8px; padding:9px 12px; font-weight:700; background:#fff; }
  .cart-remove-btn i { margin-right:6px; }
  .bundle-list { padding-left:18px; }
  @media (max-width: 767.98px) {
    .cart-page { top:18px; }
    .cart-page-heading { justify-content:flex-start; margin-bottom:22px; padding:12px 0 16px; gap:10px; }
    .cart-page-heading .cart-heading-icon { width:36px; height:36px; }
    .cart-page-heading strong { font-size:21px; }
    .cart-empty-card { min-height:280px; padding:36px 18px; }
    .cart-empty-icon { width:64px; height:64px; margin-bottom:18px; }
    .cart-empty-icon i { font-size:32px; }
    .cart-empty-title { font-size:23px; }
    .cart-empty-text { font-size:15px; }
    .cart-empty-action { width:100%; min-width:0; }
    .cart-items-header { padding:14px 16px; }
    .cart-table thead { display:none; }
    .cart-table tbody tr { display:block; padding:16px; border-bottom:1px solid #edf1f5; }
    .cart-table tbody td { display:block; padding:8px 0; border:0; text-align:left !important; }
    .cart-product { min-width:0; align-items:flex-start; gap:14px; }
    .thumb { width:72px; height:100px; }
    .cart-product-title { font-size:16px; }
    .cart-qty-control { margin:0; }
    .cart-price { font-size:17px; }
  }
  @media print {
    .btn, .alert, .sticky-summary, .payment-card { display:none !important; }
    .card, .table { box-shadow:none !important; }
  }
  .chosen-container-single .chosen-single span {
 
    margin-top: 8px !important;
  
}
</style>

<div class="container cart-page">

  <h5 class="section-title cart-page-heading">
    <span class="cart-heading-icon"><i class="bi bi-cart-check-fill"></i></span>
    <strong>{{ __('messages.yourCart') }}</strong>
  </h5>

  {{-- Flash --}}
  @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  {{-- Empty cart --}}
  @if (!$cart || $cart->cartItems->isEmpty())
    <div class="cart-empty-card">
      <div class="cart-empty-content">
        <div class="cart-empty-icon"><i class="bi bi-cart-x-fill"></i></div>
        <h3 class="cart-empty-title">{{ __('messages.emptyCart') }}</h3>
        <p class="cart-empty-text">{{ __('messages.canAdd') }}</p>
        <a href="{{ route('books') }}" class="cart-empty-action">
          <i class="bi bi-book-half"></i>
          <span>{{ __('messages.seeBooks') }}</span>
        </a>
      </div>
    </div>
  @else

  {{-- Clear cart --}}
  <form action="{{ route('cart.clear') }}" method="POST" class="cart-toolbar"
        onsubmit="return confirm('{{ __('messages.confirmClearCart') }}')">
    @csrf
    <button type="submit" class="btn btn-outline-danger btn-sm cart-clear-btn">
      <i class="bi bi-trash-fill"></i> {{ __('messages.cleaCart') }}
    </button>
  </form>

  <div class="row g-4">
    {{-- Left: items --}}
    <div class="col-lg-8">
      <div class="cart-items-card">
        <div class="cart-items-header">
          <i class="bi bi-bag"></i>
          <span class="fw-semibold">{{ __('messages.product') }}</span>
        </div>

        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table align-middle mb-0 cart-table">
              <thead>
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
                      <div class="cart-product">
                        @if ($item->bundle->image_url)
                          <img src="{{ $item->bundle->image_url }}" class="thumb shadow" alt="სტატიის სურათი">
                        @endif
                        <div>
                          <a href="{{ route('bundles.show', $item->bundle->slug) }}" class="text-decoration-none">
                            <span class="badge bg-info me-1">Bundle</span>
                            <span class="cart-product-title">{{ $item->bundle->title }}</span>
                          </a>
                          <ul class="small mb-0 mt-2 text-muted bundle-list">
                            @foreach ($item->bundle->books as $b)
                              <li>{{ $b->title }} × {{ $b->pivot->qty }}</li>
                            @endforeach
                          </ul>

                          {{-- mobile delete --}}
                          <div class="d-md-none mt-2">
                            <form action="{{ route('cart.removeBundle', ['bundle' => $item->bundle_id]) }}" method="POST"
                                  onsubmit="return confirm('ნამდვილად გსურს წაშლა?');">
                              @csrf
                              <button type="submit" class="btn btn-outline-danger btn-sm cart-remove-btn"><i class="bi bi-trash3"></i>{{ __('messages.RemoveFomCart') }}</button>
                            </form>
                          </div>
                        </div>
                      </div>

                    @else
                      @if ($item->book)
                        <div class="cart-product">
                          @if ($item->book->photo)
                            <img src="{{ asset('storage/'.$item->book->photo) }}"
                                 onerror="this.onerror=null;this.src='{{ asset('images/default_image.png') }}';"
                                 class="thumb shadow-sm" alt="{{ $item->book->title }}">
                          @endif
                          <div>
                            <a href="{{ route('full', ['title' => Str::slug($item->book->title), 'id' => $item->book->id]) }}"
                               class="cart-product-title text-decoration-none">{{ $item->book->title }}</a>
                            <div class="cart-author">{{ $item->book->author->name ?? '' }}</div>

                            {{-- mobile delete --}}
                            <div class="d-md-none mt-2">
                              <form action="{{ route('cart.remove', ['book' => $item->book_id]) }}" method="POST"
                                    onsubmit="return confirm('ნამდვილად გსურს წაშლა?');">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm cart-remove-btn"><i class="bi bi-trash3"></i>{{ __('messages.RemoveFomCart') }}</button>
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
                    <div class="cart-stock mb-2">
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
                    <div class="input-group input-group-sm justify-content-center cart-qty-control">
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
                  <td class="text-center fw-semibold cart-price">
                    {{ number_format($item->price * $item->quantity) }}
                    <span>{{ __('messages.lari') }}</span>
                  </td>

                  {{-- Desktop delete --}}
                  <td class="text-center d-none d-md-table-cell">
                    @if ($item->bundle_id)
                      <form action="{{ route('cart.removeBundle', ['bundle' => $item->bundle_id]) }}" method="POST"
                            onsubmit="return confirm('ნამდვილად გსურს წაშლა?');">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm cart-remove-btn"><i class="bi bi-trash3"></i>{{ __('messages.RemoveFomCart') }}</button>
                      </form>
                    @elseif($item->book_id)
                      <form action="{{ route('cart.remove', ['book' => $item->book_id]) }}" method="POST"
                            onsubmit="return confirm('ნამდვილად გსურს წაშლა?');">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm cart-remove-btn"><i class="bi bi-trash3"></i>{{ __('messages.RemoveFomCart') }}</button>
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
