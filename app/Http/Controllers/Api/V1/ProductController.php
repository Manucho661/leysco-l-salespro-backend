<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;
use App\Services\Leys\InventoryService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(protected InventoryService $inventoryService)
    {
        $this->middleware('auth:sanctum');
        $this->middleware('role:Sales Manager')->only(['store', 'update', 'destroy']);
    }

    public function index(): JsonResponse
    {
        $products = Product::paginate(15);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    public function store(ProductStoreRequest $request): JsonResponse
    {
        $product = Product::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $product
        ], 201);
    }

    public function update(ProductUpdateRequest $request, Product $product): JsonResponse
    {
        $product->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => $product
        ]);
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ], 204);
    }
}
