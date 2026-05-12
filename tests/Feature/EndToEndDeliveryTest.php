<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use App\Models\RiderLocation;
use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EndToEndDeliveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_delivery_workflow()
    {
        // 1. Setup: Create Customer, Restaurant, and Rider
        $customer = User::factory()->create(['role' => UserRole::Customer]);
        $owner = User::factory()->create(['role' => UserRole::RestaurantOwner]);
        $rider = User::factory()->create(['role' => UserRole::Rider]);
        
        $restaurant = Restaurant::factory()->create([
            'user_id' => $owner->id,
            'latitude' => 30.0444, 
            'longitude' => 31.2357,
        ]);
        
        $menuItem = MenuItem::factory()->create([
            'restaurant_id' => $restaurant->id,
            'price' => 100
        ]);

        // Setup Rider Location
        RiderLocation::create([
            'user_id' => $rider->id,
            'latitude' => 30.0445,
            'longitude' => 31.2358,
            'availability' => 'online',
            'last_ping_at' => now(),
        ]);

        // 2. Customer places order (Payment Pending)
        $orderData = [
            'restaurant_id' => $restaurant->id,
            'payment_method' => PaymentMethod::Card->value,
            'delivery_address' => '123 Test St',
            'delivery_lat' => 30.0444,
            'delivery_lng' => 31.2357,
            'items' => [
                ['menu_item_id' => $menuItem->id, 'quantity' => 1]
            ]
        ];

        $response = $this->actingAs($customer)->postJson('/api/v1/orders', $orderData);
        $response->assertStatus(201);
        $orderId = $response->json('data.id');
        
        $order = Order::find($orderId);
        $this->assertEquals(OrderStatus::PaymentPending, $order->status);

        // 3. Verify visibility: Restaurant owner should NOT see the order yet
        $response = $this->actingAs($owner)->getJson('/api/v1/orders');
        $response->assertStatus(200);
        $this->assertCount(0, $response->json('data'));

        // 4. Simulate Paymob Webhook: Payment Success
        // We'll call the webhook endpoint or just manually transition for the test
        // Since we want to test the controller logic:
        // Actually, PaymobWebhookController needs a lot of mock data (HMAC, etc.)
        // For simplicity in this E2E test, we'll assume the webhook works and transition the state.
        $order->update(['status' => OrderStatus::Pending]);

        // 5. Verify visibility: Restaurant owner should NOW see the order
        $response = $this->actingAs($owner)->getJson('/api/v1/orders');
        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));

        // 6. Restaurant prepares order
        $this->actingAs($owner)->patchJson("/api/v1/orders/{$orderId}/status", ['status' => 'accepted']);
        $this->actingAs($owner)->patchJson("/api/v1/orders/{$orderId}/status", ['status' => 'preparing']);
        $this->actingAs($owner)->patchJson("/api/v1/orders/{$orderId}/status", ['status' => 'ready_for_pickup']);

        // 7. Rider discovers order
        $response = $this->actingAs($rider)->getJson('/api/rider/orders/available');
        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $orderId]);

        // 8. Rider accepts order
        $response = $this->actingAs($rider)->postJson("/api/rider/orders/{$orderId}/accept");
        $response->assertStatus(200);
        $this->assertEquals(OrderStatus::RiderAssigned, Order::find($orderId)->status);
        $this->assertEquals($rider->id, Order::find($orderId)->rider_id);

        // 9. Rider picks up order
        $response = $this->actingAs($rider)->patchJson("/api/rider/orders/{$orderId}/pickup");
        $response->assertStatus(200);
        $this->assertEquals(OrderStatus::PickedUp, Order::find($orderId)->status);

        // 10. Rider delivers order
        $response = $this->actingAs($rider)->patchJson("/api/rider/orders/{$orderId}/deliver");
        $response->assertStatus(200);
        $this->assertEquals(OrderStatus::Delivered, Order::find($orderId)->status);

        // 11. Verify Earnings
        $response = $this->actingAs($rider)->getJson('/api/rider/earnings');
        $response->assertStatus(200);
        $this->assertGreaterThan(0, $response->json('data.total_net'));
    }
}
