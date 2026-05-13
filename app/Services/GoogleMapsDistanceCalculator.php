<?php

namespace App\Services;

use App\Contracts\DistanceCalculatorInterface;
use App\Services\External\HaversineDistanceCalculator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleMapsDistanceCalculator implements DistanceCalculatorInterface
{
    public function __construct(
        private readonly HaversineDistanceCalculator $fallbackCalculator
    ) {}

    public function distanceInKm(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $apiKey = config('services.google.maps_api_key');

        if (!$apiKey) {
            Log::warning('Google Maps API key missing. Falling back to Haversine.');
            return $this->fallbackCalculator->distanceInKm($lat1, $lon1, $lat2, $lon2);
        }

        try {
            $response = Http::timeout(3)
                ->retry(2, 100)
                ->get('https://maps.googleapis.com/maps/api/distancematrix/json', [
                    'origins'      => "{$lat1},{$lon1}",
                    'destinations' => "{$lat2},{$lon2}",
                    'key'          => $apiKey,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (($data['status'] ?? '') === 'OK' && isset($data['rows'][0]['elements'][0]['distance']['value'])) {
                    return $data['rows'][0]['elements'][0]['distance']['value'] / 1000;
                }
            }
        } catch (\Exception $e) {
            Log::error('Google Maps API exception: ' . $e->getMessage());
        }

        return $this->fallbackCalculator->distanceInKm($lat1, $lon1, $lat2, $lon2);
    }

    public function estimatedMinutes(float $lat1, float $lon1, float $lat2, float $lon2): int
    {
        $apiKey = config('services.google.maps_api_key');

        if (!$apiKey) {
            return $this->fallbackCalculator->estimatedMinutes($lat1, $lon1, $lat2, $lon2);
        }

        try {
            $response = Http::timeout(3)
                ->retry(2, 100)
                ->get('https://maps.googleapis.com/maps/api/distancematrix/json', [
                    'origins'      => "{$lat1},{$lon1}",
                    'destinations' => "{$lat2},{$lon2}",
                    'key'          => $apiKey,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (($data['status'] ?? '') === 'OK' && isset($data['rows'][0]['elements'][0]['duration']['value'])) {
                    return (int) ceil($data['rows'][0]['elements'][0]['duration']['value'] / 60);
                }
            }
        } catch (\Exception $e) {}

        return $this->fallbackCalculator->estimatedMinutes($lat1, $lon1, $lat2, $lon2);
    }
}
