<?php

namespace App\Services\External;

use App\Contracts\DistanceCalculatorInterface;

/**
 * Placeholder implementation using Haversine formula.
 *
 * TODO: Replace with Google Maps Distance Matrix API for production.
 * This uses straight-line distance as an approximation.
 *
 * Required environment variables for real implementation:
 *   - GOOGLE_MAPS_API_KEY
 *
 * @see EXTERNAL_INTEGRATIONS_GUIDE.md
 */
class HaversineDistanceCalculator implements DistanceCalculatorInterface
{
    private const EARTH_RADIUS_KM = 6371;
    private const AVG_SPEED_KPH = 30; // Average delivery speed in city

    public function distanceInKm(float $originLat, float $originLng, float $destLat, float $destLng): float
    {
        $dLat = deg2rad($destLat - $originLat);
        $dLng = deg2rad($destLng - $originLng);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($originLat)) * cos(deg2rad($destLat))
            * sin($dLng / 2) ** 2;

        $distance = self::EARTH_RADIUS_KM * 2 * atan2(sqrt($a), sqrt(1 - $a));

        // Multiply by 1.3 to approximate road distance vs straight-line
        return round($distance * 1.3, 2);
    }

    public function estimatedMinutes(float $originLat, float $originLng, float $destLat, float $destLng): int
    {
        $distanceKm = $this->distanceInKm($originLat, $originLng, $destLat, $destLng);

        return max(5, (int) ceil(($distanceKm / self::AVG_SPEED_KPH) * 60));
    }
}
