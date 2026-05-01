<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'menu_item_id', 'item_variant_id', 'quantity',
        'unit_price', 'total_price', 'special_requests'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function itemVariant()
    {
        return $this->belongsTo(ItemVariant::class);
    }
}
