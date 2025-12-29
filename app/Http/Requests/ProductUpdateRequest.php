<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    public function authorize(): bool
{
    // Only admin users can update products
    return $this->user()?->role === 'Sales Manager';
}

public function rules(): array
{
    // Get the product ID from route parameter
    $productId = $this->route('id');

    return [
        'sku' => 'sometimes|required|string|unique:products,sku,' . $productId,
        'name' => 'sometimes|required|string',
        'category_id' => 'sometimes|required|exists:categories,id',
        'tax_rate' => 'sometimes|required|numeric',
        'unit' => 'sometimes|required|string',
        'packaging' => 'sometimes|nullable|string',
        'min_order_quantity' => 'sometimes|required|integer',
        'reorder_level' => 'sometimes|required|integer',
        'price' => 'sometimes|required|numeric',
    ];
}
}
