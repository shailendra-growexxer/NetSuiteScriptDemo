<div class="row g-3">
  <div class="col-md-3">
    <label class="form-label">SKU</label>
    <input class="form-control" name="sku" value="{{ old('sku', $product->sku ?? '') }}" required>
  </div>
  <div class="col-md-3">
    <label class="form-label">Barcode</label>
    <input class="form-control" name="barcode" value="{{ old('barcode', $product->barcode ?? '') }}">
  </div>
  <div class="col-md-6">
    <label class="form-label">Name</label>
    <input class="form-control" name="name" value="{{ old('name', $product->name ?? '') }}" required>
  </div>
  <div class="col-md-12">
    <label class="form-label">Description</label>
    <textarea class="form-control" rows="3" name="description">{{ old('description', $product->description ?? '') }}</textarea>
  </div>
  <div class="col-md-3">
    <label class="form-label">Price</label>
    <input class="form-control" type="number" step="0.01" name="price" value="{{ old('price', $product->price ?? 0) }}" required>
  </div>
  <div class="col-md-3">
    <label class="form-label">Cost</label>
    <input class="form-control" type="number" step="0.01" name="cost" value="{{ old('cost', $product->cost ?? 0) }}">
  </div>
  <div class="col-md-3">
    <label class="form-label">Stock</label>
    <input class="form-control" type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" required>
  </div>
  <div class="col-md-3">
    <label class="form-label">UOM</label>
    <input class="form-control" name="uom" value="{{ old('uom', $product->uom ?? '') }}">
  </div>
  <div class="col-md-4">
    <label class="form-label">Category</label>
    <input class="form-control" name="category" value="{{ old('category', $product->category ?? '') }}">
  </div>
  <div class="col-md-4">
    <label class="form-label">Brand</label>
    <input class="form-control" name="brand" value="{{ old('brand', $product->brand ?? '') }}">
  </div>
  <div class="col-md-4 form-check mt-4">
    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', ($product->is_active ?? true)) ? 'checked' : '' }}>
    <label class="form-check-label">Active</label>
  </div>
</div>
@if($errors->any())
  <div class="alert alert-danger mt-3">
    <ul class="mb-0">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif


