<?php

namespace App\Services;

use App\Models\Product;

class NetSuiteService
{
    public function __construct()
    {
        // Here you could initialize the NetSuite client (ryanwinchester/netsuite-php)
        // with token-based auth using config('netsuite.*') values.
    }

    public function upsertItem(Product $product): void
    {
        // Integrate with NetSuite Item record via SuiteTalk SOAP
        // Stub: mark synced
        $product->forceFill(['synced_at' => now()])->saveQuietly();
    }

    public function deleteItem(Product $product): void
    {
        // Stub for delete call
        $product->forceFill(['synced_at' => now()])->saveQuietly();
    }
}


