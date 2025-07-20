<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockMovement;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WorkDistribution\Employee;
use Illuminate\Support\Carbon;

class StockMovementSeeder extends Seeder
{
    public function run()
    {
        $products = Product::pluck('id');
        $warehouses = Warehouse::pluck('warehouse_id');
        $employees = Employee::pluck('id'); // optional if employee_id is needed

        foreach ($products as $productId) {
            $numMovements = rand(3, 6);

            for ($i = 0; $i < $numMovements; $i++) {
                $randomDate = Carbon::now()
                    ->subDays(rand(0, 30))
                    ->setTime(rand(6, 18), rand(0, 59));

                // Pick two different warehouse IDs
                $fromWarehouse = $warehouses->random();
                do {
                    $toWarehouse = $warehouses->random();
                } while ($toWarehouse === $fromWarehouse);

                StockMovement::create([
                    'product_id' => $productId,
                    'from_warehouse_id' => $fromWarehouse,
                    'to_warehouse_id' => $toWarehouse,
                    'employee_id' => $employees->random(),
                    'quantity' => rand(10, 100),
                    'moved_at' => $randomDate,
                    'notes' => 'Auto-generated stock movement',
                    'created_at' => $randomDate,
                    'updated_at' => $randomDate,
                ]);
            }
        }
    }
}
