<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Get all the necessary dependencies that were created by previous seeders.
        $supplier1 = User::where('email', 'supplier1@example.com')->first();
        $supplier2 = User::where('email', 'supplier2@example.com')->first();
        $vendor = Vendor::first();
        if (!$vendor) {
            $this->command->error('No vendor found. Please seed vendors first.');
            return;
        }

        $category = Category::first();
        if (!$category) {
            $this->command->error('No category found. Please seed categories first.');
            return;
        }

        // --- Seed Finished Goods ---
        // These are sold by a vendor and belong to a finished goods category.
        Product::factory()->create([
            'name' => 'Uganda Waragi 750ml',
            'sku' => 'FG-UWG-750',
            'type' => 'finished_good',
            'unit_price' => 25000,
            'unit_of_measure' => 'bottle',
            'category_id' => $category->id,
            'vendor_id' => $vendor->id,
        ]);

        Product::factory()->create([
            'name' => 'Bell Lager 500ml',
            'sku' => 'FG-BELL-500',
            'type' => 'finished_good',
            'unit_price' => 3500,
            'unit_of_measure' => 'bottle',
            'category_id' => $category->id,
            'vendor_id' => $vendor->id,
            //'supplier_id' => null,
        ]);

        // --- Seed Raw Materials ---
        // This array defines all your raw materials and which supplier provides them.
        $rawMaterials = [
            //Ingredients from Supplier 1 (Tindyebwa Farms)
            ['sku' => 'RM-MOL-01', 'name' => 'Molasses', 'price' => 2000, 'uom' => 'kg', 'supplier_id' => $supplier1->id],
            ['sku' => 'RM-BAN-01', 'name' => 'Bananas', 'price' => 1000, 'uom' => 'kg', 'supplier_id' => $supplier1->id],
            ['sku' => 'RM-CIT-P-01', 'name' => 'Citrus Peels', 'price' => 800, 'uom' => 'kg', 'supplier_id' => $supplier1->id],
            ['sku' => 'RM-JUN-B-01', 'name' => 'Juniper Berries', 'price' => 15000, 'uom' => 'kg', 'supplier_id' => $supplier1->id],

            // Ingredients from Supplier 2 (Nankya Packaging)
            //['sku' => 'RM-YST-01', 'name' => 'Yeast', 'price' => 5000, 'uom' => 'kg', 'supplier_id' => $supplier2->id],
            ['sku' => 'RM-CHR-01', 'name' => 'Charcoal', 'price' => 2500, 'uom' => 'kg', 'supplier_id' => $supplier2->id],
            ['sku' => 'RM-CIT-A-01', 'name' => 'Citric Acid', 'price' => 6000, 'uom' => 'kg', 'supplier_id' => $supplier2->id],
            ['sku' => 'RM-BOT-750', 'name' => 'Glass Bottles 750ml', 'price' => 500, 'uom' => 'unit', 'supplier_id' => $supplier2->id],
            ['sku' => 'RM-CAP-01', 'name' => 'Bottle Caps', 'price' => 50, 'uom' => 'unit', 'supplier_id' => $supplier2->id],
            ['sku' => 'RM-LBL-01', 'name' => 'Paper Labels', 'price' => 20, 'uom' => 'unit', 'supplier_id' => $supplier2->id],
        ];

        // Loop through the array and create a record for each raw material.
        foreach ($rawMaterials as $material) {
            Product::factory()->create([
                'name' => $material['name'],
                'sku' => $material['sku'],
                'type' => 'raw_material',
                'unit_price' => $material['price'],
                'unit_of_measure' => $material['uom'],
                'category_id' => $category->id,
                //'supplier_id' => $material['supplier_id'],
                //'vendor_id' => null, // Raw materials don't have a vendor
            ]);
        }
    }
}
