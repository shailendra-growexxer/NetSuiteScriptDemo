@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="mb-0">{{ $product->name }}</h2>
  <a class="btn btn-outline-light" href="{{ route('products.edit', $product) }}"><i class="bi bi-pencil"></i> Edit</a>
  </div>
<div class="card p-3">
  <dl class="row">
    <dt class="col-sm-2">SKU</dt><dd class="col-sm-10">{{ $product->sku }}</dd>
    <dt class="col-sm-2">Price</dt><dd class="col-sm-10">${{ number_format($product->price,2) }}</dd>
    <dt class="col-sm-2">Stock</dt><dd class="col-sm-10">{{ $product->stock }}</dd>
    <dt class="col-sm-2">NetSuite Item</dt><dd class="col-sm-10">{{ $product->netsuite_item_id ?? '-' }}</dd>
  </dl>
  <p>{{ $product->description }}</p>
</div>
@endsection


