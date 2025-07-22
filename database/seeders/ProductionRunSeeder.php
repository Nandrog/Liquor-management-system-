<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductionRunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all finished goods products
        $finishedGoods = \App\Models\Product::where('type', 'finished_good')->get();
        // Get all manufacturers
        $manufacturers = \App\Models\User::role('Manufacturer')->get();

        if ($finishedGoods->isEmpty() || $manufacturers->isEmpty()) {
            $this->command->warn('No finished goods or manufacturers found. Seed those first.');
            return;
        }

        // Get all factories (assuming Factory model exists)
        $factories = \App\Models\Factory::all();
        if ($factories->isEmpty()) {
            $this->command->warn('No factories found. Seed factories first.');
            return;
        }

        foreach ($finishedGoods as $product) {
            // Assign a random manufacturer and factory for each run
            $manufacturer = $manufacturers->random();
            $factory = $factories->random();
            $costOfMaterials = rand(10000, 500000); // Example cost, adjust as needed
            \App\Models\ProductionRun::create([
                'user_id' => $manufacturer->id,
                'factory_id' => $factory->id,
                'product_id' => $product->id,
                'quantity_produced' => 20,
                'cost_of_materials' => $costOfMaterials,
                'completed_at' => now()->subDays(rand(1, 180)),
                'manufacturer_id' => $manufacturer->id,
            ]);
        }
    }
}
