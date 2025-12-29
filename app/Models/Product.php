<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'sku',
        'name',
        'description',
        'category_id',
        'price',
        'unit',
        'reorder_level'
    ];

    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }

    public function reservations()
    {
        return $this->hasMany(StockReservation::class);
    }
}
