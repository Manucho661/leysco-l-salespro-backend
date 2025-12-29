<?php

namespace App\Services\Leys;

use App\Models\Inventory;
use App\Models\Product;
use App\Jobs\SendLowStockAlertJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class InventoryService
{
    protected string $cacheKeyPrefix = 'inventory.product.';

    // Existing methods...
    
    /**
     * Reserve stock in a warehouse
     */
    public function reserveStock(Product $product, $warehouse, int $qty): void
    {
        DB::transaction(function () use ($product, $warehouse, $qty) {
            $row = Inventory::lockForUpdate()
                ->firstOrCreate(
                    ['product_id' => $product->id, 'warehouse_id' => $warehouse->id],
                    ['quantity' => 0, 'reserved_quantity' => 0]
                );

            $available = $row->quantity - $row->reserved_quantity;

            if ($available < $qty) {
                throw new \RuntimeException("Not enough stock to reserve in this warehouse");
            }

            $row->reserved_quantity += $qty;
            $row->save();

            $this->invalidateStockCache($product->id);
        });
    }

    /**
     * Release reserved stock
     */
    public function releaseReserved(Product $product, $warehouse, int $qty): void
    {
        DB::transaction(function () use ($product, $warehouse, $qty) {
            $row = Inventory::lockForUpdate()
                ->where('product_id', $product->id)
                ->where('warehouse_id', $warehouse->id)
                ->first();

            if (! $row || $row->reserved_quantity < $qty) {
                throw new \RuntimeException("Cannot release more than reserved");
            }

            $row->reserved_quantity -= $qty;
            $row->save();

            $this->invalidateStockCache($product->id);
        });
    }

    /**
     * Commit reserved stock (deduct from available & reserved)
     */
    public function commitStock(Product $product, $warehouse, int $qty): void
    {
        DB::transaction(function () use ($product, $warehouse, $qty) {
            $row = Inventory::lockForUpdate()
                ->where('product_id', $product->id)
                ->where('warehouse_id', $warehouse->id)
                ->first();

            if (! $row || $row->reserved_quantity < $qty) {
                throw new \RuntimeException("Cannot commit more than reserved");
            }

            $row->reserved_quantity -= $qty;
            $row->quantity -= $qty;
            $row->save();

            $this->invalidateStockCache($product->id);
        });
    }
}
