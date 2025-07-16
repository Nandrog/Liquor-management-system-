<?php

namespace App\Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PoSupplierMgtController extends Controller
{
    public function index()
    {
        // 1. Start the query from the User model.
        // 2. Use the `role()` method from the Spatie package to get only suppliers.
        $suppliers = User::role('Supplier')
            ->with([
                // 3. Eager load the new 'suppliedPurchases' relationship on the User model.
                'suppliedPurchases' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                },
                'suppliedPurchases.items.product',
                'suppliedPurchases.warehouse'
            ])
            ->get();

        return view('officer.supplier.overview', [
            'suppliers' => $suppliers,
        ]);
    }
}
