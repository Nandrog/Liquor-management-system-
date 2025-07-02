<?php

namespace App\Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PoSupplierMgtController extends Controller
{
    public function index()
    {
        // 1. Fetch all suppliers.
        // 2. Eager load their 'purchases' relationship.
        // 3. For each purchase, eager load its 'items' (with the 'product') and 'warehouse'.
       /* $suppliers = Supplier::with([
            'purchases' => function ($query) {
                // Order purchases by most recent first for each supplier
                $query->orderBy('purchase_date', 'desc');
            },
            'purchases.items.product', // Nested eager loading
            'purchases.warehouse'      // Eager load the warehouse for each purchase
        ])->get();*/

        // 3. We can also calculate the total units supplied per supplier for a summary.
        //    This is a more complex query, so for now, we'll calculate it in the view,
        //    but for performance on large datasets, a dedicated query would be better.

        return view('officer.supplier.overview', [
           // 'suppliers' => $suppliers,
        ]);
    }
}
