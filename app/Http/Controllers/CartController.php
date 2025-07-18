<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Required for DB::raw()
use App\Models\Cart;
use App\Models\Product;

use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function index()
    {
        // Get the current user's ID
        $userId = Auth::id();

        // Fetch cart items for the user from the database.
        // We use 'with('product')' to eager load the product details for each cart item.
        // This is much more efficient than fetching the product for each item in a loop (avoids N+1 problem).
        $cartItems = Cart::where('user_id', $userId)->with('product')->get();

        // Calculate the subtotal.
        $subtotal = $cartItems->sum(function ($item) {
            // Ensure product and price exist to avoid errors
            return optional($item->product)->unit_price * $item->quantity;
        });

        // The view name 'cart.index' should correspond to a file at:
        // resources/views/cart/index.blade.php
        return view('cart.index', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal
        ]);
    }

    /**
     * Add a product to the cart using an AJAX request.
     * This method is called by the 'fetch' function on your storefront page.
     */
    public function add(Request $request)
    {
        // 1. Validate the incoming request.
        $request->validate(['product_id' => 'required|exists:products,id']);

        $userId = Auth::id();
        $productId = $request->product_id;

        // 2. Find an existing cart item for this user and product.
        $cartItem = Cart::where('user_id', $userId)->where('product_id', $productId)->first();

        if ($cartItem) {
            // If the item already exists, just increment the quantity.
            $cartItem->increment('quantity');
        } else {
            // If it's a new item, create it with quantity 1.
            Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => 1,
            ]);
        }

        // 3. Calculate the new total number of items in the user's cart for the header icon.
        $cartTotal = Cart::where('user_id', $userId)->sum('quantity');

        // 4. Return a successful JSON response for the AJAX call.
        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart_total' => $cartTotal
        ]);
    }

    /**
     * Update the quantities of items in the cart.
     * This is called when the "Update Cart" button is submitted from the cart page.
     */
    public function update(Request $request)
    {
        // Validate that we received an array of quantities.
        $request->validate(['quantities' => 'required|array']);

        $userId = Auth::id();

        foreach ($request->quantities as $cartId => $quantity) {
            // Ensure the quantity is a positive integer.
            $quantity = max(0, (int)$quantity);

            // Find the cart item that belongs to the current user. This is a crucial security check.
            $cartItem = Cart::where('id', $cartId)->where('user_id', $userId)->first();

            if ($cartItem) {
                if ($quantity > 0) {
                    // If quantity is positive, update it.
                    $cartItem->update(['quantity' => $quantity]);
                } else {
                    // If quantity is 0, remove the item from the cart.
                    $cartItem->delete();
                }
            }
        }

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully!');
    }

    /**
     * Remove an item completely from the cart.
     * This method expects the cart item ID, not the product ID.
     */
    public function remove(Request $request)
    {
        // Validate that a cart_id was sent.
        $request->validate(['cart_id' => 'required|integer|exists:carts,id']);

        $userId = Auth::id();
        $cartId = $request->cart_id;

        // Find the cart item, ensuring it belongs to the currently logged-in user
        // before deleting it. This prevents one user from deleting another user's cart item.
        $cartItem = Cart::where('id', $cartId)->where('user_id', $userId)->first();

        if ($cartItem) {
            $cartItem->delete();
            return redirect()->route('cart.index')->with('success', 'Product removed from cart successfully!');
        }

        // If the item wasn't found (or didn't belong to the user), redirect with an error.
        return redirect()->route('cart.index')->with('error', 'Item not found in cart.');
    }
}
