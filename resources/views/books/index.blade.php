@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="mb-0">Demo RESTlet - Books Management</h2>
  <div class="d-flex gap-2">
    <a href="{{ route('books.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> New Book</a>
    <button class="btn btn-danger" form="bulk-delete" type="submit"><i class="bi bi-trash"></i> Delete Selected</button>
    <button class="btn btn-info" form="fetch-netsuite" type="submit"><i class="bi bi-cloud-download"></i> Fetch from NetSuite</button>
  </div>
</div>

<div class="row mb-3">
  <div class="col-md-6">
    <form id="import-form" method="POST" action="{{ route('books.bulk-import') }}" enctype="multipart/form-data">
      @csrf
      <div class="input-group">
        <input type="file" name="csv" class="form-control" accept=".csv">
        <button class="btn btn-outline-primary" type="submit"><i class="bi bi-file-earmark-arrow-up"></i> Import CSV</button>
      </div>
    </form>
  </div>
  <div class="col-md-6">
    <form id="fetch-netsuite" method="POST" action="{{ route('books.fetch-netsuite') }}">
      @csrf
      <div class="input-group">
        <select name="genre" class="form-select">
          <option value="">All Genres</option>
          <option value="Fiction">Fiction</option>
          <option value="Non-Fiction">Non-Fiction</option>
          <option value="Science Fiction">Science Fiction</option>
          <option value="Mystery">Mystery</option>
          <option value="Romance">Romance</option>
          <option value="Biography">Biography</option>
          <option value="History">History</option>
          <option value="Self-Help">Self-Help</option>
        </select>
        <input type="text" name="author" class="form-control" placeholder="Author filter">
        <button class="btn btn-outline-info" type="submit"><i class="bi bi-search"></i> Filter</button>
      </div>
    </form>
  </div>
</div>

<form id="bulk-delete" method="POST" action="{{ route('books.bulk-destroy') }}">
  @csrf
  @method('DELETE')
  <div class="card">
    <div class="table-responsive">
      <table class="table table-striped align-middle mb-0">
        <thead>
          <tr>
            <th><input type="checkbox" id="select-all"></th>
            <th>ISBN</th>
            <th>Title</th>
            <th>Author</th>
            <th>Price</th>
            <th>Genre</th>
            <th>Stock</th>
            <th>NS Item ID</th>
            <th>Synced</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($books as $book)
            <tr>
              <td><input type="checkbox" name="ids[]" value="{{ $book->id }}"></td>
              <td>{{ $book->isbn }}</td>
              <td><a href="{{ route('books.show', $book) }}">{{ $book->title }}</a></td>
              <td>{{ $book->author }}</td>
              <td>${{ number_format($book->price, 2) }}</td>
              <td>{{ $book->genre }}</td>
              <td>{{ $book->stock }}</td>
              <td>{{ $book->netsuite_item_id ?? '-' }}</td>
              <td>
                @if($book->synced_at)
                  <span class="badge bg-success">{{ $book->synced_at->format('M d, Y') }}</span>
                @else
                  <span class="badge bg-warning">Not Synced</span>
                @endif
              </td>
              <td class="text-nowrap">
                <a class="btn btn-sm btn-outline-primary" href="{{ route('books.edit', $book) }}"><i class="bi bi-pencil"></i></a>
                <form action="{{ route('books.destroy', $book) }}" method="POST" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this book?')"><i class="bi bi-trash"></i></button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="card-footer">{{ $books->links() }}</div>
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
