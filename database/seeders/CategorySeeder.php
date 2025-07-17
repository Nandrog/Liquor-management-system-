<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Using firstOrCreate to prevent duplicates if the seeder is run multiple times.
        Category::firstOrCreate(['name' => 'Beer'], ['description' => 'Fermented malt beverages.']);
        Category::firstOrCreate(['name' => 'Whiskey'], ['description' => 'Distilled alcoholic beverages made from fermented grain mash.']);
        Category::firstOrCreate(['name' => 'Spirits'], ['description' => 'Other distilled alcoholic beverages like gin and vodka.']);
        Category::firstOrCreate(['name' => 'Wine'], ['description' => 'Fermented grape beverages.']);
        Category::firstOrCreate(['name' => 'Packaging'], ['description' => 'Materials used for bottling and packaging, such as bottles, caps, and labels.']);
        Category::firstOrCreate(['name' => 'Ingredients'], ['description' => 'Raw materials used in the production process.']);

        $this->command->info('Categories seeded successfully!');

        $categories = ['Whiskey', 'Vodka', 'Gin', 'Beer', 'Cider'];

        // Use firstOrCreate to avoid creating duplicates if run again
        foreach ($categories as $categoryName) {
            Category::firstOrCreate(
                ['name' => $categoryName],
                ['description' => "A category for various types of {$categoryName}."]
            );
        }
        $this->command->info('Categories have been seeded!');
    }
}
