<?php

namespace App\Repositories;

use App\Models\StockReservation;

class StockReservationRepository
{
    public function create(array $data): StockReservation
    {
        return StockReservation::create($data);
    }

    public function releaseExpiredReservations()
    {
        return StockReservation::where('status', 'reserved')
            ->where('expires_at', '<', now())
            ->get();
    }
}
