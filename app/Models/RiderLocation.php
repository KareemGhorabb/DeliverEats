<?php

namespace App\Models;

use App\Enums\RiderAvailability;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiderLocation extends Model
{
    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
        'availability',
        'last_ping_at',
    ];

    protected $casts = [
        'latitude'     => 'decimal:7',
        'longitude'    => 'decimal:7',
        'availability' => RiderAvailability::class,
        'last_ping_at' => 'datetime',
    ];

    // ──────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────

    public function rider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ──────────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────────

    public function scopeAvailable($query)
    {
        return $query->where('availability', RiderAvailability::Online);
    }

    public function scopeRecentlyActive($query, int $minutes = 5)
    {
        return $query->where('last_ping_at', '>=', now()->subMinutes($minutes));
    }

    // ──────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────

    /**
     * Haversine distance in km to given coordinates.
     */
    public function distanceTo(float $lat, float $lng): float
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat - $this->latitude);
        $dLng = deg2rad($lng - $this->longitude);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($this->latitude)) * cos(deg2rad($lat))
            * sin($dLng / 2) ** 2;

        return $earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}
