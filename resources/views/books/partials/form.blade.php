<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">ISBN</label>
    <input class="form-control" name="isbn" value="{{ old('isbn', $book->isbn ?? '') }}" required>
  </div>
  <div class="col-md-6">
    <label class="form-label">Title</label>
    <input class="form-control" name="title" value="{{ old('title', $book->title ?? '') }}" required>
  </div>
  <div class="col-md-6">
    <label class="form-label">Author</label>
    <input class="form-control" name="author" value="{{ old('author', $book->author ?? '') }}" required>
  </div>
  <div class="col-md-6">
    <label class="form-label">Publisher</label>
    <input class="form-control" name="publisher" value="{{ old('publisher', $book->publisher ?? '') }}">
  </div>
  <div class="col-md-12">
    <label class="form-label">Description</label>
    <textarea class="form-control" rows="3" name="description">{{ old('description', $book->description ?? '') }}</textarea>
  </div>
  <div class="col-md-3">
    <label class="form-label">Price</label>
    <input class="form-control" type="number" step="0.01" name="price" value="{{ old('price', $book->price ?? 0) }}" required>
  </div>
  <div class="col-md-3">
    <label class="form-label">Pages</label>
    <input class="form-control" type="number" name="pages" value="{{ old('pages', $book->pages ?? '') }}">
  </div>
  <div class="col-md-3">
    <label class="form-label">Stock</label>
    <input class="form-control" type="number" name="stock" value="{{ old('stock', $book->stock ?? 0) }}" required>
  </div>
  <div class="col-md-3">
    <label class="form-label">Published Date</label>
    <input class="form-control" type="date" name="published_date" value="{{ old('published_date', $book->published_date?->format('Y-m-d') ?? '') }}">
  </div>
  <div class="col-md-4">
    <label class="form-label">Genre</label>
    <select class="form-select" name="genre">
      <option value="">Select Genre</option>
      <option value="Fiction" {{ old('genre', $book->genre ?? '') == 'Fiction' ? 'selected' : '' }}>Fiction</option>
      <option value="Non-Fiction" {{ old('genre', $book->genre ?? '') == 'Non-Fiction' ? 'selected' : '' }}>Non-Fiction</option>
      <option value="Science Fiction" {{ old('genre', $book->genre ?? '') == 'Science Fiction' ? 'selected' : '' }}>Science Fiction</option>
      <option value="Mystery" {{ old('genre', $book->genre ?? '') == 'Mystery' ? 'selected' : '' }}>Mystery</option>
      <option value="Romance" {{ old('genre', $book->genre ?? '') == 'Romance' ? 'selected' : '' }}>Romance</option>
      <option value="Biography" {{ old('genre', $book->genre ?? '') == 'Biography' ? 'selected' : '' }}>Biography</option>
      <option value="History" {{ old('genre', $book->genre ?? '') == 'History' ? 'selected' : '' }}>History</option>
      <option value="Self-Help" {{ old('genre', $book->genre ?? '') == 'Self-Help' ? 'selected' : '' }}>Self-Help</option>
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label">Language</label>
    <select class="form-select" name="language">
      <option value="English" {{ old('language', $book->language ?? 'English') == 'English' ? 'selected' : '' }}>English</option>
      <option value="Spanish" {{ old('language', $book->language ?? '') == 'Spanish' ? 'selected' : '' }}>Spanish</option>
      <option value="French" {{ old('language', $book->language ?? '') == 'French' ? 'selected' : '' }}>French</option>
      <option value="German" {{ old('language', $book->language ?? '') == 'German' ? 'selected' : '' }}>German</option>
      <option value="Italian" {{ old('language', $book->language ?? '') == 'Italian' ? 'selected' : '' }}>Italian</option>
    </select>
  </div>
  <div class="col-md-4 form-check mt-4">
    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', ($book->is_active ?? true)) ? 'checked' : '' }}>
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
