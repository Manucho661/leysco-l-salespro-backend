<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Leys\LeysInventoryService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    protected LeysInventoryService $inventoryService;

    public function __construct(LeysInventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function reserve(Request $request)
    {
        $validated = $request->validate([
            'product_id'   => 'required|integer|exists:products,id',
            'warehouse_id' => 'required|integer|exists:warehouses,id',
            'quantity'     => 'required|integer|min:1',
        ]);

        $reservation = $this->inventoryService->reserveStock(
            $validated['product_id'],
            $validated['warehouse_id'],
            $validated['quantity']
        );

        return response()->json([
            'message' => 'Stock reserved successfully',
            'data' => $reservation
        ]);
    }
}
