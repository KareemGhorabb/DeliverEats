<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use App\Services\PayoutService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PayoutCalculationTest extends TestCase
{
    use RefreshDatabase;

    private PayoutService $payoutService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->payoutService = new PayoutService();
    }

    public function test_restaurant_payout_with_15_percent_commission(): void
    {
        $order = $this->createDeliveredOrder(subtotal: 200.00, deliveryFee: 20.00);
        $payouts = $this->payoutService->processOrderPayout($order);

        $rPayout = $payouts['restaurant'];
        $this->assertEquals(200.00, (float) $rPayout->amount);
        $this->assertEquals(30.00, (float) $rPayout->platform_commission); // 15%
        $this->assertEquals(170.00, (float) $rPayout->net_amount);
    }

    public function test_rider_payout_with_10_percent_commission(): void
    {
        $order = $this->createDeliveredOrder(subtotal: 200.00, deliveryFee: 20.00);
        $payouts = $this->payoutService->processOrderPayout($order);

        $ridPayout = $payouts['rider'];
        $this->assertEquals(20.00, (float) $ridPayout->amount);
        $this->assertEquals(2.00, (float) $ridPayout->platform_commission); // 10%
        $this->assertEquals(18.00, (float) $ridPayout->net_amount);
    }

    public function test_rejects_payout_for_non_delivered_order(): void
    {
        $order = $this->createDeliveredOrder();
        $order->update(['status' => OrderStatus::Preparing]);

        $this->expectException(\InvalidArgumentException::class);
        $this->payoutService->processOrderPayout($order);
    }

    public function test_earnings_summary_aggregation(): void
    {
        $order1 = $this->createDeliveredOrder(subtotal: 100, deliveryFee: 15);
        $order2 = $this->createDeliveredOrder(subtotal: 200, deliveryFee: 25);

        $this->payoutService->processOrderPayout($order1);
        $this->payoutService->processOrderPayout($order2);

        $earnings = $this->payoutService->getRestaurantEarnings($order1->restaurant_id);
        $this->assertEquals(300.00, $earnings['total_gross']);
        $this->assertEquals(45.00, $earnings['total_commission']); // 15% of 300
        $this->assertEquals(255.00, $earnings['total_net']);
    }

    private function createDeliveredOrder(float $subtotal = 100, float $deliveryFee = 15): Order
    {
        $customer = User::factory()->create(['role' => UserRole::Customer]);
        $owner = User::factory()->create(['role' => UserRole::RestaurantOwner]);
        $rider = User::factory()->create(['role' => UserRole::Rider]);
        $restaurant = Restaurant::create([
            'user_id' => $owner->id, 'name' => 'R' . rand(1, 9999),
            'slug' => 'r-' . rand(1, 99999), 'address' => 'Test',
        ]);

        return Order::create([
            'user_id' => $customer->id, 'restaurant_id' => $restaurant->id,
            'rider_id' => $rider->id, 'status' => OrderStatus::Delivered,
            'subtotal' => $subtotal, 'delivery_fee' => $deliveryFee,
            'surge_multiplier' => 1.0, 'tax' => $subtotal * 0.14,
            'total' => $subtotal + $deliveryFee + ($subtotal * 0.14),
            'delivery_address' => 'Test', 'delivered_at' => now(),
        ]);
    }
}
