<?php

namespace App\Services\Surge;

use Carbon\Carbon;

/**
 * Time-based surge: peak hours → multiplier.
 *
 * Peak hours (Cairo time):
 *   12:00-14:00 (lunch rush)  → 1.3x
 *   18:00-21:00 (dinner rush) → 1.4x
 *   22:00-02:00 (late night)  → 1.5x
 *   Otherwise                 → 1.0x
 */
class TimeBasedStrategy implements SurgeStrategyInterface
{
    public function calculate(int $restaurantId): float
    {
        $hour = Carbon::now('Africa/Cairo')->hour;

        return match (true) {
            $hour >= 12 && $hour < 14 => 1.3,  // Lunch rush
            $hour >= 18 && $hour < 21 => 1.4,  // Dinner rush
            $hour >= 22 || $hour < 2  => 1.5,  // Late night
            default                   => 1.0,
        };
    }

    public function name(): string
    {
        return 'Time-Based (peak hours)';
    }
}
