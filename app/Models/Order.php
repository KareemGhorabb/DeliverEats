<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'restaurant_id', 'rider_id', 'status',
        'subtotal', 'delivery_fee', 'surge_multiplier', 'tax', 'total',
        'delivery_address', 'delivery_lat', 'delivery_lng', 'special_instructions',
        'confirmed_at', 'preparing_at', 'ready_at', 'picked_up_at', 'delivered_at',
        'cancelled_at', 'cancellation_reason'
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'preparing_at' => 'datetime',
        'ready_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function rider()
    {
        return $this->belongsTo(User::class, 'rider_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function histories()
    {
        return $this->hasMany(OrderHistory::class);
    }
}
