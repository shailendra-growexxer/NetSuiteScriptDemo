@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="mb-0">Products</h2>
  <div class="d-flex gap-2">
    <a href="{{ route('products.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> New Product</a>
    <button class="btn btn-danger" form="bulk-delete" type="submit"><i class="bi bi-trash"></i> Delete Selected</button>
  </div>
</div>

<form id="import-form" class="mb-3" method="POST" action="{{ route('products.bulk-import') }}" enctype="multipart/form-data">
  @csrf
  <div class="input-group">
    <input type="file" name="csv" class="form-control" accept=".csv">
    <button class="btn btn-outline-light" type="submit"><i class="bi bi-file-earmark-arrow-up"></i> Import CSV</button>
  </div>
</form>

<form id="bulk-delete" method="POST" action="{{ route('products.bulk-destroy') }}">
  @csrf
  @method('DELETE')
  <div class="card">
    <div class="table-responsive">
      <table class="table table-striped align-middle mb-0">
        <thead>
          <tr>
            <th><input type="checkbox" id="select-all"></th>
            <th>SKU</th>
            <th>Name</th>
            <th>Price</th>
            <th>Stock</th>
            <th>NS Item ID</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($products as $product)
            <tr>
              <td><input type="checkbox" name="ids[]" value="{{ $product->id }}"></td>
              <td>{{ $product->sku }}</td>
              <td><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></td>
              <td>${{ number_format($product->price, 2) }}</td>
              <td>{{ $product->stock }}</td>
              <td>{{ $product->netsuite_item_id ?? '-' }}</td>
              <td class="text-nowrap">
                <a class="btn btn-sm btn-outline-primary" href="{{ route('products.edit', $product) }}"><i class="bi bi-pencil"></i></a>
                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this product?')"><i class="bi bi-trash"></i></button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="card-footer">{{ $products->links() }}</div>
  </div>
</form>

@push('scripts')
<script>
  document.getElementById('select-all').addEventListener('change', function(e){
    document.querySelectorAll('input[name="ids[]"]').forEach(cb=>cb.checked = e.target.checked);
  });
</script>
@endpush
@endsection


