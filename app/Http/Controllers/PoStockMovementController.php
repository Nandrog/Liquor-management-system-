<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockMovement;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WorkDistribution\Employee;

class PoStockMovementController extends Controller
{
    // ✅ Show the form
    public function create()
    {
        $products = Product::all();
        $warehouses = Warehouse::all();
        $employees = Employee::all();

        return view('inventory.create-stock-movement', compact('products', 'warehouses', 'employees'));
    }

    // ✅ Handle the POST
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|integer|min:1',
            'moved_at' => 'required|date',
            'notes' => 'nullable|string',
            'employee_id' => 'required|exists:employees,id',
        ]);

        StockMovement::create([
            'product_id' => $request->product_id,
            'from_warehouse_id' => $request->from_warehouse_id,
            'to_warehouse_id' => $request->to_warehouse_id,
            'quantity' => $request->quantity,
            'moved_at' => $request->moved_at,
            'notes' => $request->notes,
            'employee_id' => $request->employee_id, // ✅ save who moved it
        ]);

        return redirect()->route('stock_movements.create')->with('success', 'Stock moved successfully!');
    }
}