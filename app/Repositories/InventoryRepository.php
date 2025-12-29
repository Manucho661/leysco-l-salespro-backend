<?php

namespace App\Repositories;

use App\Models\Inventory;

class InventoryRepository
{
    public function findByProductAndWarehouse(int $productId, int $warehouseId)
    {
        return Inventory::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->first();
    }

    public function lockForUpdate(int $productId, int $warehouseId)
    {
        return Inventory::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->lockForUpdate()
            ->first();
    }

    public function updateStock(Inventory $inventory, int $available, int $reserved): void
    {
        $inventory->update([
            'quantity' => $available,
            'quantity' => $reserved,
        ]);
    }
}
