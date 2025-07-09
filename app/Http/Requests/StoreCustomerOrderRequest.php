<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCustomerOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only authenticated users who are customers can create orders.
        return Auth::check() && Auth::user()->hasRole('Customer');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'vendor_id' => 'required|exists:vendors,id',
            'products' => 'required|array|min:1',
            // Ensure the product ID sent exists in the vendor_products table for that vendor.
            'products.*' => [
                'required',
                function ($attribute, $value, $fail) {
                    $productId = str_replace(['products.', '.quantity'], '', $attribute);
                    $exists = \App\Models\VendorProduct::where('vendor_id', $this->vendor_id)
                        ->where('product_id', $productId)
                        ->exists();
                    if (!$exists) {
                        $fail("The selected product is not sold by this vendor.");
                    }
                },
            ],
            'products.*.quantity' => 'nullable|integer|min:0',
        ];
    }
}