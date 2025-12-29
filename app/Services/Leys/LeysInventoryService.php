<?php

namespace App\Services\Leys;

use Illuminate\Support\Facades\DB;
use App\Repositories\InventoryRepository;
use App\Repositories\StockReservationRepository;
use Exception;

class LeysInventoryService
{
    public function __construct(
        protected InventoryRepository $inventoryRepo,
        protected StockReservationRepository $reservationRepo
    ) {}

    public function reserveStock(int $productId, int $warehouseId, int $quantity)
    {
        return DB::transaction(function () use ($productId, $warehouseId, $quantity) {

            $inventory = $this->inventoryRepo->lockForUpdate($productId, $warehouseId);

            if (!$inventory || $inventory->quantity_available < $quantity) {
                throw new Exception("Insufficient stock");
            }

            $this->inventoryRepo->updateStock(
                $inventory,
                $inventory->quantity_available - $quantity,
                $inventory->quantity_reserved + $quantity
            );

            return $this->reservationRepo->create([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'quantity' => $quantity,
                'status' => 'reserved',
                'expires_at' => now()->addMinutes(30),
            ]);
        });
    }
}
