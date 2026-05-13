<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SurgeService
{
    /**
     * Redis cache TTL for surge multiplier values (5 minutes).
     */
    private const CACHE_TTL_SECONDS = 300;

    /**
     * Surge pricing tiers based on active order count.
     * Format: [min_orders => multiplier]
     */
    private const SURGE_TIERS = [
        0  => 1.0,
        5  => 1.25,
        10 => 1.5,
        20 => 1.75,
        30 => 2.0,
        40 => 2.5,
    ];

    /**
     * Get the currently cached surge multiplier for a restaurant.
     * Falls back to 1.0 if not yet calculated.
     */
    public function getCurrentMultiplier(int $restaurantId): float
    {
        return (float) Cache::remember(
            $this->cacheKey($restaurantId),
            self::CACHE_TTL_SECONDS,
            fn () => $this->computeMultiplier($restaurantId)
        );
    }

    /**
     * Recalculate the surge multiplier for a restaurant and store it in Redis.
     *
     * @param  int  $restaurantId
     * @return float  The newly calculated multiplier
     */
    public function recalculate(int $restaurantId): float
    {
        $multiplier = $this->computeMultiplier($restaurantId);

        // Store in Redis with TTL
        Cache::put($this->cacheKey($restaurantId), $multiplier, self::CACHE_TTL_SECONDS);

        Log::info("SurgeService: Restaurant [{$restaurantId}] surge multiplier updated to {$multiplier}x.");

        return $multiplier;
    }

    /**
     * Compute the surge multiplier based on the number of active orders.
     */
    private function computeMultiplier(int $restaurantId): float
    {
        // Count active orders for this restaurant in the last 30 minutes
        $activeOrders = Order::where('restaurant_id', $restaurantId)
            ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready', 'assigned', 'picked_up'])
            ->where('created_at', '>=', now()->subMinutes(30))
            ->count();

        $multiplier = 1.0;

        foreach (self::SURGE_TIERS as $minOrders => $tierMultiplier) {
            if ($activeOrders >= $minOrders) {
                $multiplier = $tierMultiplier;
            }
        }

        return $multiplier;
    }

    /**
     * Generate the Redis cache key for a restaurant's surge multiplier.
     */
    private function cacheKey(int $restaurantId): string
    {
        return "surge:restaurant:{$restaurantId}";
    }
}
