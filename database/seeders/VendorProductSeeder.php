<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\VendorProduct;

class VendorProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder assigns all existing products to the first vendor.
     */
    public function run(): void
    {
        // Find the first vendor. Make sure you have at least one vendor in your 'vendors' table.
        // You can also find a specific vendor by ID or name, e.g., Vendor::find(1);
        $vendor = Vendor::first();

        if (!$vendor) {
            $this->command->info('No vendors found. Skipping VendorProductSeeder.');
            return;
        }

        // Get all products from the master catalog
        $products = Product::all();

        $this->command->info("Assigning " . $products->count() . " products to vendor: " . $vendor->name);

        foreach ($products as $product) {
            // Use updateOrCreate to avoid creating duplicate entries if the seeder is run multiple times.
            // It will find a record with this vendor_id and product_id, or create a new one.
            VendorProduct::updateOrCreate(
                [
                    'vendor_id' => $vendor->id,
                    'product_id' => $product->id,
                ],
                    [
        // Provide a value for 'retail_price' when creating a new record
        'retail_price' => $product->unit_price * 1.25,
                ]
            );
        }
    }
}
