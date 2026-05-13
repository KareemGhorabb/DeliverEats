<?php

namespace App\Services\Surge;

use App\Enums\OrderStatus;
use App\Models\Order;

/**
 * Demand-based surge: active order count → multiplier.
 *
 * Thresholds:
 *   0-5  active orders → 1.0x
 *   6-10 active orders → 1.25x
 *   11-20 active orders → 1.5x
 *   21-30 active orders → 1.75x
 *   31+  active orders → 2.0x
 */
class DemandBasedStrategy implements SurgeStrategyInterface
{
    public function calculate(int $restaurantId): float
    {
        $activeOrderCount = Order::where('restaurant_id', $restaurantId)
            ->active()
            ->count();

        return match (true) {
            $activeOrderCount <= 5  => 1.0,
            $activeOrderCount <= 10 => 1.25,
            $activeOrderCount <= 20 => 1.5,
            $activeOrderCount <= 30 => 1.75,
            default                 => 2.0,
        };
    }

    public function name(): string
    {
        return 'Demand-Based (active order count)';
    }
}
