<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id', 'payment_intent_id', 'method', 'status',
        'amount', 'currency', 'stripe_metadata', 'paid_at', 'refunded_at'
    ];

    protected $casts = [
        'stripe_metadata' => 'array',
        'paid_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
