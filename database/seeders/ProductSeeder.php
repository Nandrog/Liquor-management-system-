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
        // --- 1. GATHER DEPENDENCIES EFFICIENTLY ---
        $categories = Category::pluck('id', 'name');
        $vendors = Vendor::pluck('id', 'name');
        $suppliers = User::role('Supplier')->pluck('id', 'username');

        if ($categories->isEmpty() || $vendors->isEmpty() || $suppliers->isEmpty()) {
            $this->command->error('Please seed Categories, Vendors, and Supplier Users before running the ProductSeeder.');
            return;
        }
        $vendorNames = $vendors->keys()->toArray();

        // --- 2. DEFINE ALL PRODUCTS IN A SINGLE, UNIFIED ARRAY ---
        $products = [
            // --- Finished Goods  ---
            ['image' => "whiskey-bourbon.jpg",'name' => "Oak Barrel Reserve Bourbon", 'price' => 95500, 'category' => "Whiskey", 'type' => 'finished_good'],
            ['image' => "whiskey-single-malt.jpg",'name' => "OBAN Single Malt Scotch Whisky", 'price' => 362000, 'category' => "Whiskey", 'type' => 'finished_good'],
            ['image' => "Hennessy.jpg",'name' => "Hennessy VS Cognac", 'price' => 592000, 'category' => "Spirits", 'type' => 'finished_good'],
            ['image' => "Jameson.jpg",'name' => "Jameson Irish Whiskey", 'price' => 82000, 'category' => "Whiskey", 'type' => 'finished_good'],
            ['image' => "black-label.jpg",'name' => "Black Label Scotch Whisky", 'price' => 582000, 'category' => "Whiskey", 'type' => 'finished_good'],
            ['image' => "vodka-crystal.jpg",'name' => "Arctic Crystal Vodka", 'price' => 78900, 'category' => "Vodka", 'type' => 'finished_good'],
            ['image' => "gin-botanical.jpg", 'name' => "Botanical Garden Gin", 'price' => 84500, 'category' => "Gin", 'type' => 'finished_good'],
            ['image' => "bell-beer.jpg",'name' => "Bell IPA 6-Pack", 'price' => 38900, 'category' => "Beer", 'type' => 'finished_good'],
            ['image' => "tusker.jpg",'name' => "Tusker Lager 4-Pack", 'price' => 35500, 'category' => "Beer", 'type' => 'finished_good'],
            ['image' => "Hinch-Peated-Single-Malt.jpg",'name' => "Hinch's Peated Single Malt", 'price' => 762000, 'category' => "Whiskey", 'type' => 'finished_good'],
            ['image' => "whiskey-single-malt.jpg", 'name' => "Smoky Peat Single Malt", 'price' => 162000, 'category' => "Whiskey", 'type' => 'finished_good'],
            ['image' => "uganda-waragi.jpg",'name' => "Uganda Waragi premium", 'price' => 72000, 'category' => "Spirits", 'type' => 'finished_good'],
            ['image' => "Captain-morgan.jpg",'name' => "Captain morgan premium", 'price' => 70000, 'category' => "Spirits", 'type' => 'finished_good'],
            ['image' => "uganda-waragi.jpg",'name' => "Uganda Waragi lemon and ginger", 'price' => 82000, 'category' => "Spirits", 'type' => 'finished_good'],
            ['image' => "red-label.jpg",'name' => "Red Label Scotch Whisky", 'price' => 482000, 'category' => "Whiskey", 'type' => 'finished_good'],
            ['image' => "grants.jpg", 'name' => "Grant's Family Reserve", 'price' => 92000, 'category' => "Whiskey", 'type' => 'finished_good'],
            ['image' => "blue-label.jpg", 'name' => "blue label", 'price' => 78900, 'category' => "Vodka", 'type' => 'finished_good'],
            ['image' => "bailey.jpg", 'name' => "Bailey", 'price' => 94500, 'category' => "Spirits", 'type' => 'finished_good'],
            ['image' => "Malibu.jpg",'name' => "Malibu", 'price' => 79900, 'category' => "Spirits", 'type' => 'finished_good'],
            ['image' => "bombay-sapphire.jpg",'name' => "Bombay Sapphire", 'price' => 84700, 'category' => "Gin", 'type' => 'finished_good'],
            ['image' => "nile.jpg",'name' => "Nile special IPA 6-Pack", 'price' => 38900, 'category' => "Beer", 'type' => 'finished_good'],
            ['image' => "smirnoff-4.jpg",'name' => "Smirnoff 4-Pack", 'price' => 35500, 'category' => "Cider", 'type' => 'finished_good'],
            ['image' => "smirnoff.jpg", 'name' => "Smirnoff 6-Pack", 'price' => 35500, 'category' => "Cider", 'type' => 'finished_good'],
            ['image' => "club.jpg",'name' => "Club Beer IPA 6-Pack", 'price' => 38900, 'category' => "Beer", 'type' => 'finished_good'],

            // --- Raw Materials ---
            ['name' => 'Molasses', 'price' => 2000, 'category' => 'Ingredients', 'supplier' => 'amossupplier', 'type' => 'raw_material'],
            ['name' => 'Bananas', 'price' => 1000, 'category' => 'Ingredients', 'supplier' => 'amossupplier', 'type' => 'raw_material'],
            ['name' => 'Sugar', 'price' => 3500, 'category' => 'Ingredients', 'supplier' => 'amossupplier', 'type' => 'raw_material'],
            ['name' => 'High-Fructose Corn Syrup', 'price' => 4000, 'category' => 'Ingredients', 'supplier' => 'amossupplier', 'type' => 'raw_material'],
            ['name' => 'Cassava', 'price' => 2000, 'category' => 'Ingredients', 'supplier' => 'amossupplier', 'type' => 'raw_material'],

            ['name' => 'Juniper Berries', 'price' => 15000, 'category' => 'Ingredients', 'supplier' => 'botanicalssupplier', 'type' => 'raw_material'],
            ['name' => 'Coriander', 'price' => 8000, 'category' => 'Ingredients', 'supplier' => 'botanicalssupplier', 'type' => 'raw_material'],
            ['name' => 'Angelica Root', 'price' => 18000, 'category' => 'Ingredients', 'supplier' => 'botanicalssupplier', 'type' => 'raw_material'],
            ['name' => 'Lemon Peel', 'price' => 4000, 'category' => 'Ingredients', 'supplier' => 'botanicalssupplier', 'type' => 'raw_material'],
            ['name' => 'Orris Root', 'price' => 22000, 'category' => 'Ingredients', 'supplier' => 'botanicalssupplier', 'type' => 'raw_material'],
            ['name' => 'Grains of Paradise', 'price' => 30000, 'category' => 'Ingredients', 'supplier' => 'botanicalssupplier', 'type' => 'raw_material'],
            ['name' => 'Cubeb Berries', 'price' => 28000, 'category' => 'Ingredients', 'supplier' => 'botanicalssupplier', 'type' => 'raw_material'],
            ['name' => 'Cassia Bark', 'price' => 12000, 'category' => 'Ingredients', 'supplier' => 'botanicalssupplier', 'type' => 'raw_material'],
            ['name' => 'Almonds', 'price' => 9000, 'category' => 'Ingredients', 'supplier' => 'botanicalssupplier', 'type' => 'raw_material'],
            ['name' => 'Licorice', 'price' => 11000, 'category' => 'Ingredients', 'supplier' => 'botanicalssupplier', 'type' => 'raw_material'],
            ['name' => 'Hops', 'price' => 25000, 'category' => 'Ingredients', 'supplier' => 'botanicalssupplier', 'type' => 'raw_material'],

            ['name' => 'Peat', 'price' => 5000, 'category' => 'Ingredients', 'supplier' => 'mariasupplier', 'type' => 'raw_material'],
            ['name' => 'Charcoal', 'price' => 2500, 'category' => 'Ingredients', 'supplier' => 'davidsupplier', 'type' => 'raw_material'],

            ['name' => 'Glass Bottles 750ml', 'price' => 500, 'category' => 'Packaging', 'supplier' => 'petersupplier', 'type' => 'raw_material'],
            ['name' => 'Glass Bottles 500ml', 'price' => 500, 'category' => 'Packaging', 'supplier' => 'petersupplier', 'type' => 'raw_material'],
            ['name' => 'Bottle Caps', 'price' => 50, 'category' => 'Packaging', 'supplier' => 'petersupplier', 'type' => 'raw_material'],
            ['name' => 'Paper Labels', 'price' => 20, 'category' => 'Packaging', 'supplier' => 'sarahsupplier', 'type' => 'raw_material'],
            
            ['name' => 'Spirit Caramel', 'price' => 15000, 'category' => 'Additives & Chemicals', 'supplier' => 'chemcosupplier', 'type' => 'raw_material'],
            ['name' => 'Activated Carbon', 'price' => 10000, 'category' => 'Additives & Chemicals', 'supplier' => 'chemcosupplier', 'type' => 'raw_material'],
            ['name' => 'Gypsum (Calcium Sulfate)', 'price' => 5000, 'category' => 'Additives & Chemicals', 'supplier' => 'chemcosupplier', 'type' => 'raw_material'],
            ['name' => 'Calcium Chloride', 'price' => 6000, 'category' => 'Additives & Chemicals', 'supplier' => 'chemcosupplier', 'type' => 'raw_material'],
            ['name' => 'Epsom Salt (Magnesium Sulfate)', 'price' => 5500, 'category' => 'Additives & Chemicals', 'supplier' => 'chemcosupplier', 'type' => 'raw_material'],
            ['name' => 'Campden Tablets', 'price' => 200, 'category' => 'Additives & Chemicals', 'supplier' => 'chemcosupplier', 'type' => 'raw_material'],
            ['name' => 'Irish Moss / Whirlfloc', 'price' => 300, 'category' => 'Additives & Chemicals', 'supplier' => 'chemcosupplier', 'type' => 'raw_material'],
            ['name' => 'Citric Acid', 'price' => 7000, 'category' => 'Additives & Chemicals', 'supplier' => 'chemcosupplier', 'type' => 'raw_material'],
            ['name' => 'Lactic Acid', 'price' => 8000, 'category' => 'Additives & Chemicals', 'supplier' => 'chemcosupplier', 'type' => 'raw_material'],
            ['name' => 'Potassium Sorbate', 'price' => 9000, 'category' => 'Additives & Chemicals', 'supplier' => 'chemcosupplier', 'type' => 'raw_material'],
            ['name' => 'Yeast', 'price' => 3000, 'category' => 'Additives & Chemicals', 'supplier' => 'chemcosupplier', 'type' => 'raw_material'],
            
            ['name' => 'Corn', 'price' => 2500, 'category' => 'Ingredients', 'supplier' => 'amossupplier', 'type' => 'raw_material'],
            ['name' => 'Rye', 'price' => 2700, 'category' => 'Ingredients', 'supplier' => 'amossupplier', 'type' => 'raw_material'],
            ['name' => 'Malted Barley', 'price' => 3200, 'category' => 'Ingredients', 'supplier' => 'amossupplier', 'type' => 'raw_material'],
            ['name' => 'Unmalted Barley', 'price' => 3000, 'category' => 'Ingredients', 'supplier' => 'amossupplier', 'type' => 'raw_material'],
            ['name' => 'Wheat', 'price' => 2200, 'category' => 'Ingredients', 'supplier' => 'amossupplier', 'type' => 'raw_material'],
        ];

        // --- 3. LOOP AND CREATE/UPDATE PRODUCTS IN THE DATABASE ---
        foreach ($products as $productData) {
            // Set defaults for finished goods if not specified
            $type = $productData['type'] ?? 'raw_material';
            // Assign appropriate unit of measure
            if (isset($productData['uom'])) {
                $uom = $productData['uom'];
            } elseif ($type === 'finished_good') {
                if (Str::contains($productData['name'], ['Pack', '6-Pack', '4-Pack'])) {
                    $uom = 'Pack';
                } elseif (Str::contains($productData['name'], ['Bottle', 'Vodka', 'Whisky', 'Gin', 'Cognac', 'Beer', 'Cider', 'Spirits'])) {
                    $uom = 'Bottle';
                } else {
                    $uom = 'Unit';
                }
            } elseif ($type === 'raw_material') {
                if (Str::contains($productData['name'], ['Sugar', 'Molasses', 'High-Fructose Corn Syrup', 'Bananas', 'Cassava'])) {
                    $uom = 'Kg';
                } elseif (Str::contains($productData['name'], ['Yeast', 'Citric Acid', 'Lactic Acid', 'Potassium Sorbate', 'Spirit Caramel', 'Activated Carbon', 'Gypsum', 'Calcium Chloride', 'Epsom Salt', 'Campden Tablets', 'Irish Moss', 'Additives', 'Chemicals'])) {
                    $uom = 'Kg';
                } elseif (Str::contains($productData['name'], ['Glass Bottles', 'Bottle Caps', 'Paper Labels'])) {
                    $uom = 'Piece';
                } elseif (Str::contains($productData['name'], ['Juniper Berries', 'Coriander', 'Angelica Root', 'Lemon Peel', 'Orris Root', 'Grains of Paradise', 'Cubeb Berries', 'Cassia Bark', 'Almonds', 'Licorice', 'Hops', 'Peat', 'Charcoal', 'Corn', 'Rye', 'Malted Barley', 'Unmalted Barley', 'Wheat'])) {
                    $uom = 'Kg';
                } else {
                    $uom = 'Unit';
                }
            } else {
                $uom = 'Unit';
            }

            // Randomly assign a vendor to finished goods
            $vendorName = $type === 'finished_good' ? $vendorNames[array_rand($vendorNames)] : null;

            Product::updateOrCreate(
                // Use a generated SKU as the unique identifier to prevent duplicates
                ['sku' => Str::slug($productData['name'])],
                [
                    'name'              => $productData['name'],
                    'description'       => 'High quality ' . $productData['name'],
                    'unit_price'        => $productData['price'],
                    'unit_of_measure'   => $uom,
                    'reorder_level'     => rand(1000, 8000),
                    'type'              => $type,
                    'category_id'       => $categories->get($productData['category']),
                    'vendor_id'         => $vendors->get($vendorName),
                    'user_id'           => $suppliers->get($productData['supplier'] ?? null),
                    'image_filename' => $productData['image'] ?? null,
                ]
            );
        }

        $this->command->info('Products table seeded successfully with unified data!');
    }
}