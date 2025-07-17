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
        $supplier1 = User::where('email', 'supplier1@example.com')->first();
        $supplier2 = User::where('email', 'supplier2@example.com')->first();
        $vendor = Vendor::first();
        $category = Category::first();

        if (!$vendor) {
            $this->command->error('No vendor found. Please seed vendors first.');
            return;
        }

        if (!$category) {
            $this->command->error('No category found. Please seed categories first.');
            return;
        }

        // --- Finished Goods ---
        $finishedGoods = [
            [
                'sku' => 'FG-UWG-750',
                'name' => 'Uganda Waragi 750ml',
                'type' => 'finished_good',
                'unit_price' => 25000,
                'unit_of_measure' => 'bottle',
                'vendor_id' => $vendor->id,
            ],
            [
                'sku' => 'FG-BELL-500',
                'name' => 'Bell Lager 500ml',
                'type' => 'finished_good',
                'unit_price' => 3500,
                'unit_of_measure' => 'bottle',
                'vendor_id' => $vendor->id,
            ],
            [
                 'sku' => 'FG-TUSK-500',
                 'name' => 'Tusker Lager 500ml',
                 'type' => 'finished_good',
                 'unit_price' => 3300,
                 'unit_of_measure' => 'bottle',
                 'vendor_id' => $vendor->id,
            ],
[
    'sku' => 'FG-MALT-330',
    'name' => 'Malta Guinness 330ml',
    'type' => 'finished_good',
    'unit_price' => 2800,
    'unit_of_measure' => 'can',
    'vendor_id' => $vendor->id,
],
            [
    'sku' => 'FG-GIN-250',
    'name' => 'Craft Gin 250ml',
    'type' => 'finished_good',
    'unit_price' => 12000,
    'unit_of_measure' => 'bottle',
    'vendor_id' => $vendor->id,
],
[
    'sku' => 'FG-GIN-750',
    'name' => 'Craft Gin 750ml',
    'type' => 'finished_good',
    'unit_price' => 27000,
    'unit_of_measure' => 'bottle',
    'vendor_id' => $vendor->id,
],
[
    'sku' => 'FG-RUM-500',
    'name' => 'Dark Rum 500ml',
    'type' => 'finished_good',
    'unit_price' => 18000,
    'unit_of_measure' => 'bottle',
    'vendor_id' => $vendor->id,
],
[
    'sku' => 'FG-VODKA-375',
    'name' => 'Triple Distilled Vodka 375ml',
    'type' => 'finished_good',
    'unit_price' => 15000,
    'unit_of_measure' => 'bottle',
    'vendor_id' => $vendor->id,
],
[
    'sku' => 'FG-WHISKY-750',
    'name' => 'Blended Whisky 750ml',
    'type' => 'finished_good',
    'unit_price' => 35000,
    'unit_of_measure' => 'bottle',
    'vendor_id' => $vendor->id,
],
[
    'sku' => 'FG-BRNDY-500',
    'name' => 'Fruit Brandy 500ml',
    'type' => 'finished_good',
    'unit_price' => 22000,
    'unit_of_measure' => 'bottle',
    'vendor_id' => $vendor->id,
],

        ];

        foreach ($finishedGoods as $product) {
            Product::firstOrCreate(
                ['sku' => $product['sku']],
                [
                    'name' => $product['name'],
                    'type' => $product['type'],
                    'unit_price' => $product['unit_price'],
                    'unit_of_measure' => $product['unit_of_measure'],
                    'category_id' => $category->id,
                    'vendor_id' => $product['vendor_id'],
                ]
            );
        }

        // --- Raw Materials ---
        $rawMaterials = [
            ['sku' => 'RM-MOL-01', 'name' => 'Molasses', 'price' => 2000, 'uom' => 'kg', 'supplier_id' => $supplier1->id],
            ['sku' => 'RM-BAN-01', 'name' => 'Bananas', 'price' => 1000, 'uom' => 'kg', 'supplier_id' => $supplier1->id],
            ['sku' => 'RM-CIT-P-01', 'name' => 'Citrus Peels', 'price' => 800, 'uom' => 'kg', 'supplier_id' => $supplier1->id],
            ['sku' => 'RM-JUN-B-01', 'name' => 'Juniper Berries', 'price' => 15000, 'uom' => 'kg', 'supplier_id' => $supplier1->id],
            ['sku' => 'RM-CHR-01', 'name' => 'Charcoal', 'price' => 2500, 'uom' => 'kg', 'supplier_id' => $supplier2->id],
            ['sku' => 'RM-CIT-A-01', 'name' => 'Citric Acid', 'price' => 6000, 'uom' => 'kg', 'supplier_id' => $supplier2->id],
            ['sku' => 'RM-BOT-750', 'name' => 'Glass Bottles 750ml', 'price' => 500, 'uom' => 'unit', 'supplier_id' => $supplier2->id],
            ['sku' => 'RM-CAP-01', 'name' => 'Bottle Caps', 'price' => 50, 'uom' => 'unit', 'supplier_id' => $supplier2->id],
            ['sku' => 'RM-LBL-01', 'name' => 'Paper Labels', 'price' => 20, 'uom' => 'unit', 'supplier_id' => $supplier2->id],
            [
    'sku' => 'RM-MASH-01',
    'name' => 'Fermentation Mash (Banana-Cassava Mix)',
    'price' => 1500,
    'uom' => 'kg',
    'supplier_id' => $supplier1->id,
],
[
    'sku' => 'RM-MALT-01',
    'name' => 'Barley Malt',
    'price' => 2500,
    'uom' => 'kg',
    'supplier_id' => $supplier1->id,
],
[
    'sku' => 'RM-YEAST-01',
    'name' => 'Distillers Yeast',
    'price' => 18000,
    'uom' => 'kg',
    'supplier_id' => $supplier1->id,
],
[
    'sku' => 'RM-SPR-ALC-01',
    'name' => 'Neutral Spirit Alcohol',
    'price' => 12000,
    'uom' => 'litre',
    'supplier_id' => $supplier2->id,
],
[
    'sku' => 'RM-OAK-01',
    'name' => 'Oak Chips (Aging)',
    'price' => 5000,
    'uom' => 'kg',
    'supplier_id' => $supplier2->id,
],
[
    'sku' => 'RM-GLS750',
    'name' => 'Liquor Bottles 750ml',
    'price' => 600,
    'uom' => 'unit',
    'supplier_id' => $supplier2->id,
],
[
    'sku' => 'RM-CAPS-ALC',
    'name' => 'Metallic Bottle Caps',
    'price' => 100,
    'uom' => 'unit',
    'supplier_id' => $supplier2->id,
],
[
    'sku' => 'RM-ESSNC-GIN',
    'name' => 'Gin Botanical Essence',
    'price' => 20000,
    'uom' => 'litre',
    'supplier_id' => $supplier2->id,
],
[
    'sku' => 'RM-FLTR-01',
    'name' => 'Carbon Filtration Media',
    'price' => 15000,
    'uom' => 'kg',
    'supplier_id' => $supplier2->id,
],

        ];

        foreach ($rawMaterials as $material) {
            Product::firstOrCreate(
                ['sku' => $material['sku']],
                [
                    'name' => $material['name'],
                    'type' => 'raw_material',
                    'unit_price' => $material['price'],
                    'unit_of_measure' => $material['uom'],
                    'category_id' => $category->id,
                    // Uncomment this line only if products table has a `supplier_id` column
                    // 'supplier_id' => $material['supplier_id'],
                ]
            );
        }
    }
}
