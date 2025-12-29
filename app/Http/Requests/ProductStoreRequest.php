<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only admin users can create products
        return $this->user()?->role === 'Sales Manager';
    }

    public function rules(): array
    {
        return [
            'sku' => 'required|string|unique:products,sku|max:50',
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'subcategory' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'packaging' => 'nullable|string|max:100',
            'min_order_quantity' => 'required|integer|min:1',
            'reorder_level' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'sku.unique' => 'This SKU already exists.',
            'price.min' => 'Price must be at least 0.',
            'min_order_quantity.min' => 'Minimum order quantity must be at least 1.',
        ];
    }
}
