<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\RiderAvailability;
use App\Enums\UserRole;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\RiderLocation;
use App\Models\User;
use App\Services\DispatchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConcurrentOrderTest extends TestCase
{
    use RefreshDatabase;

    private DispatchService $dispatchService;
    private Restaurant $restaurant;
    private User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dispatchService = app(DispatchService::class);

        $owner = User::factory()->create(['role' => UserRole::RestaurantOwner]);
        $this->customer = User::factory()->create(['role' => UserRole::Customer]);

        $this->restaurant = Restaurant::create([
            'user_id'   => $owner->id,
            'name'      => 'Test Restaurant',
            'slug'      => 'test-restaurant',
            'address'   => 'Downtown Cairo',
            'latitude'  => 30.0444,
            'longitude' => 31.2357,
        ]);
    }

    private function createOrder(): Order
    {
        return Order::create([
            'user_id'          => $this->customer->id,
            'restaurant_id'    => $this->restaurant->id,
            'status'           => OrderStatus::Confirmed,
            'subtotal'         => 100,
            'delivery_fee'     => 15,
            'surge_multiplier' => 1.0,
            'tax'              => 14,
            'total'            => 129,
            'delivery_address' => 'Test Address',
        ]);
    }

    private function createRider(float $lat, float $lng): User
    {
        $rider = User::factory()->create(['role' => UserRole::Rider]);

        RiderLocation::create([
            'user_id'      => $rider->id,
            'latitude'     => $lat,
            'longitude'    => $lng,
            'availability' => RiderAvailability::Online,
            'last_ping_at' => now(),
        ]);

        return $rider;
    }

    /**
     * Test that 50 orders dispatched sequentially each get a unique rider
     * and no rider is assigned twice (busy check works).
     */
    public function test_fifty_orders_dispatched_without_race_conditions(): void
    {
        // Create 50 riders at varying distances
        $riders = [];
        for ($i = 0; $i < 50; $i++) {
            $riders[] = $this->createRider(
                30.0444 + ($i * 0.005),
                31.2357 + ($i * 0.005)
            );
        }

        $assignedRiderIds = [];
        $orders = [];

        for ($i = 0; $i < 50; $i++) {
            $order = $this->createOrder();
            $orders[] = $order;

            $rider = $this->dispatchService->assignNearestRider($order);
            if ($rider) {
                $assignedRiderIds[] = $rider->id;
            }
        }

        // All 50 orders should have unique riders assigned
        $this->assertCount(50, $assignedRiderIds, 'All 50 orders should have a rider assigned');
        $this->assertCount(50, array_unique($assignedRiderIds), 'All assigned riders should be unique');

        // All riders should be marked busy
        foreach ($riders as $rider) {
            $location = RiderLocation::where('user_id', $rider->id)->first();
            $this->assertEquals(
                RiderAvailability::Busy,
                $location->availability,
                "Rider #{$rider->id} should be marked as busy"
            );
        }
    }

    /**
     * Test that when there are fewer riders than orders,
     * the remaining orders get null (no rider assigned).
     */
    public function test_more_orders_than_riders(): void
    {
        // Only 3 riders
        for ($i = 0; $i < 3; $i++) {
            $this->createRider(30.0444 + ($i * 0.005), 31.2357 + ($i * 0.005));
        }

        $assignedCount = 0;
        $unassignedCount = 0;

        for ($i = 0; $i < 5; $i++) {
            $order = $this->createOrder();
            $rider = $this->dispatchService->assignNearestRider($order);
            if ($rider) {
                $assignedCount++;
            } else {
                $unassignedCount++;
            }
        }

        $this->assertEquals(3, $assignedCount, 'Only 3 orders should have riders');
        $this->assertEquals(2, $unassignedCount, '2 orders should be unassigned');
    }

    /**
     * Test that releasing a rider makes them available for the next order.
     */
    public function test_rider_release_and_reassign(): void
    {
        $rider = $this->createRider(30.045, 31.236);

        // First order: assign rider
        $order1 = $this->createOrder();
        $assigned = $this->dispatchService->assignNearestRider($order1);
        $this->assertNotNull($assigned);
        $this->assertEquals($rider->id, $assigned->id);

        // Second order: rider is busy, no one available
        $order2 = $this->createOrder();
        $assigned2 = $this->dispatchService->assignNearestRider($order2);
        $this->assertNull($assigned2);

        // Release rider
        $this->dispatchService->releaseRider($rider);

        // Third order: rider is available again
        $order3 = $this->createOrder();
        $assigned3 = $this->dispatchService->assignNearestRider($order3);
        $this->assertNotNull($assigned3);
        $this->assertEquals($rider->id, $assigned3->id);
    }
}
