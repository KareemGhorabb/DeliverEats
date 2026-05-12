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

class RiderDispatchTest extends TestCase
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
            'delivery_address' => '456 Test St',
        ]);
    }

    private function createRider(float $lat, float $lng, RiderAvailability $availability = RiderAvailability::Online): User
    {
        $rider = User::factory()->create(['role' => UserRole::Rider]);

        RiderLocation::create([
            'user_id'      => $rider->id,
            'latitude'     => $lat,
            'longitude'    => $lng,
            'availability' => $availability,
            'last_ping_at' => now(),
        ]);

        return $rider;
    }

    // ──────────────────────────────────────────────
    // DISPATCH TESTS
    // ──────────────────────────────────────────────

    public function test_assigns_nearest_available_rider(): void
    {
        $order = $this->createOrder();

        // Rider A: 5km away
        $riderA = $this->createRider(30.08, 31.25);
        // Rider B: 1km away (closer)
        $riderB = $this->createRider(30.05, 31.24);
        // Rider C: 10km away
        $riderC = $this->createRider(30.15, 31.30);

        $assigned = $this->dispatchService->assignNearestRider($order);

        $this->assertNotNull($assigned);
        $this->assertEquals($riderB->id, $assigned->id);
        $this->assertEquals($riderB->id, $order->fresh()->rider_id);
    }

    public function test_skips_offline_riders(): void
    {
        $order = $this->createOrder();

        // Closest rider is offline
        $this->createRider(30.045, 31.236, RiderAvailability::Offline);
        // Further rider is online
        $onlineRider = $this->createRider(30.08, 31.25, RiderAvailability::Online);

        $assigned = $this->dispatchService->assignNearestRider($order);

        $this->assertNotNull($assigned);
        $this->assertEquals($onlineRider->id, $assigned->id);
    }

    public function test_skips_busy_riders(): void
    {
        $order = $this->createOrder();

        // Closest rider is busy
        $this->createRider(30.045, 31.236, RiderAvailability::Busy);
        // Further rider is online
        $onlineRider = $this->createRider(30.08, 31.25, RiderAvailability::Online);

        $assigned = $this->dispatchService->assignNearestRider($order);

        $this->assertEquals($onlineRider->id, $assigned->id);
    }

    public function test_returns_null_when_no_riders_available(): void
    {
        $order = $this->createOrder();

        // No riders created
        $assigned = $this->dispatchService->assignNearestRider($order);

        $this->assertNull($assigned);
        $this->assertNull($order->fresh()->rider_id);
    }

    public function test_marks_assigned_rider_as_busy(): void
    {
        $order = $this->createOrder();
        $rider = $this->createRider(30.05, 31.24);

        $this->dispatchService->assignNearestRider($order);

        $location = RiderLocation::where('user_id', $rider->id)->first();
        $this->assertEquals(RiderAvailability::Busy, $location->availability);
    }

    public function test_release_rider_sets_online(): void
    {
        $rider = $this->createRider(30.05, 31.24, RiderAvailability::Busy);

        $this->dispatchService->releaseRider($rider);

        $location = RiderLocation::where('user_id', $rider->id)->first();
        $this->assertEquals(RiderAvailability::Online, $location->availability);
    }

    public function test_rejects_riders_beyond_15km_radius(): void
    {
        $order = $this->createOrder();

        // Rider 20km+ away
        $this->createRider(30.25, 31.50, RiderAvailability::Online);

        $assigned = $this->dispatchService->assignNearestRider($order);

        $this->assertNull($assigned);
    }

    public function test_skips_stale_rider_locations(): void
    {
        $order = $this->createOrder();

        // Rider with stale location (15 minutes old)
        $rider = User::factory()->create(['role' => UserRole::Rider]);
        RiderLocation::create([
            'user_id'      => $rider->id,
            'latitude'     => 30.05,
            'longitude'    => 31.24,
            'availability' => RiderAvailability::Online,
            'last_ping_at' => now()->subMinutes(15),
        ]);

        $assigned = $this->dispatchService->assignNearestRider($order);

        $this->assertNull($assigned);
    }

    // ──────────────────────────────────────────────
    // LOCATION UPDATE TESTS
    // ──────────────────────────────────────────────

    public function test_update_rider_location(): void
    {
        $rider = User::factory()->create(['role' => UserRole::Rider]);

        $location = $this->dispatchService->updateRiderLocation($rider, 30.05, 31.24, 'online');

        $this->assertEquals(30.05, (float) $location->latitude);
        $this->assertEquals(31.24, (float) $location->longitude);
        $this->assertEquals(RiderAvailability::Online, $location->availability);
        $this->assertNotNull($location->last_ping_at);
    }

    public function test_upserts_rider_location(): void
    {
        $rider = User::factory()->create(['role' => UserRole::Rider]);

        $this->dispatchService->updateRiderLocation($rider, 30.05, 31.24);
        $this->dispatchService->updateRiderLocation($rider, 30.06, 31.25);

        $this->assertEquals(1, RiderLocation::where('user_id', $rider->id)->count());

        $location = RiderLocation::where('user_id', $rider->id)->first();
        $this->assertEquals(30.06, (float) $location->latitude);
    }
}
