<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
public function run(): void
{
$ugandaWaragi = Product::where('sku', 'FG-UWG-750')->first();

if ($ugandaWaragi) {
$recipe = Recipe::create([
'output_product_id' => $ugandaWaragi->id,
'name' => 'Standard UG Waragi Recipe',
]);

$materials = [
'RM-MOL-01' => 4.5,
'RM-CASS-01' => 0.005,
'RM-YST-01' => 0.015,
'RM-CTP-01' => 0.005,
'RM-CHR-01' => 0.010,
'RM-BOT-750' => 1,
'RM-CAP-01' => 1,
'RM-LBL-01' => 1,
];

foreach ($materials as $sku => $quantity) {
$materialProduct = Product::where('sku', $sku)->first();
if ($materialProduct) {
$recipe->materials()->attach($materialProduct->id, ['quantity' => $quantity]);
}
}
}
}
}