<?php

namespace App\Modules\Inventory\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class FiOrderReportController extends Controller
{
    /**
     * Display a financial report of all sales orders (customer and vendor).
     */
    public function salesOrders()
    {
        // 1. Fetch all sales-related orders with their relationships.
        $allSalesOrders = Order::whereIn('type', [OrderType::VENDOR_ORDER, OrderType::CUSTOMER_ORDER])
            ->with(['customer.user', 'vendor']) // Eager load for display
            ->latest()
            ->get();

        // 2. Separate the orders into two main groups: by Vendor and by Customer.
        $groupedByType = $allSalesOrders->groupBy('type.value');
        $vendorOrders = $groupedByType->get(OrderType::VENDOR_ORDER->value, collect());
        $customerOrders = $groupedByType->get(OrderType::CUSTOMER_ORDER->value, collect());

        // 3. For each group, further group the orders by their status.
        $vendorOrdersByStatus = $vendorOrders->groupBy('status.value');
        $customerOrdersByStatus = $customerOrders->groupBy('status.value');
        
        // 4. Calculate the total value of ONLY the 'paid' orders for each group.
        $vendorPaidTotal = $vendorOrders
            ->where('status', OrderStatus::PAID)
            ->sum('total_amount');

        $customerPaidTotal = $customerOrders
            ->where('status', OrderStatus::PAID)
            ->sum('total_amount');
            
        // 5. Calculate the grand total of all paid sales.
        $grandTotalSales = $vendorPaidTotal + $customerPaidTotal;

        // 6. Pass all the structured data to the view.
        return view('finance.orders.sales_report', [
            'vendorOrdersByStatus' => $vendorOrdersByStatus,
            'customerOrdersByStatus' => $customerOrdersByStatus,
            'vendorPaidTotal' => $vendorPaidTotal,
            'customerPaidTotal' => $customerPaidTotal,
            'grandTotalSales' => $grandTotalSales,
        ]);
    }
}
