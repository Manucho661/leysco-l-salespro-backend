<?php

namespace App\Services\Leys;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Jobs\SendLowStockAlertJob;

class InventoryService
{
    protected string $cacheKeyPrefix = 'inventory.product.';

    /**
     * Get total stock across all warehouses (cached)
     */
    public function getTotalStock(Product $product): int
    {
        $cacheKey = $this->cacheKeyPrefix . $product->id . '.total';

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($product) {
            return Inventory::where('product_id', $product->id)
                ->sum('quantity');
        });
    }

    /**
     * Get stock breakdown per warehouse
     */
    public function getStockByWarehouse(Product $product)
    {
        return Inventory::with('warehouse')
            ->where('product_id', $product->id)
            ->get()
            ->map(function ($row) {
                return [
                    'warehouse_id'   => $row->warehouse_id,
                    'warehouse_name' => $row->warehouse->name ?? null,
                    'quantity'       => (int) $row->quantity,
                    'reserved'       => (int) $row->reserved_quantity,
                    'available'      => (int) max(0, $row->quantity - $row->reserved_quantity)
                ];
            });
    }

    /**
     * Compute available stock across warehouses
     */
    public function getAvailableStock(Product $product): int
    {
        return Inventory::where('product_id', $product->id)
            ->sum(DB::raw('quantity - reserved_quantity'));
    }

    /**
     * Increase stock for a product in a warehouse
     */
    public function addStock(int $productId, int $warehouseId, int $qty): void
    {
        DB::transaction(function () use ($productId, $warehouseId, $qty) {
            $row = Inventory::lockForUpdate()
                ->firstOrCreate(
                    ['product_id' => $productId, 'warehouse_id' => $warehouseId],
                    ['quantity' => 0, 'reserved_quantity' => 0]
                );

            $row->quantity += $qty;
            $row->save();
        });

        $this->invalidateStockCache($productId);
    }

    /**
     * Decrease stock safely
     */
    public function deductStock(int $productId, int $warehouseId, int $qty): bool
    {
        return DB::transaction(function () use ($productId, $warehouseId, $qty) {
            $row = Inventory::lockForUpdate()
                ->where('product_id', $productId)
                ->where('warehouse_id', $warehouseId)
                ->first();

            if (! $row) {
                return false;
            }

            $available = $row->quantity - $row->reserved_quantity;

            if ($available < $qty) {
                return false;
            }

            $row->quantity -= $qty;
            $row->save();

            $this->invalidateStockCache($productId);

            return true;
        });
    }

    /**
     * Auto-trigger low stock alerts
     */
    public function checkLowStock(Product $product): bool
    {
        $available = $this->getAvailableStock($product);

        if ($product->reorder_level === null) {
            return false;
        }

        $isLow = $available <= $product->reorder_level;

        if ($isLow) {
            // queueable — dispatch with required arguments
            dispatch(new SendLowStockAlertJob($product, $available));
        }

        return $isLow;
    }

    /**
     * Clear cached inventory values
     */
    public function invalidateStockCache(int $productId): void
    {
        Cache::forget($this->cacheKeyPrefix . $productId . '.total');
    }
}
