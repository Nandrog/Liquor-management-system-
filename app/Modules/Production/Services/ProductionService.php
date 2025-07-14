<?php

namespace App\Modules\Production\Services;

use App\Models\Product;
use App\Models\ProductionRun;
use App\Models\Recipe;
use App\Models\StockLevel;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ProductionService
{
    public function createProductionRun(User $manufacturer, int $outputProductId, int $cratesToProduce): array
    {
        $recipe = Recipe::where('output_product_id', $outputProductId)->with('materials')->first();

        if (!$recipe) {
            throw new InvalidArgumentException("No recipe found for Product ID: {$outputProductId}");
        }

        $bottlesToProduce = $cratesToProduce * 24;
        $warehouse = $manufacturer->productionPlant->warehouse;

        $requiredMaterials = [];
        $totalCost = 0;
        foreach ($recipe->materials as $material) {
            $requiredQty = $material->pivot->quantity * $bottlesToProduce;
            $requiredMaterials[$material->id] = $requiredQty;
            $totalCost += $requiredQty * $material->unit_price;
        }

        // --- FIX #1: Use the correct primary key ---
        $stockCheck = $this->checkStockLevels($warehouse->warehouse_id, $requiredMaterials);
        if (!$stockCheck['sufficient']) {
            return $stockCheck;
        }

        DB::transaction(function () use ($manufacturer, $recipe, $bottlesToProduce, $warehouse, $requiredMaterials, $totalCost) {
            foreach ($requiredMaterials as $productId => $quantity) {
                // --- FIX #2: Use the correct primary key ---
                StockLevel::where('warehouse_id', $warehouse->warehouse_id)
                    ->where('product_id', $productId)
                    ->decrement('quantity', $quantity);
            }

            // --- FIX #3: Use the correct primary key ---
            StockLevel::firstOrCreate(
                ['warehouse_id' => $warehouse->warehouse_id, 'product_id' => $recipe->output_product_id],
                ['quantity' => 0]
            )->increment('quantity', $bottlesToProduce);

            ProductionRun::create([
                'user_id' => $manufacturer->id,
                'factory_id' => $manufacturer->productionPlant->id,
                'product_id' => $recipe->output_product_id,
                'quantity_produced' => $bottlesToProduce,
                'cost_of_materials' => $totalCost,
                'completed_at' => now(),
            ]);
        });

        return [
            'sufficient' => true,
            'message' => "Successfully produced {$bottlesToProduce} bottles.",
            'cost' => $totalCost,
        ];
    }

    private function checkStockLevels(int $warehouseId, array $requiredMaterials): array
    {
        // This method is now correct because it receives the proper ID from the call above.
        $currentStock = StockLevel::where('warehouse_id', $warehouseId)
            ->whereIn('product_id', array_keys($requiredMaterials))
            ->pluck('quantity', 'product_id');

        $insufficient = [];
        foreach ($requiredMaterials as $productId => $requiredQty) {
            $onHand = $currentStock->get($productId, 0);
            if ($onHand < $requiredQty) {
                $product = Product::find($productId);
                $insufficient[] = "Not enough {$product->name}. Required: {$requiredQty}, On Hand: {$onHand}";
            }
        }

        if (!empty($insufficient)) {
            return ['sufficient' => false, 'message' => 'Cannot complete production run due to low stock.', 'errors' => $insufficient];
        }

        return ['sufficient' => true];
    }
}