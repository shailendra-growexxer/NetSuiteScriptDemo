<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;

Route::redirect('/', '/products');

Route::resource('products', ProductController::class);
Route::delete('products-bulk', [ProductController::class, 'bulkDestroy'])->name('products.bulk-destroy');
Route::post('products-import', [ProductController::class, 'bulkImport'])->name('products.bulk-import');
