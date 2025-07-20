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
        $warehouses = Warehouse::pluck('warehouse_id');

        foreach ($products as $productId) {
            $numMovements = rand(3, 6);

            for ($i = 0; $i < $numMovements; $i++) {
                $randomDate = Carbon::now()
                    ->subDays(rand(0, 30))
                    ->setTime(rand(6, 18), rand(0, 59));

                // Create instance, disable timestamps, set fields manually
                $movement = new StockMovement();
                $movement->product_id = $productId;
                $movement->warehouse_id = $warehouses->random();
                $movement->quantity = rand(10, 100);
                $movement->movement_type = collect(['in', 'out', 'transfer'])->random();

                // Set varying date values
                $movement->created_at = $randomDate;
                $movement->updated_at = $randomDate;

                // ❗ Prevent Laravel from overriding timestamps
                $movement->timestamps = false;

                $movement->save();
            }
        }
    }
}
