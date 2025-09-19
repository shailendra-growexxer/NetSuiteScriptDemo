@extends('layouts.app')

@section('content')
<h2 class="mb-3">New Product</h2>
<div class="card p-3">
  <form method="POST" action="{{ route('products.store') }}">
    @csrf
    @include('products.partials.form')
    <div class="mt-3 d-flex gap-2">
      <button class="btn btn-primary" type="submit"><i class="bi bi-save"></i> Save</button>
      <a class="btn btn-secondary" href="{{ route('products.index') }}">Cancel</a>
    </div>
  </form>
</div>
@endsection


