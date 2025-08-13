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

{{-- ფასდაკლების კოდი  --}}

{{-- <div class="mb-3">
    <label for="new_price" class="form-label">ფასდაკლებული ფასი (თუ არის)</label>
    <div class="input-group">
        <span class="input-group-text"><i class="bi bi-tag-fill"></i></span>
        <input type="number" name="new_price" class="form-control" id="new_price"
               value="{{ old('new_price', $book->new_price ?? '') }}">
    </div>
</div> --}}

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
    <input type="file" name="photo_2" class="form-control" id="photo" {{ isset($photo_2) }}>
</div>


<div class="mb-3">
    <label for="photo_3" class="form-label"><i class="bi bi-image"></i> ფოტო 3</label>
    @if (isset($book) && $book->photo_3)
        <div class="mb-2">
            <img src="{{ asset('storage/' . $book->photo_3) }}" class="img-thumbnail" width="150"
                alt="{{ $book->title }}">
        </div>
    @endif
    <input type="file" name="photo_3" class="form-control" id="photo" {{ isset($photo_3) }}>
</div>


<div class="mb-3">
    <label for="photo_4" class="form-label"><i class="bi bi-image"></i> ფოტო 4</label>
    @if (isset($book) && $book->photo_4)
        <div class="mb-2">
            <img src="{{ asset('storage/' . $book->photo_4) }}" class="img-thumbnail" width="150"
                alt="{{ $book->title }}">
        </div>
    @endif
    <input type="file" name="photo_4" class="form-control" id="photo" {{ isset($photo_4) }}>
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
    <textarea name="full" class="form-control" id="full" required>{{ old('full', $book->full ?? '') }}</textarea>
</div>
<div class="mb-3">


    <div class="mb-3">
        <label for="author_id" class="form-label"><i class="bi bi-person-lines-fill"></i> ავტორი</label>
        <select name="author_id" class="chosen-select" id="author_id" required>
            <option value=""></option>
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

    $(document).ready(function () {
        $('#languageSwitcher').on('change', function () {
            updateLanguage(this.value);
        });

        // Run once on load
        updateLanguage($('#languageSwitcher').val());
    });
</script>
