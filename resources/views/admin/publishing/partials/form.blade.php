<div class="row">
    <div class="col-lg-8">
        <div class="mb-3">
            <label for="title" class="form-label">სათაური</label>
            <input type="text" name="title" id="title" value="{{ old('title', $item->title) }}" class="form-control @error('title') is-invalid @enderror" required>
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">კატეგორია</label>
            <input type="text" name="category" id="category" value="{{ old('category', $item->category) }}" class="form-control @error('category') is-invalid @enderror">
            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="shop_url" class="form-label">მაღაზიის ბმული</label>
            <input type="url" name="shop_url" id="shop_url" value="{{ old('shop_url', $item->shop_url) }}" class="form-control @error('shop_url') is-invalid @enderror" placeholder="https://bukinistebi.ge/...">
            @error('shop_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">აღწერა</label>
            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="8">{{ old('description', $item->description) }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-lg-4">
        @foreach(['image_1' => 'მთავარი სურათი', 'image_2' => 'სურათი 2', 'image_3' => 'სურათი 3', 'image_4' => 'სურათი 4'] as $field => $label)
            <div class="mb-3">
                <label for="{{ $field }}" class="form-label">{{ $label }}</label>
                <input type="file" name="{{ $field }}" id="{{ $field }}" class="form-control @error($field) is-invalid @enderror" accept="image/*">
                @error($field) <div class="invalid-feedback">{{ $message }}</div> @enderror

                @if($item->{$field})
                    <img src="{{ asset('storage/'.$item->{$field}) }}" alt="{{ $label }}" class="img-thumbnail mt-2" style="max-height: 120px; object-fit: cover;">
                @endif
            </div>
        @endforeach
    </div>
</div>

<div class="d-flex gap-2 mt-3">
    <button type="submit" class="btn btn-success">{{ $buttonText }}</button>
    <a href="{{ route('admin.publishing.index') }}" class="btn btn-secondary">უკან</a>
</div>
