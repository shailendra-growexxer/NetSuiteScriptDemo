<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\DB;
use App\Services\NetSuiteService;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::query()
            ->latest()
            ->paginate(20);
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        $product = null;
        DB::transaction(function () use ($data, &$product) {
            $product = Product::create($data);
        });
        app(NetSuiteService::class)->upsertItem($product);
        return redirect()->route('products.index')->with('success', 'Product created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();
        DB::transaction(function () use ($data, $product) {
            $product->update($data);
        });
        app(NetSuiteService::class)->upsertItem($product);
        return redirect()->route('products.index')->with('success', 'Product updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        app(NetSuiteService::class)->deleteItem($product);
        DB::transaction(function () use ($product) {
            $product->delete();
        });
        return redirect()->route('products.index')->with('success', 'Product deleted.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            return back()->with('error', 'No products selected.');
        }
        DB::transaction(function () use ($ids) {
            $products = Product::whereIn('id', $ids)->get();
            foreach ($products as $product) {
                app(NetSuiteService::class)->deleteItem($product);
                $product->delete();
            }
        });
        return back()->with('success', 'Selected products deleted.');
    }

    public function bulkImport(Request $request)
    {
        $request->validate([
            'csv' => 'required|file|mimes:csv,txt',
        ]);
        $path = $request->file('csv')->getRealPath();
        $rows = array_map('str_getcsv', file($path));
        $header = array_map('trim', array_shift($rows));
        DB::transaction(function () use ($rows, $header) {
            foreach ($rows as $row) {
                $data = array_combine($header, $row);
                if (!$data) {
                    continue;
                }
                $payload = [
                    'sku' => $data['sku'] ?? null,
                    'barcode' => $data['barcode'] ?? null,
                    'name' => $data['name'] ?? null,
                    'description' => $data['description'] ?? null,
                    'price' => (float)($data['price'] ?? 0),
                    'cost' => (float)($data['cost'] ?? 0),
                    'stock' => (int)($data['stock'] ?? 0),
                    'uom' => $data['uom'] ?? null,
                    'category' => $data['category'] ?? null,
                    'brand' => $data['brand'] ?? null,
                    'is_active' => isset($data['is_active']) ? (bool)$data['is_active'] : true,
                ];
                $product = Product::updateOrCreate(['sku' => $payload['sku']], $payload);
                app(NetSuiteService::class)->upsertItem($product);
            }
        });
        return back()->with('success', 'Bulk import completed.');
    }
}
