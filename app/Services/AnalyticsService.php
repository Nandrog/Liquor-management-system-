<?php

namespace App\Services;

use App\Models\Customer;
use Carbon\Carbon;

class AnalyticsService
{
    public function segmentCustomers(): array
    {
        $now = Carbon::now();
        $segments = [];

        $customers = Customer::with('sales')->get();

        foreach ($customers as $customer) {
            $totalSpend = 0;
            $lastPurchase = null;
            $purchaseCount = 0;

            foreach ($customer->sales as $sale) {
                $totalSpend += $sale->total_price;
                $purchaseCount++;
                if (!$lastPurchase || $sale->sale_date > $lastPurchase) {
                    $lastPurchase = $sale->sale_date;
                }
            }

            if ($purchaseCount === 0) {
                $segment = 'Inactive';
            } else {
                $daysSinceLast = Carbon::parse($lastPurchase)->diffInDays($now);

                if ($totalSpend > 1000 && $purchaseCount > 10 && $daysSinceLast < 30) {
                    $segment = 'High Value';
                } elseif ($daysSinceLast > 60) {
                    $segment = 'At Risk';
                } elseif ($purchaseCount <= 3) {
                    $segment = 'Low Engagement';
                } else {
                    $segment = 'Regular';
                }
            }

            $segments[] = [
                'customer' => $customer,
                'total_spend' => $totalSpend,
                'purchase_count' => $purchaseCount,
                'last_purchase' => $lastPurchase,
                'segment' => $segment,
            ];
        }

        return $segments;
    }
}
