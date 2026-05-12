<?php

namespace App\Services\Surge;

use Illuminate\Support\Facades\Cache;

/**
 * Manual multiplier strategy — allows admin to set a custom surge.
 *
 * The admin can set a multiplier override per restaurant via:
 *   Cache::put("surge:manual:{restaurantId}", 2.5);
 *
 * If no manual override is set, returns 1.0 (no surge).
 */
class MultiplierStrategy implements SurgeStrategyInterface
{
    /**
     * Set a manual surge override for a restaurant.
     */
    public static function setOverride(int $restaurantId, float $multiplier): void
    {
        Cache::put("surge:manual:{$restaurantId}", max(1.0, $multiplier), 3600); // 1 hour TTL
    }

    /**
     * Clear the manual override.
     */
    public static function clearOverride(int $restaurantId): void
    {
        Cache::forget("surge:manual:{$restaurantId}");
    }

    public function calculate(int $restaurantId): float
    {
        return Cache::get("surge:manual:{$restaurantId}", 1.0);
    }

    public function name(): string
    {
        return 'Manual Multiplier (admin override)';
    }
}
