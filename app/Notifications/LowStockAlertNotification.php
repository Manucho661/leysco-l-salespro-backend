<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Product $product;
    public int $stock;
    public ?int $warehouseId;

    public function __construct(Product $product, int $stock, ?int $warehouseId = null)
    {
        $this->product = $product;
        $this->stock = $stock;
        $this->warehouseId = $warehouseId;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Low Stock Alert: ' . $this->product->name)
            ->line("Product: {$this->product->name}")
            ->line("Remaining Stock: {$this->stock}")
            ->when($this->warehouseId, fn ($m) =>
                $m->line("Warehouse ID: {$this->warehouseId}")
            )
            ->line('Reorder is recommended.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'stock' => $this->stock,
            'warehouse_id' => $this->warehouseId,
            'type' => 'low_stock',
        ];
    }
}
