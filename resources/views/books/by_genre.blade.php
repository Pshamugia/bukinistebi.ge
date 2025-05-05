@extends('layouts.app')

@section('title', $genre->name . ' წიგნები')

@section('content')


<h5 class="section-title" style="position: relative; margin-bottom:25px; top:-10px; padding-bottom:25px; align-items: left;
    justify-content: left;">     <strong>
        <i class="bi bi-bookmarks-fill"></i> კატეგორია: {{ $genre->name }}
    </strong>
</h5> 

     <!-- Featured Books -->
<div class="container mt-5" style="position:relative; margin-top: -15px !important">
  
    <div class="row">
        @foreach ($books as $book)
        <div class="col-md-3" style="position: relative; padding-bottom: 25px;">
            <div class="card book-card shadow-sm" style="border: 1px solid #f0f0f0; border-radius: 8px;">
                <a href="{{ route('full', ['title' => Str::slug($book->title), 'id' => $book->id]) }}" class="card-link">
                    <div style="background-image: url('{{ asset('images/default_image.png') }}'); background-size: cover; background-position: center;">
                        <img src="{{ asset('storage/' . $book->photo) }}" 
                             alt="{{ $book->title }}" 
                             class="cover img-fluid" 
                             style="border-radius: 8px 8px 0 0; object-fit: cover;" 
                             loading="lazy" 
                             onerror="this.onerror=null;this.src='{{ asset('images/default_image.png') }}';">
                    </div>
                </a>
                <div class="card-body">
                    <h4 class="text-primary font-weight-bold">{{ \Illuminate\Support\Str::limit($book->title, 18) }}</h4>
                    <p style="font-size: 14px; color: #555;">
                        <a href="{{ route('full_author', ['id' => $book->author_id, 'name' => Str::slug($book->author->name)]) }}" style="text-decoration: none; color: #007bff;">
                            <span>  {{ $book->author->name }} </span>
                        </a>
                    </p>
                    <p style="font-size: 18px; color: #333;">
                        <strong> {{ number_format($book->price) }} <a style="color: #b8b5b5;">&#x20BE; </strong></a> 
                        <span style="position: relative; top:5px">
                            @if($book->quantity == 0)
                            <span style="font-size: 13px; float: right; color:red">მარაგი ამოწურულია</span>
     @elseif($book->quantity == 1)
     <span style="font-size: 13px; float: right;">მარაგშია 1 ცალი</span>
     @else
     <span style="font-size: 13px; float: right;">მარაგშია {{ $book->quantity }} ცალი</span>
     @endif
                            </span> </p>
    
                    @if (!auth()->check() || auth()->user()->role !== 'publisher')
                        @if (in_array($book->id, $cartItemIds))
                            <button class="btn btn-success toggle-cart-btn w-100" data-product-id="{{ $book->id }}" data-in-cart="true">
                                <span style="position:relative;top:3px;"> <i class="bi bi-check-circle"></i> დამატებულია</span>
                            </button>
                        @else
                            <button class="btn btn-primary toggle-cart-btn w-100" data-product-id="{{ $book->id }}" data-in-cart="false">
                                <span style="position:relative;top:3px;"> <i class="bi bi-cart-plus"></i> დაამატე კალათაში </span>
                            </button>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    @endforeach

       
    </div>
</div>
    {{ $books->links('pagination.custom-pagination') }}
@endsection
