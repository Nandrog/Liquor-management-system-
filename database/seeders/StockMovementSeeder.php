<?php

namespace Database\Seeders;

use App\Models\StockMovement;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WorkDistribution\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class StockMovementSeeder extends Seeder
{
    public function run(): void
    {
        if (Product::count() == 0) {
            Product::factory()->count(5)->create();
        }

        if (Warehouse::count() < 2) {
            Warehouse::factory()->count(2)->create();
        }

        if (Employee::count() == 0) {
            $this->command->warn('No employees found. Please seed employees first.');
            return;
        }

        $product = Product::inRandomOrder()->first();
        $fromWarehouse = Warehouse::inRandomOrder()->first();

        $toWarehouse = Warehouse::where('warehouse_id', '!=', $fromWarehouse->warehouse_id)
                                ->inRandomOrder()
                                ->first();

        if (!$toWarehouse) {
            $this->command->warn('Not enough warehouses to create stock movements.');
            return;
        }

        $employee = Employee::inRandomOrder()->first();

        for ($i = 1; $i <= 5; $i++) {
            StockMovement::create([
                'product_id' => $product->id,
                'from_warehouse_id' => $fromWarehouse->warehouse_id,
                'to_warehouse_id' => $toWarehouse->warehouse_id,
                'quantity' => rand(10, 100),
                'moved_at' => Carbon::now()->addDays($i),
                'employee_id' => $employee->employee_id,
                'notes' => 'Test stock move #' . $i,
            ]);
        }

        $this->command->info('✅ Stock Movements seeded!');
    }
}
