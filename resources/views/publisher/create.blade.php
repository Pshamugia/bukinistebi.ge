@extends('layouts.app')

@section('content')

<div class="container">
    <h5 class="section-title" style="position: relative; margin-bottom:25px; padding-bottom:25px; align-items: left; justify-content: left;">
        <strong><i class="bi bi-stack-overflow"></i> ატვირთე წიგნი</strong>
    </h5>

    <!-- Display messages -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
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

    <!-- Book Upload Form -->
    <form action="{{ route('publisher.books.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">წიგნის სახელწოდება</label>
            <input type="text" name="title" class="form-control" id="title" value="{{ old('title') }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">წიგნის მოკლე აღწერა</label>
            <textarea name="description" class="form-control" id="description" required>{{ old('description') }}</textarea>
        </div>

        <div style="border:1px solid #c0c0c0; border-radius:5px; padding:25px; margin:20px 0px 20px 0px">
            <div class="mb-3">
                <label for="photo" class="form-label">ყდის ფოტო</label>
                <input type="file" name="photo" class="form-control" id="photo">
            </div>
            <div class="mb-3">
                <label for="photo_2" class="form-label">ფოტო 2</label>
                <input type="file" name="photo_2" class="form-control" id="photo_2">
            </div>
            <div class="mb-3">
                <label for="photo_3" class="form-label">ფოტო 3</label>
                <input type="file" name="photo_3" class="form-control" id="photo_3">
            </div>
            <div class="mb-3">
                <label for="photo_4" class="form-label">ფოტო 4</label>
                <input type="file" name="photo_4" class="form-control" id="photo_4">
            </div>
        </div>

        <div class="mb-3">
            <label for="cover" class="form-label">გარეკანი (რბილი/მაგარი)</label>
            <select name="cover" class="form-control" id="cover" required>
                <option value="" disabled selected>აირჩიე გარეკანი</option>
                <option value="რბილი" {{ old('cover') == 'რბილი' ? 'selected' : '' }}>რბილი</option>
                <option value="მაგარი" {{ old('cover') == 'მაგარი' ? 'selected' : '' }}>მაგარი</option>
            </select>
        </div>

        <!-- Additional Fields -->
        <div class="mb-3">
            <label for="price" class="form-label">ფასი</label>
            <input type="number" name="price" class="form-control" id="price" value="{{ old('price') }}" required>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">რაოდენობა</label>
            <input type="number" name="quantity" class="form-control" id="quantity" value="{{ old('quantity') }}" required>
        </div>
        <div class="mb-3">
            <label for="publishing_date" class="form-label">გამოცემის წელი</label>
            <input type="text" name="publishing_date" class="form-control" id="publishing_date" value="{{ old('publishing_date') }}">
        </div>
        <div class="mb-3">
            <label for="pages" class="form-label"> გვერდების რაოდენობა </label>
            <input type="number" name="pages" class="form-control" id="pages" value="{{ old('pages') }}">
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">სტატუსი (შელახული, ახალი, ყდა დაზიანებული და ა.შ.) </label>
            <input type="text" name="status" class="form-control" id="status" value="{{ old('status') }}">
        </div>

        <!-- Author Selection with Popup Form -->
<!-- Author Dropdown -->
<div class="mb-3">
    <label for="author_id" class="form-label">ავტორი</label>
    <select name="author_id" class="form-control chosen-select" id="author_id" data-placeholder="მონიშნე ავტორი" required>
        <option value="">მონიშნე ავტორი</option>
        @foreach ($authors as $author)
            <option value="{{ $author->id }}">{{ $author->name }}</option>
        @endforeach
    </select>
    <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#addAuthorModal">დაამატე ახალი ავტორი</button>
</div>

        <div class="mb-3">
            <label for="category_id" class="form-label">კატეგორია</label>
           
            <select name="genre_id[]" class="form-select categoria" id="genre_id" multiple>
                <option value="" style="color: #ccc"><span>კატეგორია</span></option>
                @foreach ($genres as $genre)
                    <option value="{{ $genre->id }}" {{ request('genre_id') == $genre->id ? 'selected' : '' }}>
                        {{ $genre->name }}
                    </option>
                @endforeach
            </select>

            <script>
                 $('.form-select').chosen({
            no_results_text: "Oops, nothing found!"
        });</script>
        </div>

        <button type="submit" class="btn btn-primary">ატვირთვა</button>
    </form>
</div>

<!-- Add Author Modal -->
<div class="modal fade" id="addAuthorModal" tabindex="-1" aria-labelledby="addAuthorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAuthorModalLabel">დაამატე ახალი ავტორი</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addAuthorForm">
                    <div class="mb-3">
                        <label for="new_author_name" class="form-label">ავტორის სახელი და გვარი</label>
                        <input type="text" id="new_author_name" name="new_author_name" class="form-control" required>
                        <div id="authorError" class="text-danger"></div>
                    </div>
                    <button type="submit" class="btn btn-primary">დამატება</button>
                </form>
            </div>
        </div>
    </div>
</div>



@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
<script>
  $(document).ready(function () {
    console.log('Initializing Chosen...');
    $('.chosen-select').chosen({
        width: '100%',
        placeholder_text_single: "მონიშნე ავტორი"
    });
});


$('#addAuthorForm').on('submit', function (e) {
    e.preventDefault(); // Prevent form submission

    let newAuthorName = $('#new_author_name').val(); // Get the input value

    $.ajax({
        url: "{{ route('publisher.authors.store') }}", // Use Laravel's named route
        type: 'POST',
        data: {
            new_author_name: newAuthorName, // Send the correct field
            _token: $('meta[name="csrf-token"]').attr('content') // Include CSRF token
        },
        success: function (response) {
            if (response.success) {
                // Add the new author to the dropdown
                let newOption = $('<option>')
                    .val(response.author.id)
                    .text(response.author.name);

                $('#author_id').append(newOption).trigger("chosen:updated"); // Update the Chosen dropdown

                // Clear the form and close the modal
                $('#new_author_name').val('');
                $('#addAuthorModal').modal('hide');
                alert('ავტორი წარმატებით დაემატა!');
            } else {
                // Show server-side validation errors
                $('#authorError').text(response.errors.new_author_name[0]);
            }
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                // Validation error
                let errors = xhr.responseJSON.errors;
                $('#authorError').text(errors.new_author_name[0]);
            } else {
                alert('დაფიქსირდა შეცდომა: ' + xhr.responseJSON.message);
            }
        }
    });
});


</script>




@endsection
