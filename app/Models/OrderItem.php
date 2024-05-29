<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id_primary',
        'product_id_secondary',
        'name',
        'description',
        'price',
        'quantity',
        'crust',
        'crust_price',
        'observation_primary',
        'observation_secondary',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
