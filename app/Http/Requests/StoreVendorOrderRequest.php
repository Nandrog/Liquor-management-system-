<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreVendorOrderRequest extends FormRequest {
    public function authorize() { return true; }
    public function rules() {
        return [
            'products' => 'required|array|min:1',
            'products.*.quantity' => 'required|integer|min:1',
        ];
    }
}