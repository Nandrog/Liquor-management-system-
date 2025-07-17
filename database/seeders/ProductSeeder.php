<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {

        // Fetch all categories from the database to create a name => id lookup map.
        // This is much more efficient than querying inside the loop.
        $categories = Category::pluck('id', 'name')->all();

        // The product data provided by you.
        $products = [
            ['image' => "whiskey-bourbon.jpg", 'name' => "Oak Barrel Reserve Bourbon", 'price' => 95500, 'category' => "Whiskey"],
            ['image' => "whiskey-single-malt.jpg", 'name' => "OBAN Single Malt Scotch Whisky", 'price' => 362000, 'category' => "Whiskey"],
            ['image' => "Hinch-Peated-Single-Malt.jpg", 'name' => "Hinch's Peated Single Malt", 'price' => 762000, 'category' => "Whiskey"],
            ['image' => "whiskey-single-malt.jpg", 'name' => "Smoky Peat Single Malt", 'price' => 162000, 'category' => "Whiskey"],
            ['image' => "Hennessy.jpg", 'name' => "Hennessy VS Cognac", 'price' => 592000, 'category' => "Whiskey"],
            ['image' => "uganda-waragi.jpg", 'name' => "Uganda Waragi premium", 'price' => 72000, 'category' => "Whiskey"],
            ['image' => "Captain-morgan.jpg", 'name' => "Captain morgan premium", 'price' => 70000, 'category' => "Whiskey"],
            ['image' => "uganda-waragi.jpg", 'name' => "Uganda Waragi lemon and ginger", 'price' => 82000, 'category' => "Whiskey"],
            ['image' => "Jameson.jpg", 'name' => "Jameson Irish Whiskey", 'price' => 82000, 'category' => "Whiskey"],
            ['image' => "black-label.jpg", 'name' => "Black Label Scotch Whisky", 'price' => 582000, 'category' => "Whiskey"],
            ['image' => "red-label.jpg", 'name' => "Red Label Scotch Whisky", 'price' => 482000, 'category' => "Whiskey"],
            ['image' => "grants.jpg", 'name' => "Grant's Family Reserve", 'price' => 92000, 'category' => "Whiskey"],
            ['image' => "vodka-crystal.jpg", 'name' => "Arctic Crystal Vodka", 'price' => 78900, 'category' => "Vodka"],
            ['image' => "grants.jpg", 'name' => "Grant's Family Reserve", 'price' => 92000, 'category' => "Whiskey"],
            ['image' => "vodka-crystal.jpg", 'name' => "Arctic Crystal Vodka", 'price' => 78900, 'category' => "Vodka"],
            ['image' => "gin-botanical.jpg", 'name' => "Botanical Garden Gin", 'price' => 84500, 'category' => "Gin"],
            ['image' => "blue-label.jpg", 'name' => "blue label", 'price' => 78900, 'category' => "Vodka"],
            ['image' => "bailey.jpg", 'name' => "Bailey", 'price' => 94500, 'category' => "Gin"],
            ['image' => "Malibu.jpg", 'name' => "Malibu", 'price' => 79900, 'category' => "Vodka"],
            ['image' => "bombay-sapphire.jpg", 'name' => "Bombay Sapphire", 'price' => 84700, 'category' => "Gin"],
            ['image' => "bell-beer.jpg", 'name' => "Bell IPA 6-Pack", 'price' => 38900, 'category' => "Beer"],
            ['image' => "tusker.jpg", 'name' => "Tusker 4-Pack", 'price' => 35500, 'category' => "Cider"],
            ['image' => "nile.jpg", 'name' => "Nile special IPA 6-Pack", 'price' => 38900, 'category' => "Beer"],
            ['image' => "smirnoff-4.jpg", 'name' => "Smirnoff 4-Pack", 'price' => 35500, 'category' => "Cider"],
            ['image' => "smirnoff.jpg", 'name' => "Smirnoff 6-Pack", 'price' => 35500, 'category' => "Cider"],
            ['image' => "club.jpg", 'name' => "Club Beer IPA 6-Pack", 'price' => 38900, 'category' => "Beer"],
];


        foreach ($products as $index => $productData) {

            // Find the category ID from our lookup map.
            $categoryId = $categories[$productData['category']] ?? null;

            // Skip this product if its category doesn't exist.
            if (!$categoryId) {
                $this->command->error("Category '{$productData['category']}' not found for product '{$productData['name']}'. Skipping.");
                continue;
            }

            // Intelligently guess the unit of measure.
            $unit = Str::contains($productData['name'], ['Pack', '6-Pack', '4-Pack']) ? 'Pack' : 'Bottle';

            $isFeatured = ($index >= 1);

            // Create the product using only the columns that exist in your table.
            Product::create([
                'name'              => $productData['name'],
                'sku'               => Str::slug($productData['name']) . '-' . Str::lower(Str::random(4)),
                'description'       => 'A fine ' . $productData['name'] . '.',
                'unit_price'        => $productData['price'],
                'unit_of_measure'   => $unit,
                'stock'             => rand(25, 200), // Assign some random stock
                'reorder_level'     => 10,
                'type'              => 'finished_good',
                'category_id'       => $categoryId,
                'is_featured'       => $isFeatured,
                'image_filename'    => $productData['image'],
                // 'user_id' and 'vendor_id' are nullable, so we can leave them empty for now.
            ]);
        }

        $this->command->info('Products have been seeded successfully!');

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
