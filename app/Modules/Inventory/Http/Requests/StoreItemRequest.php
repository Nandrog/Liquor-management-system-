<?php

namespace App\Modules\Inventory\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class StoreItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Here you can add authorization logic, e.g., check if the user is a Liquor Manager.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // Get the product ID from the route for update scenarios
        $productId = $this->route('item');

        return [
            'name' => ['required', 'string', 'max:255'],
            // The SKU must be unique, but we need to ignore the current item's SKU when updating
            'sku' => ['required', 'string', 'max:255', Rule::unique('products')->ignore($productId)],
            'description' => ['nullable', 'string'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'unit_of_measure' => ['required', 'string', 'max:50'],
            'reorder_level' => ['required', 'integer', 'min:0'],
            'type' => ['required', 'string', Rule::in(['finished_good', 'raw_material'])],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            
            // supplier_id is required only if the type is 'raw_material'
            'supplier_id' => ['nullable', 'required_if:type,raw_material', 'exists:users,id'],
            
            // vendor_id is required only if the type is 'finished_good'
            'vendor_id' => ['nullable', 'required_if:type,finished_good', 'exists:vendors,id'],
        ];
    }
}