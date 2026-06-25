@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="mb-3">
        <label class="form-label">Title</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $item->title ?? '') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Category</label>
        <input type="text" name="category" class="form-control" value="{{ old('category', $item->category ?? '') }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Shop URL</label>
        <input type="url" name="shop_url" class="form-control" value="{{ old('shop_url', $item->shop_url ?? '') }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" rows="8" class="form-control" required>{{ old('description', $item->description ?? '') }}</textarea>
    </div>

    <div class="row">
        @foreach(['image_1', 'image_2', 'image_3', 'image_4'] as $image)
            <div class="col-md-6 mb-3">
                <label class="form-label">{{ strtoupper(str_replace('_', ' ', $image)) }}</label>
                <input type="file" name="{{ $image }}" class="form-control" accept="image/*">

                @if(!empty($item?->$image))
                    <img src="{{ asset('storage/' . $item->$image) }}" class="img-thumbnail mt-2" style="height: 100px; object-fit: cover;" alt="{{ $image }}">
                @endif
            </div>
        @endforeach
    </div>

    <div class="d-flex gap-2">
        <button class="btn btn-success">Save</button>
        <a href="{{ route('admin.publishing.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>
