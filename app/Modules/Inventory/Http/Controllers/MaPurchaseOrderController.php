<?php

namespace App\Modules\Inventory\Http\Controllers;

use App\Enums\OrderType;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class MaPurchaseOrderController extends Controller
{
 public function index()
    {
        // 1. Fetch all orders of the correct type.
        $orders = Order::where('type', OrderType::SUPPLIER_ORDER)
            // 2. Eager load the supplier (which is a User) and the items (with their products).
            ->with(['recipientSupplier', 'items.product'])
            ->latest() // Show the most recent orders first
            ->paginate(15); // Paginate the results

        // 3. Return the view, passing the orders data to it.
        return view('manager.orders.purchase_orders', [
            'orders' => $orders,
        ]);
    }   
}
