<?php

namespace App\Contracts;

/**
 * Contract for distance/duration calculation between coordinates.
 *
 * TODO: Implement with Google Maps Distance Matrix API.
 * See EXTERNAL_INTEGRATIONS_GUIDE.md for setup instructions.
 */
interface DistanceCalculatorInterface
{
    /**
     * Calculate distance in kilometers between two points.
     *
     * @param float $originLat
     * @param float $originLng
     * @param float $destLat
     * @param float $destLng
     * @return float Distance in kilometers
     */
    public function distanceInKm(float $originLat, float $originLng, float $destLat, float $destLng): float;

    /**
     * Estimated travel time in minutes.
     *
     * @param float $originLat
     * @param float $originLng
     * @param float $destLat
     * @param float $destLng
     * @return int Estimated minutes
     */
    public function estimatedMinutes(float $originLat, float $originLng, float $destLat, float $destLng): int;
}
