<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\StockMovement;
use App\Models\StockLevel;
use App\Enums\OrderType;
use App\Enums\OrderStatus;

class OrderObserver
{
    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Ensure the status was actually changed to 'confirmed'
        if ($order->wasChanged('status') && $order->status === OrderStatus::CONFIRMED) {
            
            // Logic for a VENDOR order being confirmed by Procurement
            if ($order->type === OrderType::VENDOR_ORDER) {
                foreach ($order->items as $item) {
                    // Deduct stock for finished good
                    StockMovement::create([
                        'product_id' => $item->product_id,
                        'warehouse_id' => 1, // Assuming a default warehouse
                        'quantity' => -$item->quantity, // Negative for deduction
                        'reason' => 'Vendor order confirmed: #' . $order->id,
                    ]);
                    
                    // Update main stock level
                    $stock = StockLevel::firstOrCreate(['product_id' => $item->product_id, 'warehouse_id' => 1]);
                    $stock->decrement('quantity', $item->quantity);
                }
            }
            
            // Logic for a SUPPLIER order being accepted by Manufacturer
            if ($order->type === OrderType::SUPPLIER_ORDER) {
                 foreach ($order->items as $item) {
                    // Increase stock for raw material
                    StockMovement::create([
                        'product_id' => $item->product_id,
                        'warehouse_id' => 1, // Assuming a default raw material warehouse
                        'quantity' => $item->quantity, // Positive for addition
                        'reason' => 'Supplier order accepted: #' . $order->id,
                    ]);
                    
                    // Update main stock level
                    $stock = StockLevel::firstOrCreate(['product_id' => $item->product_id, 'warehouse_id' => 1]);
                    $stock->increment('quantity', $item->quantity);
                }
            }
        }
    }
}