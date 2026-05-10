<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'restaurant_id', 'rider_id', 'status',
        'subtotal', 'delivery_fee', 'surge_multiplier', 'tax', 'total',
        'delivery_address', 'delivery_lat', 'delivery_lng', 'special_instructions',
        'confirmed_at', 'preparing_at', 'ready_at', 'picked_up_at', 'delivered_at',
        'cancelled_at', 'cancellation_reason',
    ];

    protected $casts = [
        'status'           => OrderStatus::class,
        'subtotal'         => 'decimal:2',
        'delivery_fee'     => 'decimal:2',
        'surge_multiplier' => 'decimal:2',
        'tax'              => 'decimal:2',
        'total'            => 'decimal:2',
        'delivery_lat'     => 'decimal:7',
        'delivery_lng'     => 'decimal:7',
        'confirmed_at'     => 'datetime',
        'preparing_at'     => 'datetime',
        'ready_at'         => 'datetime',
        'picked_up_at'     => 'datetime',
        'delivered_at'     => 'datetime',
        'cancelled_at'     => 'datetime',
    ];

    // ──────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function rider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rider_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(OrderHistory::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    // ──────────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [
            OrderStatus::PaymentPending,
            OrderStatus::Delivered,
            OrderStatus::Cancelled,
        ]);
    }

    public function scopeForRestaurant($query, int $restaurantId)
    {
        return $query->where('restaurant_id', $restaurantId);
    }

    public function scopeForRider($query, int $riderId)
    {
        return $query->where('rider_id', $riderId);
    }

    // ──────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────

    public function isActive(): bool
    {
        return $this->status->isActive();
    }

    public function canTransitionTo(OrderStatus $target): bool
    {
        return $this->status->canTransitionTo($target);
    }
}
