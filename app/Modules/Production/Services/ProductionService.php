<?php

namespace App\Modules\Production\Services;

use App\Models\Product;
use App\Models\ProductionRun;
use App\Models\StockLevel;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ProductionService
{
    public function createProductionRun(User $manufacturer, string $outputSku, int $cratesToProduce): array
    {
        
        $recipe = config('manufacturing.recipes.' . $outputSku);
        
        if (!$recipe) {
            throw new InvalidArgumentException("No recipe found for SKU: {$outputSku}");
        }

        $bottlesToProduce = $cratesToProduce * 24;
        $warehouse = $manufacturer->productionPlant->warehouse;

        // 1. Calculate required materials and their cost
        $requiredMaterials = [];
        $totalCost = 0;
        foreach ($recipe['materials'] as $materialSku => $qtyPerBottle) {
            $product = Product::where('sku', $materialSku)->firstOrFail();
            $requiredQty = $qtyPerBottle * $bottlesToProduce;
            $requiredMaterials[$product->id] = $requiredQty;
            $totalCost += $requiredQty * $product->unit_price;
        }
 
        // 2. Check for sufficient stock
        $stockCheck = $this->checkStockLevels($warehouse->id, $requiredMaterials);
        if (!$stockCheck['sufficient']) {
            return $stockCheck; // Return the insufficient stock report
        }

        // 3. Perform the database operations in a transaction
        DB::transaction(function () use ($manufacturer, $recipe, $bottlesToProduce, $warehouse, $requiredMaterials, $totalCost) {
            // Deduct raw materials
            foreach ($requiredMaterials as $productId => $quantity) {
                StockLevel::where('warehouse_id', $warehouse->id)
                    ->where('product_id', $productId)
                    ->decrement('quantity', $quantity);
            }

            // Add finished goods
            $outputProduct = Product::where('sku', $recipe['output_product_sku'])->firstOrFail();
            StockLevel::firstOrCreate(
                ['warehouse_id' => $warehouse->id, 'product_id' => $outputProduct->id],
                ['quantity' => 0]
            )->increment('quantity', $bottlesToProduce);

            // Create a log
            ProductionRun::create([
                'user_id' => $manufacturer->id,
                'factory_id' => $manufacturer->productionPlant->id,
                'product_id' => $outputProduct->id,
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
            return [
                'sufficient' => false,
                'message' => 'Cannot complete production run due to low stock.',
                'errors' => $insufficient,
            ];
        }

        return ['sufficient' => true];
    }
}