<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use App\Services\OrderStateMachine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderStateMachineTest extends TestCase
{
    use RefreshDatabase;

    private OrderStateMachine $fsm;
    private User $customer;
    private User $restaurantOwner;
    private User $rider;
    private User $admin;
    private Restaurant $restaurant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fsm = new OrderStateMachine();

        $this->customer = User::factory()->create(['role' => UserRole::Customer]);
        $this->restaurantOwner = User::factory()->create(['role' => UserRole::RestaurantOwner]);
        $this->rider = User::factory()->create(['role' => UserRole::Rider]);
        $this->admin = User::factory()->create(['role' => UserRole::Admin]);

        $this->restaurant = Restaurant::create([
            'user_id' => $this->restaurantOwner->id,
            'name'    => 'Test Restaurant',
            'slug'    => 'test-restaurant',
            'address' => 'Test Address',
        ]);
    }

    private function createOrder(OrderStatus $status = OrderStatus::Pending): Order
    {
        return Order::create([
            'user_id'          => $this->customer->id,
            'restaurant_id'    => $this->restaurant->id,
            'status'           => $status,
            'subtotal'         => 100.00,
            'delivery_fee'     => 15.00,
            'surge_multiplier' => 1.00,
            'tax'              => 14.00,
            'total'            => 129.00,
            'delivery_address' => '123 Test St',
        ]);
    }

    // ──────────────────────────────────────────────
    // VALID TRANSITIONS
    // ──────────────────────────────────────────────

    public function test_pending_to_accepted_by_restaurant_owner(): void
    {
        $order = $this->createOrder(OrderStatus::Pending);

        $result = $this->fsm->transition($order, OrderStatus::Accepted, $this->restaurantOwner);

        $this->assertEquals(OrderStatus::Accepted, $result->status);
        $this->assertNotNull($result->confirmed_at);
        $this->assertDatabaseHas('order_histories', [
            'order_id'    => $order->id,
            'from_status' => 'pending',
            'to_status'   => 'accepted',
            'changed_by'  => $this->restaurantOwner->id,
        ]);
    }

    public function test_accepted_to_preparing(): void
    {
        $order = $this->createOrder(OrderStatus::Accepted);

        $result = $this->fsm->transition($order, OrderStatus::Preparing, $this->restaurantOwner);

        $this->assertEquals(OrderStatus::Preparing, $result->status);
        $this->assertNotNull($result->preparing_at);
    }

    public function test_preparing_to_ready_for_pickup(): void
    {
        $order = $this->createOrder(OrderStatus::Preparing);

        $result = $this->fsm->transition($order, OrderStatus::ReadyForPickup, $this->restaurantOwner);

        $this->assertEquals(OrderStatus::ReadyForPickup, $result->status);
        $this->assertNotNull($result->ready_at);
    }

    public function test_ready_for_pickup_to_picked_up_by_rider(): void
    {
        $order = $this->createOrder(OrderStatus::ReadyForPickup);

        $result = $this->fsm->transition($order, OrderStatus::PickedUp, $this->rider);

        $this->assertEquals(OrderStatus::PickedUp, $result->status);
        $this->assertNotNull($result->picked_up_at);
    }

    public function test_picked_up_to_delivered_by_rider(): void
    {
        $order = $this->createOrder(OrderStatus::PickedUp);

        $result = $this->fsm->transition($order, OrderStatus::Delivered, $this->rider);

        $this->assertEquals(OrderStatus::Delivered, $result->status);
        $this->assertNotNull($result->delivered_at);
    }

    public function test_full_lifecycle(): void
    {
        $order = $this->createOrder(OrderStatus::Pending);

        $order = $this->fsm->transition($order, OrderStatus::Accepted, $this->restaurantOwner);
        $order = $this->fsm->transition($order, OrderStatus::Preparing, $this->restaurantOwner);
        $order = $this->fsm->transition($order, OrderStatus::ReadyForPickup, $this->restaurantOwner);
        $order = $this->fsm->transition($order, OrderStatus::PickedUp, $this->rider);
        $order = $this->fsm->transition($order, OrderStatus::Delivered, $this->rider);

        $this->assertEquals(OrderStatus::Delivered, $order->status);
        $this->assertFalse($order->isActive());
        $this->assertEquals(6, $order->histories()->count()); // 5 transitions + initial placement would be in OrderService
    }

    // ──────────────────────────────────────────────
    // INVALID TRANSITIONS (must be rejected)
    // ──────────────────────────────────────────────

    public function test_reject_pending_to_delivered(): void
    {
        $order = $this->createOrder(OrderStatus::Pending);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/Invalid transition/');

        $this->fsm->transition($order, OrderStatus::Delivered, $this->rider);
    }

    public function test_reject_pending_to_picked_up(): void
    {
        $order = $this->createOrder(OrderStatus::Pending);

        $this->expectException(\InvalidArgumentException::class);

        $this->fsm->transition($order, OrderStatus::PickedUp, $this->rider);
    }

    public function test_reject_delivered_to_any(): void
    {
        $order = $this->createOrder(OrderStatus::Delivered);

        $this->expectException(\InvalidArgumentException::class);

        $this->fsm->transition($order, OrderStatus::Pending, $this->admin);
    }

    public function test_reject_cancelled_to_any(): void
    {
        $order = $this->createOrder(OrderStatus::Cancelled);

        $this->expectException(\InvalidArgumentException::class);

        $this->fsm->transition($order, OrderStatus::Accepted, $this->restaurantOwner);
    }

    public function test_reject_preparing_to_accepted(): void
    {
        $order = $this->createOrder(OrderStatus::Preparing);

        $this->expectException(\InvalidArgumentException::class);

        $this->fsm->transition($order, OrderStatus::Accepted, $this->restaurantOwner);
    }

    // ──────────────────────────────────────────────
    // ROLE-BASED GUARDS
    // ──────────────────────────────────────────────

    public function test_customer_cannot_accept_order(): void
    {
        $order = $this->createOrder(OrderStatus::Pending);

        $this->expectException(\Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException::class);

        $this->fsm->transition($order, OrderStatus::Accepted, $this->customer);
    }

    public function test_rider_cannot_accept_order(): void
    {
        $order = $this->createOrder(OrderStatus::Pending);

        $this->expectException(\Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException::class);

        $this->fsm->transition($order, OrderStatus::Accepted, $this->rider);
    }

    public function test_restaurant_owner_cannot_mark_picked_up(): void
    {
        $order = $this->createOrder(OrderStatus::ReadyForPickup);

        $this->expectException(\Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException::class);

        $this->fsm->transition($order, OrderStatus::PickedUp, $this->restaurantOwner);
    }

    public function test_admin_can_override_any_transition(): void
    {
        $order = $this->createOrder(OrderStatus::Pending);

        // Admin can accept even though it's normally restaurant_owner only
        $result = $this->fsm->transition($order, OrderStatus::Accepted, $this->admin);

        $this->assertEquals(OrderStatus::Accepted, $result->status);
    }

    // ──────────────────────────────────────────────
    // CANCELLATION RULES
    // ──────────────────────────────────────────────

    public function test_customer_can_cancel_pending_order(): void
    {
        $order = $this->createOrder(OrderStatus::Pending);

        $result = $this->fsm->transition($order, OrderStatus::Cancelled, $this->customer, 'Changed my mind');

        $this->assertEquals(OrderStatus::Cancelled, $result->status);
        $this->assertNotNull($result->cancelled_at);
    }

    public function test_customer_cannot_cancel_preparing_order(): void
    {
        $order = $this->createOrder(OrderStatus::Preparing);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/cannot cancel/i');

        $this->fsm->transition($order, OrderStatus::Cancelled, $this->customer);
    }

    public function test_rider_cannot_cancel_order(): void
    {
        $order = $this->createOrder(OrderStatus::Pending);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/Riders cannot cancel/');

        $this->fsm->transition($order, OrderStatus::Cancelled, $this->rider);
    }

    public function test_restaurant_owner_can_cancel_accepted_order(): void
    {
        $order = $this->createOrder(OrderStatus::Accepted);

        $result = $this->fsm->transition(
            $order, OrderStatus::Cancelled, $this->restaurantOwner, 'Out of ingredients'
        );

        $this->assertEquals(OrderStatus::Cancelled, $result->status);
    }

    // ──────────────────────────────────────────────
    // EVENT SOURCING HISTORY
    // ──────────────────────────────────────────────

    public function test_transition_logs_history(): void
    {
        $order = $this->createOrder(OrderStatus::Pending);

        $this->fsm->transition($order, OrderStatus::Accepted, $this->restaurantOwner, 'Order looks good');

        $this->assertDatabaseHas('order_histories', [
            'order_id'    => $order->id,
            'from_status' => OrderStatus::Pending->value,
            'to_status'   => OrderStatus::Accepted->value,
            'changed_by'  => $this->restaurantOwner->id,
            'note'        => 'Order looks good',
        ]);
    }

    public function test_multiple_transitions_create_full_audit_trail(): void
    {
        $order = $this->createOrder(OrderStatus::Pending);

        $this->fsm->transition($order, OrderStatus::Accepted, $this->restaurantOwner);
        $this->fsm->transition($order, OrderStatus::Preparing, $this->restaurantOwner);
        $this->fsm->transition($order, OrderStatus::ReadyForPickup, $this->restaurantOwner);

        $histories = $order->histories()->orderBy('id')->get();

        $this->assertCount(3, $histories);
        $this->assertEquals('pending', $histories[0]->from_status);
        $this->assertEquals('accepted', $histories[0]->to_status);
        $this->assertEquals('accepted', $histories[1]->from_status);
        $this->assertEquals('preparing', $histories[1]->to_status);
        $this->assertEquals('preparing', $histories[2]->from_status);
        $this->assertEquals('ready_for_pickup', $histories[2]->to_status);
    }
}
