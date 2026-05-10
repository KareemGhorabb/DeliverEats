<?php

namespace App\Services\Surge;

interface SurgeStrategyInterface
{
    /**
     * Calculate the surge multiplier for a restaurant.
     *
     * @param int $restaurantId
     * @return float Multiplier (1.0 = no surge)
     */
    public function calculate(int $restaurantId): float;

    /**
     * Human-readable name of this strategy.
     */
    public function name(): string;
}
