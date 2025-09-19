@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="mb-0">{{ $book->title }}</h2>
  <div class="d-flex gap-2">
    <a class="btn btn-outline-primary" href="{{ route('books.edit', $book) }}"><i class="bi bi-pencil"></i> Edit</a>
    <a class="btn btn-outline-info" href="{{ route('books.index') }}"><i class="bi bi-arrow-left"></i> Back to Books</a>
  </div>
</div>

<div class="row">
  <div class="col-md-8">
    <div class="card p-3">
      <dl class="row">
        <dt class="col-sm-3">ISBN</dt><dd class="col-sm-9">{{ $book->isbn }}</dd>
        <dt class="col-sm-3">Author</dt><dd class="col-sm-9">{{ $book->author }}</dd>
        <dt class="col-sm-3">Publisher</dt><dd class="col-sm-9">{{ $book->publisher ?? 'N/A' }}</dd>
        <dt class="col-sm-3">Published Date</dt><dd class="col-sm-9">{{ $book->published_date?->format('M d, Y') ?? 'N/A' }}</dd>
        <dt class="col-sm-3">Genre</dt><dd class="col-sm-9">{{ $book->genre ?? 'N/A' }}</dd>
        <dt class="col-sm-3">Language</dt><dd class="col-sm-9">{{ $book->language }}</dd>
        <dt class="col-sm-3">Pages</dt><dd class="col-sm-9">{{ $book->pages ?? 'N/A' }}</dd>
        <dt class="col-sm-3">Price</dt><dd class="col-sm-9">${{ number_format($book->price, 2) }}</dd>
        <dt class="col-sm-3">Stock</dt><dd class="col-sm-9">{{ $book->stock }}</dd>
        <dt class="col-sm-3">Status</dt><dd class="col-sm-9">
          @if($book->is_active)
            <span class="badge bg-success">Active</span>
          @else
            <span class="badge bg-secondary">Inactive</span>
          @endif
        </dd>
      </dl>
      @if($book->description)
        <h5>Description</h5>
        <p>{{ $book->description }}</p>
      @endif
    </div>
  </div>
  <div class="col-md-4">
    <div class="card p-3">
      <h5>NetSuite Integration</h5>
      <dl class="row">
        <dt class="col-sm-6">NetSuite Item ID</dt>
        <dd class="col-sm-6">{{ $book->netsuite_item_id ?? 'Not synced' }}</dd>
        <dt class="col-sm-6">Last Synced</dt>
        <dd class="col-sm-6">
          @if($book->synced_at)
            <span class="badge bg-success">{{ $book->synced_at->format('M d, Y H:i') }}</span>
          @else
            <span class="badge bg-warning">Never synced</span>
          @endif
        </dd>
      </dl>
      <div class="mt-3">
        <small class="text-muted">
          <i class="bi bi-info-circle"></i> This book is automatically synced to NetSuite via RESTlet when created, updated, or deleted.
        </small>
      </div>
    </div>
  </div>
</div>
@endsection
