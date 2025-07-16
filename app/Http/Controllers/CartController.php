<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display the contents of the shopping cart.
     */
    public function index()
    {
        // Retrieve the cart from the session.
        $cart = session()->get('cart', []);

        // Return a view and pass the cart data to it.
        // We will create this 'cart.index' view later.
        return view('cart.index', compact('cart'));
    }

    /**
     * Add a product to the shopping cart.
     * This is the method that will handle the "Add to Cart" button clicks.
     */
    public function add(Request $request)
    {
        // Validate that a product_id was submitted.
        $request->validate(['product_id' => 'required|exists:products,id']);

        $productId = $request->product_id;
        $product = Product::findOrFail($productId);

        // Get the current cart from the session, or create an empty array if it doesn't exist.
        $cart = session()->get('cart', []);

        // Check if the product is already in the cart.
        if (isset($cart[$productId])) {
            // If it is, just increment the quantity.
            $cart[$productId]['quantity']++;
        } else {
            // If it's not, add it to the cart with a quantity of 1.
            $cart[$productId] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->unit_price,
                "image_url" => $product->image_url // Assumes the accessor exists on your Product model
            ];
        }

        // Store the updated cart back into the session.
        session()->put('cart', $cart);

        // Redirect back to the previous page (the liquor shelf) with a success message.
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    /**
     * Update the quantity of a specific item in the cart.
     */
    public function update(Request $request)
    {
        // We expect 'quantities' to be an array of [product_id => new_quantity]
        $request->validate(['quantities' => 'required|array']);

        $cart = session()->get('cart', []);

        foreach ($request->quantities as $productId => $quantity) {
            // If the quantity is 0 or less, remove the item. Otherwise, update it.
            if (isset($cart[$productId])) {
                if ($quantity > 0) {
                    $cart[$productId]['quantity'] = $quantity;
                } else {
                    unset($cart[$productId]);
                }
            }
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully!');
    }

    /**
     * Remove a specific item from the cart.
     */
    public function remove(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $cart = session()->get('cart', []);

        if (isset($cart[$request->product_id])) {
            unset($cart[$request->product_id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Product removed from cart successfully!');
    }
}
