<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'NetSuiteScriptDemo') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; color: #0f172a; }
        .navbar { background: #ffffff; border-bottom: 1px solid #e5e7eb !important; }
        .card { background: #ffffff; border: 1px solid #e5e7eb; }
        a, .btn-link { color: #0d6efd; }
        .form-control, .form-select { background: #ffffff; color: #0f172a; border-color: #ced4da; }
        .table thead { color: #334155; }
    </style>
    @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light border-bottom">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ route('products.index') }}">NetSuiteScriptDemo</a>
    <div class="navbar-nav">
      <a class="nav-link" href="{{ route('products.index') }}">Products</a>
      <a class="nav-link" href="{{ route('books.index') }}">Demo RESTlet - Books</a>
    </div>
  </div>
  </nav>
  <main class="container py-4">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @yield('content')
  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>


