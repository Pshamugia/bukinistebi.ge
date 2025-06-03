@extends('layouts.app')

@section('title', $booknews->title)  

@section('content')
<div class="container mt-5" style="position: relative; padding-bottom: 5%">
    <div class="row">
        <!-- Book Image -->
        <div class="col-md-5">
            <div class="main-image-container mb-3">
                @if($booknews->image)
                    <img src="{{ asset('storage/' . $booknews->image) }}" 
                         alt="{{ $booknews->title }}" 
                         class="coverFull img-fluid" 
                         id="thumbnailImage" 
                         style="cursor: pointer;" 
                         data-bs-toggle="modal" 
                         data-bs-target="#imageModal">
                @else
                    <img src="{{ asset('public/uploads/default-book.jpg') }}" 
                         alt="Default Image" 
                         class="img-fluid rounded shadow">
                @endif
            </div>
        <div class="share-buttons col-md-12" style="text-align:left; margin-top: 20px;">
            <!-- Facebook -->
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::fullUrl()) }}" target="_blank" class="btn facebook-btn">
                <i class="bi bi-facebook"></i>  
            </a>
        
            <!-- Twitter -->
            <a href="https://twitter.com/intent/tweet?url={{ urlencode(Request::fullUrl()) }}&text=Check this out!" target="_blank" class="btn twitter-btn">
                <i class="bi bi-twitter"></i>  
            </a>
        
            <!-- WhatsApp -->
            <a href="https://api.whatsapp.com/send?text=Check this out! {{ urlencode(Request::fullUrl()) }}" target="_blank" class="btn whatsapp-btn">
                <i class="bi bi-whatsapp"></i>  
            </a>
        </div>
        </div>

        <!-- Book Details -->
        <div class="col-md-7" style="position: relative; margin-top:-6px">
            <h2 style="padding-bottom: 7px">{{ app()->getLocale() === 'en' ? $booknews->title_en : $booknews->title }}</h2>
            
             <p>{{ \Carbon\Carbon::parse($booknews->date_added)->format('d/m/Y') }}</p>

            <!-- Quantity Selector -->
         

            <!-- Add to Cart Button -->
 

            <!-- Book Description -->
            <div class="mt-4">
                
                <p>{!! app()->getLocale() === 'en' ? $booknews->full_en : $booknews->full !!}</p>

                 

                

            </div>
        </div>
    </div>
</div>
<!-- Modal for Enlarged Image -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">{{ $booknews->title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img src="{{ asset('storage/' . $booknews->image) }}" 
                     alt="{{ $booknews->title }}" 
                     id="modalImage" 
                     class="img-fluid">
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    /**
     * Updates the main image (hover effect) and sets it for the modal.
     */
    function updateMainImage(imageUrl) {
        const mainImage = document.getElementById('thumbnailImage');
        const modalImage = document.getElementById('modalImage');

        // Update the main image source
        mainImage.src = imageUrl;

        // Update the modal image source to match the main image
        mainImage.onclick = function () {
            modalImage.src = imageUrl;
        };
    }
</script>
@endsection
 
 