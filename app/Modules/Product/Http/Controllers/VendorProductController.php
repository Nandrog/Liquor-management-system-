<?php
namespace App\Modules\Product\Http\Controllers;

use App\Models\Vendor;
use App\Models\VendorProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class VendorProductController extends Controller
{
    public function index()
    {
        $vendor = Auth::user()->vendor;
        // Get all products the vendor has successfully ordered
        $productIds = $vendor->orders()
            ->where('status', \App\Enums\OrderStatus::CONFIRMED)
            ->with('items')
            ->get()
            ->pluck('items.*.product_id')
            ->flatten()
            ->unique();
            
        $vendorProducts = \App\Models\Product::whereIn('id', $productIds)
            ->with(['vendorProducts' => fn($q) => $q->where('vendor_id', $vendor->id)])
            ->get();

        return view('vendor.products.index', compact('vendorProducts', 'vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        // This controller action is simplified. A better approach would be a dedicated Form Request.
        $request->validate([
            'products' => 'required|array',
            'products.*.retail_price' => 'required|numeric|min:0',
        ]);

        foreach ($request->products as $productId => $data) {
            VendorProduct::updateOrCreate(
                ['vendor_id' => Auth::user()->vendor_id, 'product_id' => $productId],
                ['retail_price' => $data['retail_price']]
            );
        }

        return redirect()->route('vendor.products.index')->with('success', 'Retail prices updated.');
    }
}