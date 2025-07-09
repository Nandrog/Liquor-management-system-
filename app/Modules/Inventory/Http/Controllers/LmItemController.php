<?php

namespace App\Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Category;
use App\Models\User;
use App\Models\Vendor;
use App\Modules\Inventory\Http\Requests\StoreItemRequest;

class LmItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         // 1. Eager load relationships to prevent N+1 query problems.
        // We get the supplier's user info and the stock levels for each product.
        $products = Product::with(['supplier', 'vendor.user', 'stockLevels'])->get();

        // 2. Separate the collection into two groups based on the 'type' attribute.
        $groupedProducts = $products->groupBy('type');
        $finishedGoods = $groupedProducts->get('finished_good', collect());
        $rawMaterials = $groupedProducts->get('raw_material', collect());

        // 3. Calculate total inventory value for each type.
        $finishedGoodsValue = $finishedGoods->reduce(function ($carry, $product) {
            // Sum up the quantity from all warehouses for this product
            $totalQuantity = $product->stockLevels->sum('quantity');
            return $carry + ($totalQuantity * $product->unit_price);
        }, 0);

        $rawMaterialsValue = $rawMaterials->reduce(function ($carry, $product) {
            $totalQuantity = $product->stockLevels->sum('quantity');
            return $carry + ($totalQuantity * $product->unit_price);
        }, 0);

        // 4. Pass all the data to the view.
        return view('manager.items.index', [
            'finishedGoods' => $finishedGoods,
            'rawMaterials' => $rawMaterials,
            'finishedGoodsValue' => $finishedGoodsValue,
            'rawMaterialsValue' => $rawMaterialsValue,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
            $data = [
        'categories' => Category::all(),
        'suppliers' => User::role('Supplier')->get(),
        'vendors' => Vendor::with('user')->get(),
         'units_of_measure' => config('inventory.units_of_measure'), 
    ];
    
    return view('manager.items.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemRequest $request)
    {
            // The request is already validated by the StoreItemRequest class.
    // We can directly access the validated data.
    Product::create($request->validated());

    return redirect()->route('manager.items.index')
                     ->with('success', 'Item created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $item )
    {
            // Fetch data needed for form dropdowns
    $data = [
        'item' => $item, // The product to edit, automatically fetched by Laravel
        'categories' => Category::all(),
        'suppliers' => User::role('Supplier')->get(),
        'vendors' => Vendor::with('user')->get(),
        'units_of_measure' => config('inventory.units_of_measure'), 
    ];

    return view('manager.items.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreItemRequest $request, Product $item)
    {
            // The request is already validated.
    // The $item is already fetched via route-model binding.
    $item->update($request->validated());

    return redirect()->route('manager.items.index')
                     ->with('success', 'Item updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $item)
    {
            // Optional: Add authorization check here with a Policy
    // $this->authorize('delete', $item);

    $item->delete();

    return redirect()->route('manager.items.index')
                     ->with('success', 'Item deleted successfully!');
    }
}
