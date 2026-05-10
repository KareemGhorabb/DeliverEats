<?php

namespace App\Services;

use App\Contracts\DistanceCalculatorInterface;
use App\Enums\OrderStatus;
use App\Enums\RiderAvailability;
use App\Events\RiderAssigned;
use App\Models\Order;
use App\Models\RiderLocation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DispatchService
{
    public function __construct(
        private readonly DistanceCalculatorInterface $distanceCalculator,
    ) {}

    /**
     * Assign the nearest available rider to an order.
     *
     * @return User|null The assigned rider, or null if none available
     */
    public function assignNearestRider(Order $order): ?User
    {
        $restaurant = $order->restaurant;

        if (! $restaurant->latitude || ! $restaurant->longitude) {
            Log::warning("Cannot dispatch rider: Restaurant #{$restaurant->id} has no coordinates.");
            return null;
        }

        // Find available riders, sorted by distance to the restaurant
        $availableRiders = RiderLocation::available()
            ->recentlyActive(10) // Active in last 10 minutes
            ->with('rider')
            ->get()
            ->map(function (RiderLocation $location) use ($restaurant) {
                $location->distance = $this->distanceCalculator->distanceInKm(
                    $location->latitude,
                    $location->longitude,
                    $restaurant->latitude,
                    $restaurant->longitude
                );
                return $location;
            })
            ->sortBy('distance')
            ->filter(fn (RiderLocation $loc) => $loc->distance <= 15); // Max 15km radius

        if ($availableRiders->isEmpty()) {
            Log::info("No available riders for Order #{$order->id}.");
            return null;
        }

        // Assign the closest rider
        $closestRiderLocation = $availableRiders->first();
        $rider = $closestRiderLocation->rider;

        return DB::transaction(function () use ($order, $rider, $closestRiderLocation) {
            // Assign rider to order
            $order->update(['rider_id' => $rider->id]);

            // Mark rider as busy
            $closestRiderLocation->update([
                'availability' => RiderAvailability::Busy,
            ]);

            // Fire event
            RiderAssigned::dispatch($order->refresh(), $rider);

            Log::info("Rider #{$rider->id} assigned to Order #{$order->id} (distance: {$closestRiderLocation->distance}km)");

            return $rider;
        });
    }

    /**
     * Release rider back to available status after delivery.
     */
    public function releaseRider(User $rider): void
    {
        $rider->riderLocation?->update([
            'availability' => RiderAvailability::Online,
        ]);
    }

    /**
     * Update rider's live location.
     */
    public function updateRiderLocation(User $rider, float $lat, float $lng, ?string $availability = null): RiderLocation
    {
        $data = [
            'latitude'     => $lat,
            'longitude'    => $lng,
            'last_ping_at' => now(),
        ];

        if ($availability) {
            $data['availability'] = RiderAvailability::from($availability);
        }

        return RiderLocation::updateOrCreate(
            ['user_id' => $rider->id],
            $data
        );
    }
}
