<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\VendorProduct;
use App\Models\StockMovement;
use App\Enums\OrderType;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Exception;

class ProcurementController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $orders = Order::where('type', OrderType::VENDOR_ORDER->value) // Comparing to the enum's value
                      ->latest()
                      ->paginate(10);
        return view('procurement.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Your authorization and loading logic is good.
        $this->authorize('view', $order);
        $order->load('items.product.stockLevels', 'vendor'); // Also load vendor for notes
        return view('procurement.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        // Authorization check is good.
        $this->authorize('update', $order);

        // You only need to validate once.
        $validated = $request->validate([
            'status' => 'required|string|in:confirmed,rejected',
        ]);

        // With Enum casting on the model, this comparison is now 100% reliable.
        if ($order->status !== OrderStatus::PENDING) {
            return back()->with('error', 'This order has already been processed.');
        }

        // --- LOGIC FOR REJECTING AN ORDER ---
        if ($validated['status'] === 'rejected') {
            $order->update(['status' => OrderStatus::REJECTED]);
            return redirect()->route('procurement.orders.index')->with('success', "Order #{$order->id} has been rejected.");
        }

        // --- LOGIC FOR CONFIRMING AN ORDER ---
        if ($validated['status'] === 'confirmed') {
            try {
                DB::beginTransaction();

                if (is_null($order->vendor_id) || is_null($order->vendor)) {
                    throw new Exception("Order #{$order->id} does not have a valid associated vendor.");
                }

                foreach ($order->items as $item) {
                    $product = $item->product;
                    $currentStock = $product->stockLevels->first()->quantity ?? 0;

                    if ($currentStock < $item->quantity) {
                        throw new Exception("Not enough stock for {$product->name}. Required: {$item->quantity}, Available: {$currentStock}.");
                    }

                    // Set the source warehouse ID, e.g., from the product's default warehouse or a fixed value
                    $sourceWarehouseId = $product->stockLevels->first()->warehouse_id ?? null;
                    if (is_null($sourceWarehouseId)) {
                        throw new Exception("No warehouse found for product {$product->name}.");
                    }

                    StockMovement::create([
                        'product_id' => $product->id,
                        'quantity' => -$item->quantity,
                        'type' => 'vendor_order_fulfillment',
                        'order_id' => $order->id,
                        'notes' => "Fulfilled order for vendor: {$order->vendor->company_name}",
                        'from_warehouse_id' => $sourceWarehouseId,
                    ]);

                    VendorProduct::updateOrCreate(
                        ['vendor_id' => $order->vendor_id, 'product_id' => $product->id],
                        [] // This is correct, no update data needed, just ensure it exists.
                    );
                }

                $order->update(['status' => OrderStatus::CONFIRMED]);
                DB::commit();
                return redirect()->route('procurement.orders.index')->with('success', "Order #{$order->id} confirmed. Stock deducted and products assigned.");

            } catch (Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Failed to confirm order: ' . $e->getMessage());
            }
        }

        // Fallback in case status is neither 'confirmed' nor 'rejected'
        return back()->with('error', 'Invalid action specified.');
    }
}
