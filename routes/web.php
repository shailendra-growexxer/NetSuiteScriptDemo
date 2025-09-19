<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\BookController;

Route::redirect('/', '/products');

Route::resource('products', ProductController::class);
Route::delete('products-bulk', [ProductController::class, 'bulkDestroy'])->name('products.bulk-destroy');
Route::post('products-import', [ProductController::class, 'bulkImport'])->name('products.bulk-import');

Route::resource('books', BookController::class);
Route::delete('books-bulk', [BookController::class, 'bulkDestroy'])->name('books.bulk-destroy');
Route::post('books-import', [BookController::class, 'bulkImport'])->name('books.bulk-import');
Route::post('books-fetch-netsuite', [BookController::class, 'fetchFromNetSuite'])->name('books.fetch-netsuite');
