<?php

namespace App\Modules\Inventory\Http\Controllers\Finance;

use App\Enums\OrderType;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
class OrderReportController extends Controller
{
     public function supplierOrders()
    {
        // 1. Fetch all supplier orders with their necessary relationships.
        $allOrders = Order::where('type', OrderType::SUPPLIER_ORDER)
            ->with(['supplier', 'items.product']) // Eager load for display
            ->latest()
            ->get();

        // 2. Group the entire collection by the 'status' enum value.
        // This creates a collection where keys are 'pending', 'confirmed', etc.
        $ordersByStatus = $allOrders->groupBy('status.value');

        // 3. Calculate the total monetary value for each status group.
        $statusTotals = $ordersByStatus->map(function (Collection $ordersInGroup) {
            return $ordersInGroup->sum('total_amount');
        });

        // 4. Pass both the grouped orders and the calculated totals to the view.
        return view('finance.orders.supplier_report', [
            'ordersByStatus' => $ordersByStatus,
            'statusTotals' => $statusTotals,
        ]);
    }
}
