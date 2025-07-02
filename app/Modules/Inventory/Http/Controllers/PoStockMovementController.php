<?php

namespace App\Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockLevel;
use App\Models\StockMovement;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class PoStockMovementController extends Controller
{
     /**
     * Display the stock movement page with warehouse summaries and movement history.
     */
    public function index()
    {
        // Eager load stock levels and products to calculate total quantity efficiently
        $warehouses = Warehouse::with('stockLevels.product')->get();

        // Fetch recent stock movements, eager loading related data
        $movements = StockMovement::with(['product', 'fromWarehouse', 'toWarehouse'])
            ->latest('moved_at')
            ->paginate(10);
            
        // Fetch data for the transfer form dropdowns
        $products = Product::orderBy('name')->get();

        return view('officer.stock_movements.index', [
            'warehouses' => $warehouses,
            'movements' => $movements,
            'products' => $products,
        ]);
    }

    /**
     * Store a new stock movement (transfer).
     */
    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'from_warehouse_id' => ['required', 'integer', 'exists:warehouses,id'],
            'to_warehouse_id' => ['required', 'integer', 'exists:warehouses,id', 'different:from_warehouse_id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
        ]);

        $productId = $request->product_id;
        $fromWarehouseId = $request->from_warehouse_id;
        $toWarehouseId = $request->to_warehouse_id;
        $quantityToMove = $request->quantity;

        // 2. Use a Database Transaction
        // This ensures that all database operations succeed or none of them do.
        // It prevents a situation where stock is removed from one warehouse but never added to the other.
        try {
            DB::transaction(function () use ($productId, $fromWarehouseId, $toWarehouseId, $quantityToMove, $request) {
                // 3. Decrement stock from the source warehouse
                $fromStock = StockLevel::where('product_id', $productId)
                                       ->where('warehouse_id', $fromWarehouseId)
                                       ->firstOrFail(); // Fails if the product doesn't exist at the source

                if ($fromStock->quantity < $quantityToMove) {
                    // Not enough stock to move, throw an exception to roll back the transaction
                    throw new \Exception('Not enough stock in the source warehouse to complete the transfer.');
                }

                $fromStock->decrement('quantity', $quantityToMove);

                // 4. Increment stock in the destination warehouse
                // Use firstOrCreate: if the product doesn't exist in the destination, create a new stock level record.
                $toStock = StockLevel::firstOrCreate(
                    ['product_id' => $productId, 'warehouse_id' => $toWarehouseId],
                    ['quantity' => 0] // Default to 0 if creating for the first time
                );
                $toStock->increment('quantity', $quantityToMove);

                // 5. Create a record of the movement
                StockMovement::create([
                    'product_id' => $productId,
                    'from_warehouse_id' => $fromWarehouseId,
                    'to_warehouse_id' => $toWarehouseId,
                    'quantity' => $quantityToMove,
                    'moved_at' => now(),
                    'notes' => $request->notes,
                ]);
            });
        } catch (\Exception $e) {
            // If anything went wrong (like not enough stock), redirect back with an error.
            return back()->with('error', $e->getMessage());
        }

        // 6. Redirect back with a success message
        return redirect()->route('officer.stock_movements.index')
                         ->with('success', 'Stock transferred successfully!');
    }
}
