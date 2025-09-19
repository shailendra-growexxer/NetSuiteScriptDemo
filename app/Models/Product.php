<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'sku',
        'barcode',
        'name',
        'description',
        'price',
        'cost',
        'stock',
        'uom',
        'category',
        'brand',
        'is_active',
        'attributes',
        'netsuite_item_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'attributes' => 'array',
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'synced_at' => 'datetime',
    ];
}
