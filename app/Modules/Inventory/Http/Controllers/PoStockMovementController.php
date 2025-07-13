<?php

namespace App\Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockLevel;
use App\Models\StockMovement;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PoStockMovementController extends Controller
{
    /**
     * Display the stock movement page with warehouse summaries and movement history.
     */
    public function index()
    {
        // BEST PRACTICE: Use withSum for a much more efficient query than your previous version.
        $warehouses = Warehouse::withSum('stockLevels', 'quantity')->orderBy('name')->get();

        $movements = StockMovement::with(['product', 'fromWarehouse', 'toWarehouse'])
            ->latest('moved_at')
            ->paginate(10);
            
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
        // 1. Validation - Corrected to use the 'warehouse_id' column for the 'exists' rule.
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'from_warehouse_id' => ['required', 'integer', 'exists:warehouses,warehouse_id'],
            'to_warehouse_id' => ['required', 'integer', 'exists:warehouses,warehouse_id', 'different:from_warehouse_id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
        ]);

        $quantityToMove = $validated['quantity'];

        try {
            DB::transaction(function () use ($validated, $quantityToMove) {
                // 3. Decrement stock from the source warehouse
                $fromStock = StockLevel::where('product_id', $validated['product_id'])
                                       ->where('warehouse_id', $validated['from_warehouse_id'])
                                       ->first(); // Use first() instead of firstOrFail() for a custom error message

                // Custom check for stock levels
                if (!$fromStock || $fromStock->quantity < $quantityToMove) {
                    throw ValidationException::withMessages([
                        'quantity' => 'Not enough stock in the source warehouse to complete the transfer.'
                    ]);
                }

                $fromStock->decrement('quantity', $quantityToMove);

                // 4. Increment stock in the destination warehouse
                $toStock = StockLevel::firstOrCreate(
                    ['product_id' => $validated['product_id'], 'warehouse_id' => $validated['to_warehouse_id']],
                    ['quantity' => 0]
                );
                $toStock->increment('quantity', $quantityToMove);

                // 5. Create a record of the movement
                StockMovement::create([
                    'product_id' => $validated['product_id'],
                    'from_warehouse_id' => $validated['from_warehouse_id'],
                    'to_warehouse_id' => $validated['to_warehouse_id'],
                    'quantity' => $quantityToMove,
                    'moved_at' => now(),
                    'notes' => $validated['notes'],
                ]);
            });
        } catch (ValidationException $e) {
            // If our custom validation exception was thrown, redirect back with its specific errors.
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Catch any other generic exceptions.
            return back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }

        return redirect()->route('officer.stock_movements.index')
                         ->with('success', 'Stock transferred successfully!');
    }
}
