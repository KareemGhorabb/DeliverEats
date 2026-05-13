<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\UserRole;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
use Stripe\Checkout\Session;

class StripeWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('services.stripe.secret', 'sk_test_mock');
        Config::set('services.stripe.webhook_secret', 'whsec_test_mock');

        // System user is required for automated transitions
        User::factory()->create([
            'email' => 'admin@delivereats.com',
            'role' => UserRole::Admin
        ]);
    }

    public function test_stripe_success_redirect_transitions_order(): void
    {
        $owner = User::factory()->create(['role' => UserRole::RestaurantOwner]);
        
        $restaurant = Restaurant::create([
            'user_id' => $owner->id,
            'name' => 'Test Restaurant',
            'slug' => 'test-restaurant',
            'phone' => '123456789',
            'address' => 'Test Address',
            'latitude' => 30.0,
            'longitude' => 31.0
        ]);

        $customer = User::factory()->create(['role' => UserRole::Customer]);

        $order = Order::create([
            'user_id' => $customer->id,
            'restaurant_id' => $restaurant->id,
            'status' => OrderStatus::PaymentPending,
            'subtotal' => 100.00,
            'delivery_fee' => 10.00,
            'surge_multiplier' => 1.0,
            'tax' => 14.00,
            'total' => 124.00,
            'delivery_address' => 'Test Address'
        ]);

        $payment = Payment::create([
            'order_id' => $order->id,
            'method' => PaymentMethod::Card,
            'status' => PaymentStatus::Processing,
            'amount' => 124.00,
            'payment_intent_id' => 'cs_test_123'
        ]);

        // Mock Stripe Session retrieve
        $mockSession = (object)[
            'payment_status' => 'paid',
            'metadata' => (object)['order_id' => $order->id]
        ];

        $this->mock('alias:' . Session::class, function ($mock) use ($mockSession) {
            $mock->shouldReceive('retrieve')->with('cs_test_123')->andReturn($mockSession);
        });

        // Act as the customer
        $this->actingAs($customer);

        $response = $this->get(route('stripe.success', $order->id) . '?session_id=cs_test_123');

        $response->assertRedirect(route('customer.orders.track', $order->id));
        
        $order->refresh();
        $this->assertEquals(OrderStatus::Pending, $order->status);
        $this->assertEquals(PaymentStatus::Succeeded, $order->payment->status);
    }

    public function test_stripe_webhook_transitions_order(): void
    {
        $owner = User::factory()->create(['role' => UserRole::RestaurantOwner]);
        
        $restaurant = Restaurant::create([
            'user_id' => $owner->id,
            'name' => 'Test Restaurant',
            'slug' => 'test-restaurant',
            'phone' => '123456789',
            'address' => 'Test Address',
            'latitude' => 30.0,
            'longitude' => 31.0
        ]);

        $customer = User::factory()->create(['role' => UserRole::Customer]);

        $order = Order::create([
            'user_id' => $customer->id,
            'restaurant_id' => $restaurant->id,
            'status' => OrderStatus::PaymentPending,
            'subtotal' => 100.00,
            'delivery_fee' => 10.00,
            'surge_multiplier' => 1.0,
            'tax' => 14.00,
            'total' => 124.00,
            'delivery_address' => 'Test Address'
        ]);

        $payment = Payment::create([
            'order_id' => $order->id,
            'method' => PaymentMethod::Card,
            'status' => PaymentStatus::Processing,
            'amount' => 124.00,
            'payment_intent_id' => 'cs_test_123'
        ]);

        $payload = [
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_test_123',
                    'metadata' => ['order_id' => $order->id],
                    'payment_status' => 'paid'
                ]
            ]
        ];

        $this->mock('alias:' . Session::class, function ($mock) use ($order) {
            $mock->shouldReceive('retrieve')->with('cs_test_123')->andReturn((object)[
                'payment_status' => 'paid',
                'metadata' => (object)['order_id' => $order->id]
            ]);
        });

        $this->mock('alias:Stripe\Webhook', function ($mock) use ($order) {
            $mock->shouldReceive('constructEvent')->andReturn((object)[
                'type' => 'checkout.session.completed',
                'data' => (object)[
                    'object' => (object)[
                        'id' => 'cs_test_123',
                        'metadata' => (object)['order_id' => $order->id],
                        'payment_status' => 'paid'
                    ]
                ]
            ]);
        });

        $response = $this->postJson('/api/v1/webhooks/stripe', $payload, [
            'Stripe-Signature' => 'mock_signature'
        ]);

        $response->assertStatus(200);
        
        $order->refresh();
        $this->assertEquals(OrderStatus::Pending, $order->status);
        $this->assertEquals(PaymentStatus::Succeeded, $order->payment->status);
    }
}
