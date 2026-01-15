<!-- jQuery (required by Chosen) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@php
    $locale = $locale ?? app()->getLocale();
@endphp

 


<!-- Chosen JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
<div class="mb-3">
    <label for="language" class="form-label">Language</label>
    <select name="language" id="languageSwitcher" class="form-select" required>
        <option value="ka" {{ old('language', $book->language ?? '') == 'ka' ? 'selected' : '' }}>ქართული</option>
        <option value="en" {{ old('language', $book->language ?? '') == 'en' ? 'selected' : '' }}>English</option>
    </select>
</div>


<div class="form-check mb-3">
    <input type="checkbox" name="auction_only" class="form-check-input" id="auction_only"
        {{ old('auction_only', $book->auction_only ?? false) ? 'checked' : '' }}>
    <label class="form-check-label" for="auction_only">Hide this book everywhere except auction</label>
</div>


<div class="mb-3">
    <label for="title" class="form-label">სახელწოდება</label>
    <div class="input-group">
        <span class="input-group-text"><i class="bi bi-book"></i></span> <input type="text" name="title"
            class="form-control" id="title" value="{{ old('title', $book->title ?? '') }}" required>
    </div>
</div>
<div class="mb-3">
    <label for="price" class="form-label"> ფასი</label>
    <div class="input-group">
        <span class="input-group-text"><i class="bi bi-cash-stack"></i></span>
        <input type="number" name="price" class="form-control" id="price"
            value="{{ old('price', $book->price ?? '') }}" required>
    </div>
</div>


<div class="mb-3">
    <label for="acquisition_price" class="form-label">
        შესყიდვის ფასი (Acquisition price)
    </label>
    <div class="input-group">
        <span class="input-group-text">
            <i class="bi bi-cart-plus"></i>
        </span>
        <input
            type="number"
            step="0.01"
            name="acquisition_price"
            id="acquisition_price"
            class="form-control"
            value="{{ old('acquisition_price', $book->acquisition_price ?? '') }}"
            placeholder="რამდენად შეიძინეთ"
        >
    </div>
    <small class="text-muted">
        მხოლოდ შიდა გამოყენებისთვის — მომხმარებელს არ უჩნდება
    </small>
</div>


{{-- ფასდაკლების კოდი  --}}

{{-- <div class="mb-3">
    <label for="new_price" class="form-label">ფასდაკლებული ფასი (თუ არის)</label>
    <div class="input-group">
        <span class="input-group-text"><i class="bi bi-tag-fill"></i></span>
        <input type="number" name="new_price" class="form-control" id="new_price"
               value="{{ old('new_price', $book->new_price ?? '') }}">
    </div>
</div> --}}

{{-- THUMB IMAGE --}}
<div class="mb-3">
    <label for="thumb_image" class="form-label">
        <i class="bi bi-image"></i> Thumbnail image (small / grid / card)
    </label>

    @if (isset($book) && $book->thumb_image)
        <div class="mb-2">
            <img src="{{ asset('storage/' . $book->thumb_image) }}"
                 class="img-thumbnail"
                 width="120"
                 alt="{{ $book->title }}">
        </div>
    @endif

    <input type="file"
           name="thumb_image"
           class="form-control"
           id="thumb_image"
           accept="image/*">
    <small class="text-muted">
        Recommended: square image, will be resized automatically.
    </small>
</div>


<div class="mb-3">
    <label for="photo" class="form-label"><i class="bi bi-image"></i> ფოტო 1</label>
    @if (isset($book) && $book->photo)
        <div class="mb-2">
            <img src="{{ asset('storage/' . $book->photo) }}" class="img-thumbnail" width="150"
                alt="{{ $book->title }}">
        </div>
    @endif
    <input type="file" name="photo" class="form-control" id="photo" {{ isset($book) ? '' : 'required' }}>
</div>


<div class="mb-3">
    <label for="photo_2" class="form-label"><i class="bi bi-image"></i> ფოტო 2</label>
    @if (isset($book) && $book->photo_2)
        <div class="mb-2">
            <img src="{{ asset('storage/' . $book->photo_2) }}" class="img-thumbnail" width="150"
                alt="{{ $book->title }}">
        </div>
    @endif
    <input type="file" name="photo_2" class="form-control" id="photo_2" {{ isset($photo_2) }}>
</div>


<div class="mb-3">
    <label for="photo_3" class="form-label"><i class="bi bi-image"></i> ფოტო 3</label>
    @if (isset($book) && $book->photo_3)
        <div class="mb-2">
            <img src="{{ asset('storage/' . $book->photo_3) }}" class="img-thumbnail" width="150"
                alt="{{ $book->title }}">
        </div>
    @endif
    <input type="file" name="photo_3" class="form-control" id="photo_3" {{ isset($photo_3) }}>
</div>


<div class="mb-3">
    <label for="photo_4" class="form-label"><i class="bi bi-image"></i> ფოტო 4</label>
    @if (isset($book) && $book->photo_4)
        <div class="mb-2">
            <img src="{{ asset('storage/' . $book->photo_4) }}" class="img-thumbnail" width="150"
                alt="{{ $book->title }}">
        </div>
    @endif
    <input type="file" name="photo_4" class="form-control" id="photo_4" {{ isset($photo_4) }}>
</div>



<div class="form-group">
    <label for="video">Video URL</label>
    <input type="text" name="video" class="form-control" value="{{ old('video', $book->video ?? '') }}">
</div>


<div class="mb-3">
    <label for="quantity" class="form-label"> რაოდენობა</label>
    <div class="input-group">
        <span class="input-group-text"><i class="bi bi-box-seam"></i></span>
        <input type="number" name="quantity" class="form-control" id="quantity"
            value="{{ old('quantity', $book->quantity ?? '') }}" required>
    </div>
</div>


<div class="mb-3">
    <label for="condition" class="form-label">მდგომარეობა</label>
    <select name="condition" class="form-select">
        <option value="">ყველა</option>
        <option value="new" {{ old('condition', $book->condition ?? '') == 'new' ? 'selected' : '' }}>
            ახალი
        </option>
        <option value="used" {{ old('condition', $book->condition ?? '') == 'used' ? 'selected' : '' }}>
            მეორადი
        </option>
    </select>
</div>



<div class="mb-3">
    <label for="publishing_date" class="form-label">გამოცემის თარიღი</label>
    <div class="input-group">
        <span class="input-group-text"><i class="bi bi-calendar-event-fill"></i></span>
        <input type="text" name="publishing_date" class="form-control" id="publishing_date"
            value="{{ old('publishing_date', $book->publishing_date ?? '') }}">
    </div>
</div>


<div class="mb-3">
    <label for="manual_created_at"><i class="bi bi-clock-history"></i> საიტზე დადების დრო</label>
    <input type="datetime-local" name="manual_created_at" id="manual_created_at"
        value="{{ old('manual_created_at', isset($book) && $book->manual_created_at ? \Carbon\Carbon::parse($book->manual_created_at)->format('Y-m-d\TH:i') : '') }}">
</div>




<div class="mb-3">
    <label for="cover" class="form-label">ყდის ფორმატი</label>
    <div class="input-group">
        <span class="input-group-text"><i class="bi bi-journal-bookmark-fill"></i></span>
        <input type="text" name="cover" class="form-control" id="cover"
            value="{{ old('cover', $book->cover ?? '') }}">
    </div>
</div>


<div class="mb-3">
    <label for="pages" class="form-label">გვერდების რაოდენობა</label>
    <div class="input-group">
        <span class="input-group-text"><i class="bi bi-file-earmark-text"></i></span>
        <input type="number" name="pages" class="form-control" id="pages"
            value="{{ old('pages', $book->pages ?? '') }}">
    </div>
</div>


<div class="mb-3">
    <label for="status" class="form-label">სტატუსი (შელახული, ახალი)</label>
    <div class="input-group">
        <span class="input-group-text"><i class="bi bi-clipboard-check"></i></span>
        <input type="text" name="status" class="form-control" id="status"
            value="{{ old('status', $book->status ?? '') }}">
    </div>
</div>





<div class="mb-3">
    <label for="description" class="form-label"><i class="bi bi-info-circle"></i> აღწერა</label>
    <textarea name="description" class="form-control" id="description" required>{{ old('description', $book->description ?? '') }}</textarea>
</div>


<div class="mb-3">
    
    <label for="full" class="form-label"><i class="bi bi-journal-richtext"></i> სრული ტექსტი</label> 
    
                                <span style="color:red; font-weight:bold">
// <small>ბუკინისტი: {{ $book->publisher->name ?? $book->publisher->title ?? '—' }}</small> </span>

    <textarea name="full" class="form-control" id="full" required>{{ old('full', $book->full ?? '') }}</textarea>
</div>
<div class="mb-3">


    <div class="mb-3">
        <label for="author_id" class="form-label"><i class="bi bi-person-lines-fill"></i> ავტორი</label>
        <div class="d-flex align-items-start gap-2">
          <select name="author_id" class="form-control chosen-select" id="author_id" data-placeholder="{{ __('messages.selectAuthor') }}">
            <option value="">{{ __('messages.selectAuthor') }}</option>
            @foreach ($authors as $author)
              <option value="{{ $author->id }}"
                      data-name-en="{{ $author->name_en ?? '' }}"
                      data-name-ka="{{ $author->name ?? '' }}"
                      class="{{ $author->name_en ? 'has-en' : '' }} {{ $author->name ? 'has-ka' : '' }}"
                      {{ (isset($book) && $book->author_id == $author->id) ? 'selected' : '' }}>
                {{ $locale === 'en' ? ($author->name_en ?? '') : ($author->name ?? '') }}
              </option>
            @endforeach
          </select>

        
      
          <button type="button"
          class="btn btn-link"
          id="openAddAuthorBtn"
          data-bs-toggle="modal"
          data-bs-target="#addAuthorModal">
    <i class="bi bi-plus-circle"></i> {{ __('messages.addAuthor') }}
  </button>
  
        </div>
      </div>
      
    
    
    
    

    {{-- <div class="mb-3">
    <label for="category_id" class="form-label">Category</label>
    <select name="category_id" class="form-select" id="category_id" required>
        <option value="">Select Category</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ (old('category_id', $book->category_id ?? '') == $category->id) ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</div> --}}

    <div class="mb-3">
        <label for="genre_id"><i class="bi bi-tags"></i> კატეგორია</label>
        <select name="genre_id[]" id="genre_id" class="genres form-control" multiple>
            <option value="">მონიშნე ჟანრი (არასავალდებულო)</option>
            @foreach ($genres as $genre)
    <option value="{{ $genre->id }}"
        data-name-en="{{ $genre->name_en }}"
        data-name-ka="{{ $genre->name }}"
        {{ (isset($book) && $book->genres->contains('id', $genre->id)) ? 'selected' : '' }}>
        {{ $locale === 'en' ? ($genre->name_en ?? $genre->name) : $genre->name }}
    </option>
@endforeach

        
        </select>



        @php
    // Find the Souvenirs genre ID once (works for KA or EN)
    $souvenirGenre = collect($genres)->first(function ($g) {
        return ($g->name ?? '') === 'სუვენირები' || ($g->name_en ?? '') === 'Souvenirs';
    });
    $souvenirGenreId = $souvenirGenre->id ?? null;
@endphp

{{-- SIZE (hidden unless "Souvenirs" selected) --}}
<div class="mb-3" id="sizeWrapper" style="display:none;">
    <label for="size" class="form-label">ზომა</label>
    <select name="size[]" id="size" class="chosen-size form-control" multiple>
        <option value="XS" {{ collect(old('size', explode(',', $book->size ?? '')))->contains('XS') ? 'selected' : '' }}>XS</option>
        <option value="S"  {{ collect(old('size', explode(',', $book->size ?? '')))->contains('S')  ? 'selected' : '' }}>S</option>
        <option value="M"  {{ collect(old('size', explode(',', $book->size ?? '')))->contains('M')  ? 'selected' : '' }}>M</option>
        <option value="L"  {{ collect(old('size', explode(',', $book->size ?? '')))->contains('L')  ? 'selected' : '' }}>L</option>
        <option value="XL" {{ collect(old('size', explode(',', $book->size ?? '')))->contains('XL') ? 'selected' : '' }}>XL</option>
        <option value="XXL" {{ collect(old('size', explode(',', $book->size ?? '')))->contains('XXL') ? 'selected' : '' }}>XXL</option>
        <option value="XXXL" {{ collect(old('size', explode(',', $book->size ?? '')))->contains('XXXL') ? 'selected' : '' }}>XXXL</option>
    </select>
  </div>
  
  



  <style>
    .modal-backdrop{ z-index:104044!important; }
    .modal{ z-index:105044!important; }
    .alert-danger{ z-index:999944!important; position:relative; }
  </style>
  
  <div class="modal fade" id="addAuthorModal" tabindex="-1" aria-labelledby="addAuthorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addAuthorModalLabel">{{ __('messages.addAuthor') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <!-- no form tag -->
            <div class="mb-3">
              <label for="new_author_name" class="form-label">{{ __('messages.authorName') }}</label>
              <input type="text" id="new_author_name" class="form-control">
              <div id="authorError" class="text-danger mt-2"></div>
            </div>
            <button type="button" class="btn btn-primary" id="addAuthorSubmit">
              <span class="spinner-border spinner-border-sm me-2 d-none" id="addAuthorSpinner"></span>
              {{ __('messages.add') }}
            </button>
          </div>
          
      </div>
    </div>
  </div>

  

  <script>
    (function () {
      const SOUVENIR_ID = '{{ $souvenirGenreId }}';
    
      function isSouvenirSelected() {
        const val = $('#genre_id').val() || [];
        if (SOUVENIR_ID) return val.map(String).includes(String(SOUVENIR_ID));
        return $('#genre_id option:selected').toArray().some(opt => {
          const ka = ($(opt).data('name-ka') || '').trim();
          const en = ($(opt).data('name-en') || '').trim();
          return ka === 'სუვენირები' || en === 'Souvenirs';
        });
      }
    
      function toggleSize() {
        if (isSouvenirSelected()) {
          $('#sizeWrapper').show();
        } else {
          $('#size').val([]); // clear if leaving souvenirs
          $('#sizeWrapper').hide();
        }
        $('#size').trigger('chosen:updated');
      }
    
      $(function () {
        $('.chosen-select').chosen({width: '100%', no_results_text: 'Oops, nothing found!'});
        $('#genre_id').chosen({width: '100%', no_results_text: 'Oops, nothing found!'});
        $('#size').chosen({width: '100%', no_results_text: 'Oops, nothing found!'});
    
        $('#genre_id').on('change', toggleSize);
        toggleSize();
      });
    })();
    </script>
    
    



    </div>
    <!-- jQuery (Chosen requires jQuery) -->

    <!-- Chosen JS -->
    <script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>

    <script>
        // Replace the <textarea> with a CKEditor instance
        CKEDITOR.replace('full');
    </script>

    <!-- Initialize Chosen -->
    <script>
        $(document).ready(function() {
            $('.chosen-select').chosen({
                no_results_text: "Oops, nothing found!"
            });

            $('.genres').chosen({
                no_results_text: "Oops, nothing found!"
            });
        });
    </script>

<script>
    $(document).ready(function() {
        $('.chosen-select').chosen({
            no_results_text: "Oops, nothing found!"
        });

        $('.sizes').chosen({
            no_results_text: "Oops, nothing found!"
        });
    });
</script>



<script>
    // init Chosen (keep your own initializers too)
    $(function () {
      $('#author_id').chosen({
        width: '100%',
        placeholder_text_single: "{{ __('messages.selectAuthor') }}"
      });
    });
  
    // Helper to read current language from your switcher
    function currentLang() {
      return ($('#languageSwitcher').val() || 'ka');
    }
  
    // If user types in Chosen and there are no results, open the modal prefilled
    $('#author_id').on('chosen:no-results', function (evt, params) {
      const typed = params.chosen.search_input.val() || '';
      $('#new_author_name').val(typed);
      const modalEl = document.getElementById('addAuthorModal');
      const modal   = bootstrap.Modal.getOrCreateInstance(modalEl);
      modal.show();
    });
  
    // Submit "Add Author" via AJAX and inject into Chosen
    $('#addAuthorSubmit').on('click', function () {
  const name = $('#new_author_name').val().trim();
  if (!name) { $('#authorError').text("{{ __('messages.required') }}"); return; }

  $('#authorError').text('');
  $('#addAuthorSpinner').removeClass('d-none');

  $.ajax({
    url: "{{ url('/admin/authors/quick-store') }}",
    type: 'POST',
    data: {
      new_author_name: name,
      lang: ($('#languageSwitcher').val() || 'ka'),
      _token: '{{ csrf_token() }}'
    }
  })
  .done(function (res) {
    if (!res || !res.success) { $('#authorError').text(res?.message || 'Server error.'); return; }
    const a = res.author;

    let $opt = $('#author_id option[value="'+ a.id +'"]');
    if (!$opt.length) $opt = $('<option/>', { value: a.id }).appendTo('#author_id');

    $opt.attr('data-name-ka', a.name || '')
        .attr('data-name-en', a.name_en || '')
        .attr('class', (a.name_en ? 'has-en ' : '') + (a.name ? 'has-ka' : ''))
        .prop('selected', true);

    const langNow = ($('#languageSwitcher').val() || 'ka');
    $opt.text(langNow === 'en' ? (a.name_en || a.name || '') : (a.name || a.name_en || ''));
    $('#author_id').trigger('chosen:updated');
    if (typeof updateLanguage === 'function') updateLanguage(langNow);

    bootstrap.Modal.getInstance(document.getElementById('addAuthorModal'))?.hide();
    $('#new_author_name').val('');
  })
  .fail(function (xhr) {
    let msg = 'დაფიქსირდა შეცდომა.';
    if (xhr.status === 419) msg = 'CSRF 419 — _token დაემატა მოთხოვნაში.';
    else if (xhr.status === 404) msg = 'Route/URL არასწორია: /admin/authors/quick-store.';
    else if (xhr.status === 422 && xhr.responseJSON?.errors?.new_author_name) {
      msg = xhr.responseJSON.errors.new_author_name[0];
    } else if (xhr.responseJSON?.message) {
      msg = xhr.responseJSON.message;
    }
    $('#authorError').text(msg);
    console.error('Add author failed:', xhr);
  })
  .always(function () {
    $('#addAuthorSpinner').addClass('d-none');
  });
});


  
    // Optional: the “+ add author” button also pre-fills from the Chosen search box
    $('#openAddAuthorBtn').on('click', function(){
      const searchVal = $('#author_id_chosen .chosen-search input').val() || '';
      if (searchVal) $('#new_author_name').val(searchVal);
    });
  </script>
  
<script>
    function updateLanguage(lang) {
        // Author options
        $('#author_id option').each(function () {
            const $option = $(this);
            const hasEn = $option.hasClass('has-en');
            const hasKa = $option.hasClass('has-ka');

            // Show only the correct language
            if ((lang === 'en' && hasEn) || (lang === 'ka' && hasKa)) {
                const newText = lang === 'en' ? $option.data('name-en') : $option.data('name-ka');
                $option.text(newText || '');
                $option.show();
            } else {
                $option.hide();
            }
        });

        $('#author_id').trigger("chosen:updated");

        // Genre text only changes — no filtering needed
        $('#genre_id option').each(function () {
            let text = lang === 'en' ? $(this).data('name-en') : $(this).data('name-ka');
            if (text) $(this).text(text);
        });
        $('#genre_id').trigger("chosen:updated");

        // Set hidden field if used
        $('#langInput').val(lang);
    }

   function updateLanguage(lang) {
    // Author: change text only
    $('#author_id option').each(function () {
      const $opt = $(this);
      const text = (lang === 'en')
        ? ($opt.data('name-en') || $opt.data('name-ka') || $opt.text())
        : ($opt.data('name-ka') || $opt.data('name-en') || $opt.text());
      $opt.text(text).show(); // never hide
    });
    $('#author_id').trigger('chosen:updated');

    // Genres: same idea
    $('#genre_id option').each(function () {
      const text = (lang === 'en')
        ? ($(this).data('name-en') || $(this).data('name-ka') || $(this).text())
        : ($(this).data('name-ka') || $(this).data('name-en') || $(this).text());
      $(this).text(text);
    });
    $('#genre_id').trigger('chosen:updated');

    $('#langInput').val?.(lang);
  }

  $(function () {
    $('#languageSwitcher').on('change', function () {
      updateLanguage(this.value);
    });
    updateLanguage($('#languageSwitcher').val());
  });
</script> 
