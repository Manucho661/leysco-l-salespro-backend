<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StockReservation;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;

class ReleaseExpiredReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:release-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Release expired stock reservations and restore inventory';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired stock reservations...');

        DB::transaction(function () {
            $expiredReservations = StockReservation::where('status', 'reserved')
                ->where('expires_at', '<', now())
                ->lockForUpdate()
                ->get();

            foreach ($expiredReservations as $reservation) {
                $inventory = Inventory::where('product_id', $reservation->product_id)
                    ->where('warehouse_id', $reservation->warehouse_id)
                    ->lockForUpdate()
                    ->first();

                if ($inventory) {
                    $inventory->decrement('quantity', $reservation->quantity);
                }

                $reservation->update([
                    'status' => 'released'
                ]);
            }
        });

        $this->info('Expired reservations released successfully.');
    }
}
