<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    private User $customer;
    private User $owner;
    private Restaurant $restaurant;
    private MenuItem $menuItem;

    protected function setUp(): void
    {
        parent::setUp();
        $this->customer = User::factory()->create(['role' => UserRole::Customer]);
        $this->owner = User::factory()->create(['role' => UserRole::RestaurantOwner]);
        $this->restaurant = Restaurant::create([
            'user_id' => $this->owner->id, 'name' => 'API Test Restaurant',
            'slug' => 'api-test', 'address' => 'Cairo',
        ]);
        $cat = MenuCategory::create([
            'restaurant_id' => $this->restaurant->id, 'name' => 'Main',
        ]);
        $this->menuItem = MenuItem::create([
            'menu_category_id' => $cat->id, 'restaurant_id' => $this->restaurant->id,
            'name' => 'Burger', 'price' => 50.00,
        ]);
    }

    public function test_customer_can_place_order(): void
    {
        $response = $this->actingAs($this->customer)->postJson('/api/v1/orders', [
            'restaurant_id' => $this->restaurant->id,
            'delivery_address' => '123 Test St',
            'payment_method' => 'cash',
            'items' => [
                ['menu_item_id' => $this->menuItem->id, 'quantity' => 2],
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', 'pending');

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->customer->id,
            'restaurant_id' => $this->restaurant->id,
            'status' => 'pending',
        ]);
    }

    public function test_order_status_transition_via_api(): void
    {
        $order = Order::create([
            'user_id' => $this->customer->id, 'restaurant_id' => $this->restaurant->id,
            'status' => OrderStatus::Pending, 'subtotal' => 100, 'delivery_fee' => 15,
            'surge_multiplier' => 1.0, 'tax' => 14, 'total' => 129,
            'delivery_address' => 'Test',
        ]);

        $response = $this->actingAs($this->owner)->patchJson("/api/v1/orders/{$order->id}/status", [
            'status' => 'accepted',
        ]);

        $response->assertOk()->assertJsonPath('data.status', 'accepted');
    }

    public function test_invalid_transition_returns_422(): void
    {
        $order = Order::create([
            'user_id' => $this->customer->id, 'restaurant_id' => $this->restaurant->id,
            'status' => OrderStatus::Pending, 'subtotal' => 100, 'delivery_fee' => 15,
            'surge_multiplier' => 1.0, 'tax' => 14, 'total' => 129,
            'delivery_address' => 'Test',
        ]);

        $rider = User::factory()->create(['role' => UserRole::Rider]);

        $response = $this->actingAs($rider)->patchJson("/api/v1/orders/{$order->id}/status", [
            'status' => 'delivered',
        ]);

        $response->assertStatus(422);
    }

    public function test_order_history_endpoint(): void
    {
        $order = Order::create([
            'user_id' => $this->customer->id, 'restaurant_id' => $this->restaurant->id,
            'status' => OrderStatus::Pending, 'subtotal' => 100, 'delivery_fee' => 15,
            'surge_multiplier' => 1.0, 'tax' => 14, 'total' => 129,
            'delivery_address' => 'Test',
        ]);

        // Transition to create history
        $this->actingAs($this->owner)->patchJson("/api/v1/orders/{$order->id}/status", [
            'status' => 'accepted',
        ]);

        $response = $this->actingAs($this->customer)->getJson("/api/v1/orders/{$order->id}/history");
        $response->assertOk()->assertJsonCount(1, 'data');
    }

    public function test_unauthenticated_cannot_place_order(): void
    {
        $response = $this->postJson('/api/v1/orders', [
            'restaurant_id' => $this->restaurant->id,
            'delivery_address' => 'Test',
            'payment_method' => 'cash',
            'items' => [['menu_item_id' => $this->menuItem->id, 'quantity' => 1]],
        ]);

        $response->assertStatus(401);
    }
}
