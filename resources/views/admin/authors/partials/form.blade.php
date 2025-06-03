<!-- Georgian Name -->
<div class="mb-3">
    <label>სახელი (ქართული)</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $author->name ?? '') }}" required>
    @error('name')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<!-- English Name -->
<div class="mb-3">
    <label>Name (English)</label>
    <input type="text" name="name_en" class="form-control" value="{{ old('name_en', $author->name_en ?? '') }}">
    @error('name_en')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>