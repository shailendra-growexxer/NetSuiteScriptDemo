<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class BulkProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory()->count(10000)->create();
    }
}
