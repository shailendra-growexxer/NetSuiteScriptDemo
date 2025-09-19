@extends('layouts.app')

@section('content')
<h2 class="mb-3">New Book - Demo RESTlet</h2>
<div class="card p-3">
  <form method="POST" action="{{ route('books.store') }}">
    @csrf
    @include('books.partials.form')
    <div class="mt-3 d-flex gap-2">
      <button class="btn btn-primary" type="submit"><i class="bi bi-save"></i> Save & Sync to NetSuite</button>
      <a class="btn btn-secondary" href="{{ route('books.index') }}">Cancel</a>
    </div>
  </form>
</div>
@endsection
