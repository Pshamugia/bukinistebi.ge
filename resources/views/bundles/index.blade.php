@extends('layouts.app')
@section('title', __('Bundles'))
@section('og')
  <meta property="og:type" content="website">
  <meta property="og:title" content="ნაკრები წიგნები | BUKINISTEBI.GE">
  <meta property="og:description" content="ბუკინისტური წიგნები აქციაში - შეკვეთა ონლაინ.">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:image" content="{{ asset('images/bundles/bundle_books.webp') }}">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta name="twitter:card" content="summary_large_image">
@endsection
@section('content')
<h5 class="section-title"
style="position: relative; margin-bottom:25px; top:30px;   align-items: left;
justify-content: left;">
<strong>
  <i class="bi bi-tags"></i> {{ __('messages.sets') }}
</strong>
</h5>

<div class="container" style="position:relative;top:50px' padding-bottom:30px">
  

    

  <div class="row g-3">
    @foreach($bundles as $b)
      @php $available = $b->availableQuantity(); @endphp
      <div class="col-12 col-md-6 col-lg-4">
        <div class="card h-100">
          @if($b->image)
          <div class="image-container"
          style="background-image: url('{{ asset('images/default_image.png') }}');">
          <a class="" href="{{ route('bundles.show', $b->slug) }}"> 
            <img src="{{ asset('storage/' . $b->image) }}" alt="{{ $b->title }}"
              class="cover img-fluid" style="border-radius: 8px 8px 0 0; object-fit: cover;"
              onerror="this.onerror=null;this.src='{{ asset('images/default_image.png') }}';"> </a>
      </div>          @endif
          <div class="card-body d-flex flex-column">
            <h4 class="card-title">{{ $b->title }}</h4>
            <div class="mb-2">
              <div><del>{{ number_format($b->original_price) }} GEL</del> → <b>{{ number_format($b->price) }} GEL</b></div>
              <small class="text-success">  {{ __('messages.save') }} {{ number_format($b->savings) }} GEL</small>
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
              
              <a class="btn btn-primary w-100" href="{{ route('bundles.show', $b->slug) }}"><span> {{ __('ვრცლად') }} </span> </a>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="mt-3">{{ $bundles->links('pagination.custom-pagination') }}</div>
</div>
@endsection
