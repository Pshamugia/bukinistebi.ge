@extends('layouts.app')

@section('content')
<!-- Display messages -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

<style>
  .modal-backdrop { z-index: 104044 !important; }
  .modal { z-index: 105044 !important; }
  .alert-danger { z-index: 999944 !important; position: relative; }

  /* Make Chosen show invalid state like Bootstrap */
  .chosen-container .chosen-single,
  .chosen-container .chosen-choices { border-radius: .375rem; }
  .is-invalid + .chosen-container .chosen-single,
  .is-invalid + .chosen-container .chosen-choices {
    border: 1px solid #dc3545 !important;
    box-shadow: 0 0 0 .25rem rgba(220,53,69,.25);
  }
</style>

@if ($errors->any())
  <div class="alert alert-danger">
      <ul class="mb-0">
          @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
          @endforeach
      </ul>
  </div>
@endif

@if (session('success'))
  <div class="alert alert-success">
      {{ session('success') }}
  </div>
@endif

<div class="container" style="position: relative; top:30px;">
  <h5 class="section-title" style="position: relative; margin-bottom:25px; padding-bottom:25px; align-items: left; justify-content: left;">
      <strong><i class="bi bi-stack-overflow"></i> {{ __('messages.uploadBook') }}</strong>
  </h5>

  <!-- Book Upload Form -->
  <!-- novalidate => let Laravel handle errors; browser won't block hidden required fields -->
  <form action="{{ route('publisher.books.store') }}" method="POST" enctype="multipart/form-data" novalidate>
      @csrf

      <!-- Language (must stay native, not Chosen) -->
      <div class="mb-3">
          <label for="language" class="form-label">{{ __('messages.language') }}</label>
          <div class="input-group">
              <span class="input-group-text"><i class="bi bi-translate"></i></span>
              <select name="language" id="language"
                      class="form-select @error('language') is-invalid @enderror" required>
                  <option value="ka" {{ old('language', request('lang')) == 'ka' ? 'selected' : '' }}>ქართული</option>
    <option value="en" {{ old('language', request('lang')) == 'en' ? 'selected' : '' }}>English</option>
    <option value="ru" {{ old('language', request('lang')) == 'ru' ? 'selected' : '' }}>Русский</option>
              </select>
              @error('language') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
      </div>

      <!-- Title -->
      <div class="mb-3">
          <label for="title" class="form-label">{{ __('messages.bookTitle') }}</label>
          <div class="input-group">
              <span class="input-group-text"><i class="bi bi-book"></i></span>
              <input type="text" name="title" id="title"
                     value="{{ old('title') }}"
                     class="form-control @error('title') is-invalid @enderror" required>
              @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
      </div>

      <!-- Description -->
      <div class="mb-3">
          <label for="description" class="form-label">{{ __('messages.brief') }}</label>
          <div class="input-group">
              <span class="input-group-text"><i class="bi bi-card-text"></i></span>
              <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" required>{{ old('description') }}</textarea>
              @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
      </div>

      <!-- Photos -->
      <div style="border:1px solid #c0c0c0; border-radius:5px; padding:25px; margin:20px 0">
          <div class="mb-3">
              <label for="photo" class="form-label">{{ __('messages.coverPhoto') }}</label>
              <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" id="photo">
              @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="mb-3">
              <label for="photo_2" class="form-label">{{ __('messages.photo2') }}</label>
              <input type="file" name="photo_2" class="form-control @error('photo_2') is-invalid @enderror" id="photo_2">
              @error('photo_2') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="mb-3">
              <label for="photo_3" class="form-label">{{ __('messages.photo3') }}</label>
              <input type="file" name="photo_3" class="form-control @error('photo_3') is-invalid @enderror" id="photo_3">
              @error('photo_3') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="mb-3">
              <label for="photo_4" class="form-label">{{ __('messages.photo4') }}</label>
              <input type="file" name="photo_4" class="form-control @error('photo_4') is-invalid @enderror" id="photo_4">
              @error('photo_4') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
      </div>

      <!-- Cover (must stay native, not Chosen) -->
      <div class="mb-3">
          <label for="cover" class="form-label">{{ __('messages.coverType') }}</label>
          <div class="input-group">
              <span class="input-group-text"><i class="bi bi-journal-bookmark-fill"></i></span>
              <select name="cover" id="cover"
                      class="form-select @error('cover') is-invalid @enderror" required>
                  <option value="" disabled {{ old('cover') ? '' : 'selected' }}>{{ __('messages.chooseCover') }}</option>
                  <option value="რბილი" {{ old('cover') == 'რბილი' ? 'selected' : '' }}>{{ __('messages.softCover') }}</option>
                  <option value="მაგარი" {{ old('cover') == 'მაგარი' ? 'selected' : '' }}>{{ __('messages.hardCover') }}</option>
              </select>
              @error('cover') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
      </div>

      <!-- Price -->
      <div class="mb-3">
          <label for="price" class="form-label">{{ __('messages.price') }}</label>
          <div class="input-group">
              <span class="input-group-text"><i class="bi bi-currency-exchange"></i></span>
              <input type="number" step="0.01" name="price" id="price"
                     value="{{ old('price') }}"
                     class="form-control @error('price') is-invalid @enderror" required>
              @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
      </div>

      <!-- Quantity -->
      <div class="mb-3">
          <label for="quantity" class="form-label">{{ __('messages.quantity') }}</label>
          <div class="input-group">
              <span class="input-group-text"><i class="bi bi-123"></i></span>
              <input type="number" name="quantity" id="quantity"
                     value="{{ old('quantity') }}"
                     class="form-control @error('quantity') is-invalid @enderror" required>
              @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
      </div>

      <!-- Publishing Date -->
      <div class="mb-3">
          <label for="publishing_date" class="form-label">{{ __('messages.yearofpublicaion') }}</label>
          <div class="input-group">
              <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
              <input type="text" name="publishing_date" id="publishing_date"
                     value="{{ old('publishing_date') }}"
                     class="form-control @error('publishing_date') is-invalid @enderror">
              @error('publishing_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
      </div>

      <!-- Pages -->
      <div class="mb-3">
          <label for="pages" class="form-label">{{ __('messages.numberOfPages') }}</label>
          <div class="input-group">
              <span class="input-group-text"><i class="bi bi-file-earmark-text"></i></span>
              <input type="number" name="pages" id="pages"
                     value="{{ old('pages') }}"
                     class="form-control @error('pages') is-invalid @enderror">
              @error('pages') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
      </div>

      <!-- Book Condition -->
      <div class="mb-3">
          <label for="status" class="form-label">{{ __('messages.bookCondition') }}</label>
          <div class="input-group">
              <span class="input-group-text"><i class="bi bi-info-circle"></i></span>
              <input type="text" name="status" id="status"
                     value="{{ old('status') }}"
                     class="form-control @error('status') is-invalid @enderror">
              @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
      </div>

      <!-- Author (Chosen) -->
      <div class="mb-3">
          <label for="author_id" class="form-label">{{ __('messages.author') }}</label>
          <select name="author_id" id="author_id"
        class="form-control chosen-select @error('author_id') is-invalid @enderror"
        data-placeholder="{{ __('messages.selectAuthor') }}" required>
    <option value="">{{ __('messages.selectAuthor') }}</option>

    @foreach ($authors as $author)
        <option value="{{ $author->id }}"
            {{ old('author_id') == $author->id ? 'selected' : '' }}>
            {{ $author->getLocalizedName() }}
        </option>
    @endforeach
</select>

          @error('author_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror

          <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#addAuthorModal">
              <i class="bi bi-plus-circle"></i> {{ __('messages.addAuthor') }}
          </button>
      </div>

      <!-- Genres (Chosen multi) -->
      <div class="mb-3">
          <label for="genre_id" class="form-label">{{ __('messages.category') }}</label>
          <select name="genre_id[]" class="form-select categoria chosen-select @error('genre_id') is-invalid @enderror" id="genre_id" multiple>
              @foreach ($genres as $genre)
    @php $genreName = $genre->getLocalizedName(); @endphp

    @if(!in_array($genreName, ['Souvenirs', 'სუვენირები', 'Сувениры']))
        <option value="{{ $genre->id }}"
            {{ collect(old('genre_id', []))->contains($genre->id) ? 'selected' : '' }}>
            {{ $genreName }}
        </option>
    @endif
@endforeach

          </select>
          @php
            $genreErrors = collect($errors->get('genre_id'))
                           ->merge(collect($errors->get('genre_id.*'))->flatten());
          @endphp
          @if($genreErrors->isNotEmpty())
              <div class="invalid-feedback d-block">{{ $genreErrors->first() }}</div>
          @endif
      </div>

      <button type="submit" class="btn btn-primary">
          <i class="bi bi-cloud-upload-fill"></i> {{ __('messages.upload') }}
      </button>
  </form>
</div>

<!-- Add Author Modal -->
<div class="modal fade" id="addAuthorModal" tabindex="-1" aria-labelledby="addAuthorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="addAuthorModalLabel">{{ __('messages.addAuthor') }}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <form id="addAuthorForm">
                  <div class="mb-3">
                      <label for="new_author_name" class="form-label">{{ __('messages.authorName') }}</label>
<div class="mb-2">
    <label class="form-label">ქართული</label>
    <input type="text" id="author_name" class="form-control">
</div>

<div class="mb-2">
    <label class="form-label">English</label>
    <input type="text" id="author_name_en" class="form-control">
</div>

<div class="mb-2">
    <label class="form-label">Русский</label>
    <input type="text" id="author_name_ru" class="form-control">
</div>

<div id="authorError" class="text-danger mt-2"></div>
                      <div id="authorError" class="text-danger"></div>
                  </div>
                  <button type="submit" class="btn btn-primary">{{ __('messages.add') }}</button>
              </form>
          </div>
      </div>
  </div>
</div>

<!-- Upload Spinner Overlay -->
<div id="uploadSpinner" 
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(255,255,255,0.8); z-index:9999;
            justify-content:center; align-items:center; flex-direction:column;">
    
    <div class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status"></div>
    <div class="mt-3 fs-4 text-primary">იტვირთება, გთხოვთ დაელოდოთ...</div>
</div>


@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form[action="{{ route('publisher.books.store') }}"]');

    if (form) {
        form.addEventListener('submit', function () {
            document.getElementById('uploadSpinner').style.display = 'flex';
        });
    }
});
</script>



<script>
(function () {
  // SAFETY NET #1: if anything globally initialized Chosen on language/cover, destroy it.
  function forceNativeSelect(ids) {
    ids.forEach(function(id){
      var $el = $('#'+id);
      // if a chosen container exists, remove it and show native select
      if ($el.next('.chosen-container').length) {
        try { $el.chosen('destroy'); } catch(e) {}
        $el.next('.chosen-container').remove();
      }
      $el.css('display',''); // ensure visible
    });
  }

  $(document).ready(function () {
    console.log('Initializing Chosen safely...');

    // Initialize Chosen ONLY on explicit .chosen-select
    $('.chosen-select').each(function(){
      var $s = $(this);
      if (!$s.next('.chosen-container').length) {
        $s.chosen({
          width: '100%',
          placeholder_text_single: "მონიშნე ავტორი",
          no_results_text: "Oops, nothing found!"
        });
      }
    });

    // Make sure language/cover are native and focusable
    forceNativeSelect(['language','cover']);

    // SAFETY NET #2: if some late script re-wraps them, unwrap again shortly after
    setTimeout(function(){ forceNativeSelect(['language','cover']); }, 100);
    setTimeout(function(){ forceNativeSelect(['language','cover']); }, 500);

    // SAFETY NET #3: before submit, remove "required" from any select that is hidden by Chosen
    $('form[action="{{ route('publisher.books.store') }}"]').on('submit', function(){
      $(this).find('select[required]').each(function(){
        var $s = $(this);
        var isHidden = $s.is(':hidden') || $s.css('display') === 'none';
        if (isHidden) {
          $s.attr('data-was-required','1'); // mark it
          $s.prop('required', false);       // avoid "not focusable"
        }
      });
      // optional: re-add required after short delay (not needed on server redirect)
      setTimeout(function(){
        $('select[data-was-required="1"]').prop('required', true).removeAttr('data-was-required');
      }, 2000);
    });
  });
})();
</script>

<script>
// Add Author (AJAX)
$('#addAuthorForm').on('submit', function (e) {
    e.preventDefault();

    const data = {
        name:    $('#author_name').val(),
        name_en: $('#author_name_en').val(),
        name_ru: $('#author_name_ru').val(),
        _token: $('meta[name="csrf-token"]').attr('content')
    };

    $.ajax({
        url: "{{ route('publisher.authors.store') }}",
        type: 'POST',
        data: data,

        success: function (response) {
            if (!response.success) return;

            const author = response.author;

            const text =
                author.name_ru ||
                author.name_en ||
                author.name;

            const newOption = new Option(
                text,
                author.id,
                true,
                true
            );

            $('#author_id')
                .append(newOption)
                .trigger('chosen:updated');

            $('#addAuthorModal').modal('hide');

            $('#author_name').val('');
            $('#author_name_en').val('');
            $('#author_name_ru').val('');
            $('#authorError').text('');
        },

        error: function (xhr) {
            if (xhr.responseJSON?.errors) {
                $('#authorError').text(
                    Object.values(xhr.responseJSON.errors)[0][0]
                );
            } else {
                $('#authorError').text('Server error');
            }
        }
    });
});
</script>

<script>
// Language switch
document.getElementById('language').addEventListener('change', function () {
    const lang = this.value;
    const url = new URL(window.location.href);
    url.searchParams.set('lang', lang);
    window.location.href = url;
});
</script>
@endsection
