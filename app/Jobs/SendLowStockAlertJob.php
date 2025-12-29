<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LowStockAlertNotification;

class SendLowStockAlertJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Product $product;
    public int $stock;
    public ?int $warehouseId;

    /**
     * Create a new job instance.
     */
    public function __construct(Product $product, int $stock, ?int $warehouseId = null)
    {
        $this->product = $product;
        $this->stock = $stock;
        $this->warehouseId = $warehouseId;

        // Optional — set priority or delay if needed
        // $this->delay(now()->addSeconds(5));
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Who receives alerts? (Sales Managers / Inventory Admins)
        $recipients = User::where('role', 'Sales Manager')
            ->orWhere('role', 'Admin')
            ->get();

        if ($recipients->isEmpty()) {
            return;
        }

        Notification::send(
            $recipients,
            new LowStockAlertNotification(
                product: $this->product,
                stock: $this->stock,
                warehouseId: $this->warehouseId
            )
        );
    }
}
