<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;

    protected $fillable = [
        'isbn',
        'title',
        'author',
        'description',
        'price',
        'pages',
        'publisher',
        'published_date',
        'genre',
        'language',
        'stock',
        'is_active',
        'netsuite_item_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'published_date' => 'date',
        'synced_at' => 'datetime',
    ];
}
