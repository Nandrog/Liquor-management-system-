<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use App\Enums\OrderType;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    public function view(User $user, Order $order): bool
    {
        // Supplier can view their own supplier_order
        if ($user->hasRole('Supplier') && $order->type === OrderType::SUPPLIER_ORDER) {
            return $user->supplier_id === $order->supplier_id;
        }

        // Manufacturer can view any supplier_order
        if ($user->hasRole('Manufacturer') && $order->type === OrderType::SUPPLIER_ORDER) {
            return true;
        }

        // Vendor can view their own vendor_order
        if ($user->hasRole('Vendor') && $order->type === OrderType::VENDOR_ORDER) {
            return $user->vendor_id === $order->vendor_id;
        }

        // Procurement can view any vendor_order
        if ($user->hasRole('Procurement Officer') && $order->type === OrderType::VENDOR_ORDER) {
            return true;
        }
        
        // Customer can view their own orders
        if ($user->hasRole('Customer') && $order->customer_id === $user->customer->id) {
            return true;
        }

        return false;
    }

    public function update(User $user, Order $order): bool
    {
        // Manufacturer can update a supplier_order
        if ($user->hasRole('Manufacturer') && $order->type === OrderType::SUPPLIER_ORDER) {
            return true;
        }

        // Procurement Officer can update a vendor_order
        if ($user->hasRole('Procurement Officer') && $order->type === OrderType::VENDOR_ORDER) {
            return true;
        }

        return false;
    }
}