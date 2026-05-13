<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use App\Services\SurgeService;
use App\Services\Surge\MultiplierStrategy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SurgePricingTest extends TestCase
{
    use RefreshDatabase;

    private SurgeService $surgeService;
    private Restaurant $restaurant;

    protected function setUp(): void
    {
        parent::setUp();
        $this->surgeService = new SurgeService();
        $owner = User::factory()->create(['role' => UserRole::RestaurantOwner]);
        $this->restaurant = Restaurant::create([
            'user_id' => $owner->id, 'name' => 'Test Restaurant',
            'slug' => 'test-surge', 'address' => 'Test Address',
        ]);
        Cache::flush();
    }

    public function test_base_multiplier_is_at_least_one(): void
    {
        $m = $this->surgeService->getCurrentMultiplier($this->restaurant->id);
        $this->assertGreaterThanOrEqual(1.0, $m);
    }

    public function test_surge_increases_with_many_active_orders(): void
    {
        Cache::flush();
        $this->createActiveOrders(15);
        $m = $this->surgeService->recalculate($this->restaurant->id);
        $this->assertGreaterThanOrEqual(1.5, $m);
    }

    public function test_manual_override_sets_surge(): void
    {
        MultiplierStrategy::setOverride($this->restaurant->id, 2.5);
        $m = $this->surgeService->recalculate($this->restaurant->id);
        $this->assertGreaterThanOrEqual(2.5, $m);
    }

    public function test_surge_capped_at_3x(): void
    {
        MultiplierStrategy::setOverride($this->restaurant->id, 5.0);
        $m = $this->surgeService->recalculate($this->restaurant->id);
        $this->assertLessThanOrEqual(3.0, $m);
    }

    public function test_breakdown_returns_all_strategies(): void
    {
        $bd = $this->surgeService->getBreakdown($this->restaurant->id);
        $this->assertArrayHasKey('DemandBasedStrategy', $bd);
        $this->assertArrayHasKey('TimeBasedStrategy', $bd);
        $this->assertArrayHasKey('MultiplierStrategy', $bd);
        $this->assertArrayHasKey('final', $bd);
    }

    private function createActiveOrders(int $count): void
    {
        $c = User::factory()->create(['role' => UserRole::Customer]);
        for ($i = 0; $i < $count; $i++) {
            Order::create([
                'user_id' => $c->id, 'restaurant_id' => $this->restaurant->id,
                'status' => OrderStatus::Preparing, 'subtotal' => 100,
                'delivery_fee' => 15, 'surge_multiplier' => 1.0,
                'tax' => 14, 'total' => 129, 'delivery_address' => 'Test',
            ]);
        }
    }
}
