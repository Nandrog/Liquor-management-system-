<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockMovement;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Support\Carbon;

class StockMovementSeeder extends Seeder
{
    public function run()
    {
        $products = Product::pluck('id');
        $warehouses = Warehouse::pluck('warehouse_id'); // ✅ updated to match your schema

        foreach ($products as $productId) {
            $numMovements = rand(3, 7); // varying number of movements per product

            for ($i = 0; $i < $numMovements; $i++) {
                StockMovement::create([
                    'product_id' => $productId,
                    'warehouse_id' => $warehouses->random(), // ✅ updated
                    'quantity' => rand(1, 100),
                    'movement_type' => collect(['in', 'out', 'transfer'])->random(),
                    'created_at' => Carbon::now()->subDays(rand(0, 30)),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
