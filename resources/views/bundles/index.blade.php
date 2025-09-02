@extends('layouts.app')
@section('title', __('Bundles'))

@section('content')
<div class="container" style="position:relative;top:50px">
  <h4 class="mb-3"> <i class="bi bi-tags"></i>
      {{ __('messages.sale') }}</h4>
  <div class="row g-3">
    @foreach($bundles as $b)
      @php $available = $b->availableQuantity(); @endphp
      <div class="col-12 col-md-6 col-lg-4">
        <div class="card h-100">
          @if($b->image)
          <div class="image-container"
          style="background-image: url('{{ asset('images/default_image.png') }}');">
          <a class="btn btn-primary w-100" href="{{ route('bundles.show', $b->slug) }}"> 
            <img src="{{ asset('storage/' . $b->image) }}" alt="{{ $b->title }}"
              class="cover img-fluid" style="border-radius: 8px 8px 0 0; object-fit: cover;"
              onerror="this.onerror=null;this.src='{{ asset('images/default_image.png') }}';"> </a>
      </div>          @endif
          <div class="card-body d-flex flex-column">
            <h4 class="card-title">{{ $b->title }}</h4>
            <div class="mb-2">
              <div><del>{{ number_format($b->original_price) }} GEL</del> → <b>{{ number_format($b->price) }} GEL</b></div>
              <small class="text-success">{{ __('You save') }} {{ number_format($b->savings) }} GEL</small>
            </div>
            <ul class="small mb-3">
              @foreach($b->books->take(3) as $bk)
                <li>{{ $bk->title }} × {{ $bk->pivot->qty }}</li>
              @endforeach
              @if($b->books->count() > 3)
                <li>+ {{ $b->books->count() - 3 }} more…</li>
              @endif
            </ul>
            <div class="mt-auto">
              <div class="mb-2">
                @if($available > 0)
                  <span class="badge bg-success">{{ __('In stock') }}: {{ $available }}</span>
                @else
                  <span class="badge bg-secondary">{{ __('Out of stock') }}</span>
                @endif
              </div>
              <a class="btn btn-primary w-100" href="{{ route('bundles.show', $b->slug) }}">{{ __('ვრცლად') }}</a>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="mt-3">{{ $bundles->links() }}</div>
</div>
@endsection
