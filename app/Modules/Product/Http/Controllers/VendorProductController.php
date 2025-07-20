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
        // CRITICAL: Eager load the 'product' relationship to avoid N+1 query issues in the view
        $vendorProducts = $vendor->vendorProducts()->with('product')->paginate(20);

        return view('vendor.products.index', compact('vendorProducts', 'vendor'));
    }
/*
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
*/
    public function update(Request $request, VendorProduct $product)
    {
        // Authorization: Ensure the product being updated belongs to the logged-in vendor.
        if ($product->vendor_id !== Auth::user()->vendor->id) {
            abort(403, 'UNAUTHORIZED ACTION');
        }

        $request->validate([
            'retail_price' => 'required|numeric|min:0',
        ]);

        $product->update([
            'retail_price' => $request->retail_price,
        ]);

        //return back()->with('success', 'Price updated successfully!');
        return redirect()->route('vendor.products.index')->with('success', 'Retail prices updated.');
    }

    public function bulkUpdate(Request $request)
    {
        // Validation for an array of inputs
        $request->validate([
            'products' => 'required|array',
            'products.*.retail_price' => 'required|numeric|min:0', // The '*' is a wildcard
        ]);

        $vendor = Auth::user()->vendor;

        // Loop through the submitted product data
        foreach ($request->products as $vendorProductId => $data) {
            // Find the product, BUT ensure it belongs to the currently logged-in vendor.
            // This is a crucial security check.
            $vendorProduct = VendorProduct::where('id', $vendorProductId)
                                            ->where('vendor_id', $vendor->id)
                                            ->first();

            // If found, update it. If not, just skip it.
            if ($vendorProduct) {
                $vendorProduct->update([
                    'retail_price' => $data['retail_price']
                ]);
            }
        }

        return redirect()->back()->with('success', 'All prices have been updated successfully!');
    }

}
