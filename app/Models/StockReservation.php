<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class StockReservation extends Model
{
    use HasFactory;

    use HasUuids;

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'order_id',
        'quantity',
        'status',
        'expires_at'
    ];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }
     public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
