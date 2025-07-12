<?php

namespace App\Services;

use App\Models\Order;
use App\Models\StockLevel;
use Exception;
use Illuminate\Support\Facades\Log;

class InventoryService
{
    /**
     * Increases stock in a SPECIFIC WAREHOUSE from a supplier order.
     * This method is the primary entry point for adding new inventory to the system.
     *
     * @param Order $order The supplier order that has been delivered.
     * @param int $warehouseId The ID of the warehouse where the stock is being received.
     * @return void
     */
    public function addStockFromSupplierOrder(Order $order, int $warehouseId): void
    {
        foreach ($order->items as $item) {
            // `firstOrCreate` is essential for a multi-warehouse system. It finds the stock
            // level record for a specific product in a specific warehouse. If this is the
            // first time this product is being added to this warehouse, it creates a new record.
            $stockLevel = StockLevel::firstOrCreate(
                [
                    'product_id'   => $item->product_id,
                    'warehouse_id' => $warehouseId, // The composite key to find the record
                ],
                ['quantity' => 0] // Default value if creating a new record
            );

            // Use the atomic `increment` operation to safely add the new quantity.
            // This prevents race conditions if multiple operations happen simultaneously.
            $stockLevel->increment('quantity', $item->quantity);

            // Detailed logging is crucial for auditing inventory movements.
            Log::info(
                "Stock Added: Product ID {$item->product_id} increased by {$item->quantity} in Warehouse ID {$warehouseId}. " .
                "New quantity in warehouse: {$stockLevel->fresh()->quantity}. (From Order #{$order->id})"
            );
        }
    }

    /**
     * Decreases stock from a SPECIFIC WAREHOUSE to fulfill a customer order.
     * This method includes critical checks to prevent overselling.
     *
     * @param Order $order The customer order to fulfill.
     * @param int $warehouseId The ID of the warehouse to ship from.
     * @throws Exception If there is insufficient stock for any item in the specified warehouse.
     * @return void
     */
    public function removeStockForCustomerOrder(Order $order, int $warehouseId): void
    {
        // Before deducting anything, validate that the entire order can be fulfilled from the chosen warehouse.
        // This "all-or-nothing" approach prevents partial stock deductions.
        foreach ($order->items as $item) {
            if (!$this->isStockAvailable($item->product_id, $item->quantity, $warehouseId)) {
                throw new Exception(
                    "Cannot fulfill Order #{$order->id}: Insufficient stock for Product ID {$item->product_id} in Warehouse ID {$warehouseId}."
                );
            }
        }

        // If validation passes, proceed with deducting the stock for each item.
        foreach ($order->items as $item) {
            // We can be confident the record exists because of the check above.
            $stockLevel = StockLevel::where('product_id', $item->product_id)
                                    ->where('warehouse_id', $warehouseId)
                                    ->first();

            // Use the atomic `decrement` operation for safety.
            $stockLevel->decrement('quantity', $item->quantity);

            Log::info(
                "Stock Removed: Product ID {$item->product_id} decreased by {$item->quantity} from Warehouse ID {$warehouseId}. " .
                "New quantity in warehouse: {$stockLevel->fresh()->quantity}. (For Order #{$order->id})"
            );
        }
    }

    /**
     * Checks if a required quantity of a product is available in a SPECIFIC warehouse.
     *
     * @param int $productId The ID of the product.
     * @param int $requiredQuantity The quantity needed.
     * @param int $warehouseId The ID of the warehouse to check.
     * @return bool True if stock is sufficient, false otherwise.
     */
    public function isStockAvailable(int $productId, int $requiredQuantity, int $warehouseId): bool
    {
        // Retrieve the stock quantity for the specific product in the specific warehouse.
        // `value()` is an efficient way to get a single column's value.
        $availableQuantity = StockLevel::where('product_id', $productId)
                                    ->where('warehouse_id', $warehouseId)
                                    ->value('quantity');

        // If the record doesn't exist, `value()` returns null. In this case, the available stock is 0.
        // We return true only if the available stock is greater than or equal to what's required.
        return ($availableQuantity ?? 0) >= $requiredQuantity;
    }

    /**
     * Gets the total stock quantity for a product across ALL warehouses.
     * Useful for displaying total available inventory to customers.
     *
     * @param int $productId The ID of the product.
     * @return int The total summed quantity across all warehouses.
     */
    public function getTotalStockForProduct(int $productId): int
    {
        // Use the `sum` aggregate function on the database for high performance.
        // This is much more efficient than fetching all records and summing in PHP.
        return StockLevel::where('product_id', $productId)->sum('quantity');
    }
}
