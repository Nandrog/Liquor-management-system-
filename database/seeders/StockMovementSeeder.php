<?php

namespace Database\Seeders;

use App\Models\StockMovement;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class StockMovementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Make sure you have some Products and Warehouses first!
        if (Product::count() == 0) {
            Product::factory()->count(5)->create();
        }

        if (Warehouse::count() < 2) {
            Warehouse::factory()->count(2)->create();
        }

        $product = Product::inRandomOrder()->first();
        $fromWarehouse = Warehouse::inRandomOrder()->first();

        // Ensure $toWarehouse is a different warehouse using 'warehouse_id'
        $toWarehouse = Warehouse::where('warehouse_id', '!=', $fromWarehouse->warehouse_id)
                                ->inRandomOrder()
                                ->first();

        // If only one warehouse exists, $toWarehouse might be null, so guard:
        if (!$toWarehouse) {
            $this->command->warn('Not enough warehouses to create stock movements.');
            return;
        }

        // Create 5 Stock Movements
        for ($i = 1; $i <= 5; $i++) {
            StockMovement::create([
                'product_id' => $product->id,
                'from_warehouse_id' => $fromWarehouse->warehouse_id,
                'to_warehouse_id' => $toWarehouse->warehouse_id,
                'quantity' => rand(10, 100),
                'moved_at' => Carbon::now()->addDays($i),
                'notes' => 'Test stock move #' . $i,
            ]);
        }

        $this->command->info('✅ Stock Movements seeded!');
    }
}
