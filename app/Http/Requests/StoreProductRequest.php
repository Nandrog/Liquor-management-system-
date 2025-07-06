<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\ProductType;
use Illuminate\Validation\Rules\Enum;

class StoreProductRequest extends FormRequest {
    public function authorize() { return true; }
    public function rules() {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'type' => ['required', new Enum(ProductType::class)],
            'category_id' => 'required|exists:categories,id',
        ];
    }
}