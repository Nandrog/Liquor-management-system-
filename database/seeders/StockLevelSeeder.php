<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\StockLevel;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class StockLevelSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $warehouses = Warehouse::all();

        foreach ($warehouses as $warehouse) {
            foreach ($products as $product) {
                StockLevel::updateOrCreate(
                    [
                        'warehouse_id' => $warehouse->warehouse_id,  // <-- fixed here
                        'product_id' => $product->id,
                    ],
                    [
                        'quantity' => rand(500, 1000),
                    ]
                );
            }
        }
    }
}
