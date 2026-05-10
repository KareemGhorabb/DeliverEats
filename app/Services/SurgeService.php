<?php

namespace App\Services;

use App\Services\Surge\SurgeStrategyInterface;
use App\Services\Surge\DemandBasedStrategy;
use App\Services\Surge\TimeBasedStrategy;
use App\Services\Surge\MultiplierStrategy;
use Illuminate\Support\Facades\Cache;

/**
 * Surge Pricing Engine — Strategy Pattern implementation.
 *
 * Combines multiple pricing strategies to compute a dynamic
 * delivery fee multiplier based on demand, time, and manual overrides.
 */
class SurgeService
{
    /** @var SurgeStrategyInterface[] */
    private array $strategies;

    public function __construct()
    {
        $this->strategies = [
            new DemandBasedStrategy(),
            new TimeBasedStrategy(),
            new MultiplierStrategy(),
        ];
    }

    /**
     * Get the current surge multiplier for a restaurant.
     * Result is cached for 2 minutes to avoid recalculating on every request.
     */
    public function getCurrentMultiplier(int $restaurantId): float
    {
        return Cache::remember(
            "surge:restaurant:{$restaurantId}",
            120, // 2 minutes TTL
            fn () => $this->calculateMultiplier($restaurantId)
        );
    }

    /**
     * Recalculate and cache the surge multiplier.
     */
    public function recalculate(int $restaurantId): float
    {
        $multiplier = $this->calculateMultiplier($restaurantId);

        Cache::put("surge:restaurant:{$restaurantId}", $multiplier, 120);

        return $multiplier;
    }

    /**
     * Calculate the combined multiplier from all strategies.
     * Uses the maximum multiplier from all strategies.
     */
    private function calculateMultiplier(int $restaurantId): float
    {
        $multiplier = 1.0;

        foreach ($this->strategies as $strategy) {
            $strategyMultiplier = $strategy->calculate($restaurantId);
            $multiplier = max($multiplier, $strategyMultiplier);
        }

        // Cap at 3x to protect customers
        return min(round($multiplier, 2), 3.0);
    }

    /**
     * Get breakdown of all strategy contributions.
     */
    public function getBreakdown(int $restaurantId): array
    {
        $breakdown = [];

        foreach ($this->strategies as $strategy) {
            $className = class_basename($strategy);
            $breakdown[$className] = [
                'multiplier' => $strategy->calculate($restaurantId),
                'name'       => $strategy->name(),
            ];
        }

        $breakdown['final'] = [
            'multiplier' => $this->getCurrentMultiplier($restaurantId),
            'name'       => 'Final (max of all strategies, capped at 3.0x)',
        ];

        return $breakdown;
    }
}
