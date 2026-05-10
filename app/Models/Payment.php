<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id', 'payment_intent_id', 'method', 'status',
        'amount', 'currency', 'stripe_metadata', 'paid_at', 'refunded_at',
    ];

    protected $casts = [
        'method'          => PaymentMethod::class,
        'status'          => PaymentStatus::class,
        'amount'          => 'decimal:2',
        'stripe_metadata' => 'array',
        'paid_at'         => 'datetime',
        'refunded_at'     => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
