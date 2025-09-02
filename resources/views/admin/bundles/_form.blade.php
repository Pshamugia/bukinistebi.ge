{{-- resources/views/admin/bundles/_form.blade.php --}}
@php
  $editing = isset($bundle);
@endphp

@if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach ($errors->all() as $e)
        <li>{{ $e }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="mb-3">
  <label class="form-label">Title</label>
  <input type="text" name="title" class="form-control" required
         value="{{ old('title', $editing ? $bundle->title : '') }}">
</div>

<div class="mb-3">
  <label class="form-label">Slug (optional)</label>
  <input type="text" name="slug" class="form-control"
         value="{{ old('slug', $editing ? $bundle->slug : '') }}">
</div>

<div class="mb-3">
  <label class="form-label">Books in this bundle</label>
  <select name="book_ids[]" class="form-control chosen" multiple required
          data-placeholder="Choose books…">
    @foreach($books as $bk)
    <option value="{{ $bk->id }}"
        @selected(in_array($bk->id, old('book_ids', $editing ? $bundle->books->pluck('id')->all() : [])))>
        {{ $bk->title }} — {{ optional($bk->author)->name ?? '—' }}
        ({{ number_format($bk->price) }} GEL, stock: {{ $bk->quantity }})
      </option>
      
    @endforeach
  </select>
  <small class="text-muted">Hold Ctrl/Cmd to select multiple.</small>
</div>

{{-- Optional per-book quantities (default 1). JS shows inputs when selected --}}
<div id="per-book-qty" class="mb-3"></div>

<div class="row g-3">
  <div class="col-md-4">
    <label class="form-label">Bundle Price (GEL)</label>
    <input type="number" name="price" class="form-control" min="0" required
           value="{{ old('price', $editing ? $bundle->price : '') }}">
  </div>
  <div class="col-md-4">
    <label class="form-label">Original Sum</label>
    <input type="text" class="form-control" id="original_sum" value="0" readonly>
  </div>
  <div class="col-md-4">
    <label class="form-label">You save</label>
    <input type="text" class="form-control" id="you_save" value="0" readonly>
  </div>
</div>

<div class="row g-3 mt-1">
  <div class="col-md-4">
    <label class="form-label">Active</label><br>
    <input type="checkbox" name="active" value="1" @checked(old('active', $editing ? $bundle->active : true))>
  </div>
  <div class="col-md-4">
    <label class="form-label">Starts at</label>
    <input type="datetime-local" name="starts_at" class="form-control"
  value="{{ old('starts_at', $editing ? ($bundle->starts_at?->format('Y-m-d\TH:i')) : '') }}">
  </div>
  <div class="col-md-4">
    <label class="form-label">Ends at</label>
    <input type="datetime-local" name="ends_at" class="form-control"
  value="{{ old('ends_at', $editing ? ($bundle->ends_at?->format('Y-m-d\TH:i')) : '') }}">
  </div>
</div>

<div class="mb-3 mt-3">
  <label class="form-label">Description (optional)</label>
  <textarea name="description" class="form-control" rows="3">{{ old('description', $editing ? $bundle->description : '') }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">Image (optional)</label>
  
    @if(!empty($editing) && $bundle->image)
      <div class="mb-2">
        <img src="{{ $bundle->image_url }}" alt="Current image" style="max-height:120px;border-radius:8px">
      </div>
    @endif
  
    <input type="file" name="image" class="form-control" accept="image/*">
    <small class="text-muted">JPG/PNG/WebP up to 2MB.</small>
  </div>
  

@push('scripts')
<script>
  const BOOKS = @json($books->map(fn($b)=>['id'=>$b->id,'price'=>$b->price,'title'=>$b->title]));
  const preQtys = @json(old('book_qtys', $editing ? $bundle->books->mapWithKeys(fn($b)=>[$b->id=>$b->pivot->qty])->all() : []));

  function updateQtyInputs() {
    const selected = Array.from(document.querySelector('select[name="book_ids[]"]').selectedOptions).map(o=>+o.value);
    const container = document.getElementById('per-book-qty');
    container.innerHTML = '';
    selected.forEach(id=>{
      const book = BOOKS.find(b=>b.id===id);
      const qty = preQtys[id] ?? 1;
      const row = document.createElement('div');
      row.className = 'input-group mb-2';
      row.innerHTML = `
        <span class="input-group-text" style="min-width: 260px">${book.title}</span>
        <input type="number" min="1" name="book_qtys[${id}]" class="form-control book-qty" value="${qty}">
      `;
      container.appendChild(row);
    });
    computeOriginal();
  }

  function computeOriginal() {
    const selected = Array.from(document.querySelector('select[name="book_ids[]"]').selectedOptions).map(o=>+o.value);
    let sum = 0;
    selected.forEach(id=>{
      const book = BOOKS.find(b=>b.id===id);
      const qtyInput = document.querySelector(`input[name="book_qtys[${id}]"]`);
      const qty = qtyInput ? Math.max(1, parseInt(qtyInput.value||'1')) : 1;
      sum += (book?.price || 0) * qty;
    });
    const priceInput = document.querySelector('input[name="price"]');
    const bundlePrice = parseInt(priceInput.value || '0');
    document.getElementById('original_sum').value = sum + ' GEL';
    document.getElementById('you_save').value = Math.max(0, sum - bundlePrice) + ' GEL';
  }

  document.addEventListener('DOMContentLoaded', function(){
    const select = document.querySelector('select[name="book_ids[]"]');
    if (window.$ && $(select).chosen) {
      $(select).chosen().change(updateQtyInputs);
    } else {
      select.addEventListener('change', updateQtyInputs);
    }
    document.querySelector('input[name="price"]').addEventListener('input', computeOriginal);
    document.addEventListener('input', (e)=>{ if (e.target.classList.contains('book-qty')) computeOriginal(); });

    updateQtyInputs(); // initial
  });
</script>
@endpush
