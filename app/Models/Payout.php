<?php

namespace App\Models;

use App\Enums\PayoutStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payout extends Model
{
    protected $fillable = [
        'restaurant_id',
        'rider_id',
        'amount',
        'platform_commission',
        'net_amount',
        'status',
        'stripe_transfer_id',
        'paid_at',
        'period_start',
        'period_end',
    ];

    protected $casts = [
        'amount'              => 'decimal:2',
        'platform_commission' => 'decimal:2',
        'net_amount'          => 'decimal:2',
        'status'              => PayoutStatus::class,
        'paid_at'             => 'datetime',
        'period_start'        => 'datetime',
        'period_end'          => 'datetime',
    ];

    // ──────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function rider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rider_id');
    }

    // ──────────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', PayoutStatus::Pending);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', PayoutStatus::Completed);
    }
}
