<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        // --- Recipe for Blue Label Vodka ---
        $this->createRecipe(
            'blue-label',
            "Standard Blue Label Vodka Recipe",
            [
                'wheat' => 0.15,
                'activated-carbon' => 0.010,
                'glass-bottles-750ml' => 1,
                'bottle-caps' => 1,
                'paper-labels' => 1,
            ]
        );

        // --- Recipe for Bailey (Spirits) ---
        $this->createRecipe(
            'bailey',
            "Standard Bailey Recipe",
            [
                'sugar' => 0.10,
                'glass-bottles-750ml' => 1,
                'bottle-caps' => 1,
                'paper-labels' => 1,
            ]
        );

        // --- Recipe for Malibu (Spirits) ---
        $this->createRecipe(
            'malibu',
            "Standard Malibu Recipe",
            [
                'sugar' => 0.10,
                'glass-bottles-750ml' => 1,
                'bottle-caps' => 1,
                'paper-labels' => 1,
            ]
        );

        // --- Recipe for Bombay Sapphire (Gin) ---
        $this->createRecipe(
            'bombay-sapphire',
            "Standard Bombay Sapphire Gin Recipe",
            [
                'juniper-berries' => 0.010,
                'coriander' => 0.005,
                'angelica-root' => 0.002,
                'lemon-peel' => 0.003,
                'glass-bottles-750ml' => 1,
                'bottle-caps' => 1,
                'paper-labels' => 1,
            ]
        );

        // --- Recipe for Nile Special IPA 6-Pack (Beer) ---
        $this->createRecipe(
            'nile-special-ipa-6-pack',
            "Standard Nile Special IPA Recipe",
            [
                'malted-barley' => 0.17,
                'hops' => 0.002,
                'Yeast' => 0.005,
                'glass-bottles-500ml' => 6,
                'bottle-caps' => 6,
                'paper-labels' => 1,
            ]
        );

        // --- Recipe for Smirnoff 4-Pack (Cider) ---
        $this->createRecipe(
            'smirnoff-4-pack',
            "Standard Smirnoff 4-Pack Recipe",
            [
                'sugar' => 0.10,
                'Yeast' => 0.005,
                'glass-bottles-500ml' => 4,
                'bottle-caps' => 4,
                'paper-labels' => 1,
            ]
        );

        // --- Recipe for Smirnoff 6-Pack (Cider) ---
        $this->createRecipe(
            'smirnoff-6-pack',
            "Standard Smirnoff 6-Pack Recipe",
            [
                'sugar' => 0.10,
                'Yeast' => 0.005,
                'glass-bottles-500ml' => 6,
                'bottle-caps' => 6,
                'paper-labels' => 1,
            ]
        );

        // --- Recipe for Club Beer IPA 6-Pack (Beer) ---
        $this->createRecipe(
            'club-beer-ipa-6-pack',
            "Standard Club Beer IPA Recipe",
            [
                'malted-barley' => 0.17,
                'hops' => 0.002,
                'Yeast' => 0.005,
                'glass-bottles-500ml' => 6,
                'bottle-caps' => 6,
                'paper-labels' => 1,
            ]
        );
    
        // --- Recipe for Uganda Waragi 750ml ---
        $this->createRecipe(
            'uganda-waragi-premium', // SKU of the finished good
            'Standard UG Waragi Recipe',
            [ // List of materials (SKU => quantity per single output unit)
                'molasses' => 4.5,
                'cassava' => 0.005,
                'Yeast' => 0.015,
                'lemon-peel' => 0.005,
                'Charcoal' => 0.010,
                'glass-bottles-750ml' => 1,
                'bottle-caps' => 1,
                'paper-labels' => 1,
            ]
        );

        // --- Recipe for Bell Lager 500ml ---
        $this->createRecipe(
            'bell-ipa-6-pack', // SKU of the finished good
            'Standard Bell Lager Recipe',
            [
                'malted-barley' => 0.17, // 170g of Barley Malt
                'hops' => 0.002, // 2g of Hops
                'Yeast' => 0.005, // 5g of Yeast
                // Assuming we have SKUs for 500ml bottles, caps, and labels
                'glass-bottles-500ml' => 1,    // Placeholder SKU for 500ml Bottle
                'bottle-caps' => 1,   // Placeholder SKU for Bell Cap
                'paper-labels' => 1,   // Placeholder SKU for Bell Label
            ]
        );

        // --- Recipe for Botanical Garden Gin ---
        $this->createRecipe(
            'botanical-garden-gin', // Placeholder SKU for this Gin
            'Standard Botanical Gin Recipe',
            [
                'spirit-caramel' => 0.75, // 0.75L of Neutral Spirit
                'juniper-berries' => 0.010, // 10g of Juniper Berries
                'coriander' => 0.005,  // 5g of Coriander
                'angelica-root' => 0.002, // 2g of Angelica Root
                'lemon-peel' => 0.003,  // 3g of Lemon Peel
                'glass-bottles-750ml' => 1,
                'bottle-caps' => 1,
                'paper-labels' => 1,   // Placeholder SKU for Gin Label
            ]
        );

        $this->createRecipe(
            'oak-barrel-reserve-bourbon', // Placeholder SKU for this Gin
            'Standard Oak Barrel Reserve Bourbon Recipe',
            [
                'corn' => 0.12, // 0.75L of Neutral Spirit
                'rye' => 0.015, // 10g of Juniper Berries
                'malted-barley' => 0.17, // 5g of Coriander
                'Yeast' => 0.005, // 2g of Angelica Root
                'glass-bottles-750ml' => 1,
                'bottle-caps' => 1,
                'paper-labels' => 1,   // Placeholder SKU for Gin Label
            ]
        );

        $this->createRecipe(
            'oban-single-malt-scotch-whisky', // Placeholder SKU for this Gin
            'Standard OBAN Single Malt Scotch Whisky Recipe',
            [
                'corn' => 0.12, // 0.75L of Neutral Spirit
                'unmalted-barley' => 0.015, // 10g of Juniper Berries
                'malted-barley' => 0.17, // 5g of Coriander
                'Yeast' => 0.005, // 2g of Angelica Root
                'glass-bottles-750ml' => 1,
                'bottle-caps' => 1,
                'paper-labels' => 1,   // Placeholder SKU for Gin Label
            ]
        );

        $this->createRecipe(
            'hennessy-vs-cognac', // Placeholder SKU for this Gin
            'Standard Hennessy VS Cognac Recipe',
            [
                'corn' => 0.12, // 0.75L of Neutral Spirit
                'unmalted-barley' => 0.015, // 10g of Juniper Berries
                'malted-barley' => 0.17, // 5g of Coriander
                'Yeast' => 0.005, // 2g of Angelica Root
                'glass-bottles-750ml' => 1,
                'bottle-caps' => 1,
                'paper-labels' => 1,   // Placeholder SKU for Gin Label
            ]
        );

        $this->createRecipe(
            'jameson-irish-whiskey', // Placeholder SKU for this Gin
            'Standard Jameson Irish Whiskey Recipe',
            [
                'corn' => 0.12, // 0.75L of Neutral Spirit
                'unmalted-barley' => 0.015, // 10g of Juniper Berries
                'malted-barley' => 0.17, // 5g of Coriander
                'Yeast' => 0.005, // 2g of Angelica Root
                'glass-bottles-750ml' => 1,
                'bottle-caps' => 1,
                'paper-labels' => 1,   // Placeholder SKU for Gin Label
            ]
        );

        $this->createRecipe(
            'black-label-scotch-whisky', // Placeholder SKU for this Gin
            'Standard Black Label Scotch Whisky Recipe',
            [
                'corn' => 0.12, // 0.75L of Neutral Spirit
                'unmalted-barley' => 0.015, // 10g of Juniper Berries
                'malted-barley' => 0.17, // 5g of Coriander
                'Yeast' => 0.005, // 2g of Angelica Root
                'glass-bottles-750ml' => 1,
                'bottle-caps' => 1,
                'paper-labels' => 1,   // Placeholder SKU for Gin Label
            ]
        );

        $this->createRecipe(
            'arctic-crystal-vodka', // Placeholder SKU for this Gin
            'Standard Arctic Crystal Vodka Recipe',
            [
                'wheat' => 0.15, // 0.75L of Neutral Spirit
               'activated-carbon' => 0.010, // 10g of Juniper Berries
                'malted-barley' => 0.17, // 5g of Coriander
                'Yeast' => 0.005, // 2g of Angelica Root
                'glass-bottles-750ml' => 1,
                'bottle-caps' => 1,
                'paper-labels' => 1,   // Placeholder SKU for Gin Label
            ]
        );

        $this->createRecipe(
            'tusker-lager-4-pack', // Placeholder SKU for this Gin
            'Standard Tusker Recipe',
            [
                'corn' => 0.12, // 0.75L of Neutral Spirit
               'hops' => 0.005, // 10g of Juniper Berries
                'malted-barley' => 0.17, // 5g of Coriander
                'Yeast' => 0.008, // 2g of Angelica Root
                'glass-bottles-750ml' => 1,
                'bottle-caps' => 1,
                'paper-labels' => 1,   // Placeholder SKU for Gin Label
            ]
        );

        $this->createRecipe(
            'hinchs-peated-single-malt', // Placeholder SKU for this Gin
            'Standard Hinchs Peated Single Malt Recipe',
            [
                'corn' => 0.12, // 0.75L of Neutral Spirit
               'potassium-sorbate' => 0.015, // 10g of Juniper Berries
                'citric-acid' => 0.27, // 5g of Coriander
                'high-fructose-corn-syrup' => 0.038, // 2g of Angelica Root
                'gypsum-calcium-sulfate'=>0.012,
                'cassia-bark'=>0.009,
                'peat'=>0.015,
                'glass-bottles-750ml' => 1,
                'bottle-caps' => 1,
                'paper-labels' => 1,   // Placeholder SKU for Gin Label
            ]
        );

        $this->createRecipe(
            'smoky-peat-single-malt', // Placeholder SKU for this Gin
            'Standard Smoky Peat Single Malt Recipe',
            [
                'corn' => 0.12, // 0.75L of Neutral Spirit
               'potassium-sorbate' => 0.015, // 10g of Juniper Berries
                'citric-acid' => 0.27, // 5g of Coriander
                'high-fructose-corn-syrup' => 0.038, // 2g of Angelica Root
                'gypsum-calcium-sulfate'=>0.012,
                'cassia-bark'=>0.009,
                'peat'=>0.015,
                'glass-bottles-750ml' => 1,
                'bottle-caps' => 1,
                'paper-labels' => 1,   // Placeholder SKU for Gin Label
            ]
        );

        $this->createRecipe(
            'captain-morgan-premium', // Placeholder SKU for this Gin
            'Standard Captain morgan premium Recipe',
            [
                'juniper-berries' => 0.12, // 0.75L of Neutral Spirit
               'licorice' => 0.015, // 10g of Juniper Berries
                'orris-root' => 0.27, // 5g of Coriander
                'high-fructose-corn-syrup' => 0.038, // 2g of Angelica Root
                'coriander'=>0.012,
                'cassia-bark'=>0.010,
                'calcium-chloride'=>0.014,
                'almonds'=>0.009,
                'angelica-root'=>0.015,
                'glass-bottles-750ml' => 1,
                'bottle-caps' => 1,
                'paper-labels' => 1,   // Placeholder SKU for Gin Label
            ]
        );

        $this->command->info('Multiple product recipes seeded successfully!');
    }

    /**
     * A helper function to create a recipe and attach its materials.
     */
    private function createRecipe(string $outputSku, string $recipeName, array $materials): void
    {
        $outputProduct = Product::where('sku', $outputSku)->first();

        // Only proceed if the finished good product exists
        if (!$outputProduct) {
            $this->command->warn("Could not find finished good with SKU '{$outputSku}'. Skipping recipe creation.");
            return;
        }

        // Create the main recipe record
        $recipe = Recipe::updateOrCreate(
            ['output_product_id' => $outputProduct->id],
            ['name' => $recipeName]
        );

        // Prepare materials for attaching
        $materialsToAttach = [];
        foreach ($materials as $materialSku => $quantity) {
            $materialProduct = Product::where('sku', $materialSku)->first();
            if ($materialProduct) {
                // The format for attach/sync is [product_id => ['pivot_data']]
                $materialsToAttach[$materialProduct->id] = ['quantity' => $quantity];
            } else {
                $this->command->warn("Could not find material with SKU '{$materialSku}' for recipe '{$recipeName}'. Skipping material.");
            }
        }

        // Use sync() to attach the materials. This is idempotent and safe to re-run.
        // It will add/update/remove materials to match the provided array exactly.
        if (!empty($materialsToAttach)) {
            $recipe->materials()->sync($materialsToAttach);
        }
    }
}
