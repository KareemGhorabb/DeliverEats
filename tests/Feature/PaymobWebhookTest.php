<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class PaymobWebhookTest extends TestCase
{
    use RefreshDatabase;

    private string $hmacSecret = 'test_hmac_secret_123';

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('services.paymob.hmac', $this->hmacSecret);

        // System user is required for automated transitions
        User::factory()->create([
            'email' => 'admin@delivereats.com',
            'role' => UserRole::Admin
        ]);
    }

    private function generateHmac(array $obj): string
    {
        $keys = [
            'amount_cents',
            'created_at',
            'currency',
            'error_occured',
            'has_parent_transaction',
            'id',
            'integration_id',
            'is_3d_secure',
            'is_auth',
            'is_capture',
            'is_refunded',
            'is_standalone_payment',
            'is_voided',
            'order_id' => $obj['order']['id'] ?? '',
            'owner',
            'pending',
            'source_data_pan' => $obj['source_data']['pan'] ?? '',
            'source_data_sub_type' => $obj['source_data']['sub_type'] ?? '',
            'source_data_type' => $obj['source_data']['type'] ?? '',
            'success',
        ];

        $concatenatedString = '';
        foreach ($keys as $key => $val) {
            if (is_int($key)) {
                $v = $obj[$val] ?? '';
                if ($v === true) $v = 'true';
                if ($v === false) $v = 'false';
                $concatenatedString .= $v;
            } else {
                $concatenatedString .= $val;
            }
        }

        return hash_hmac('sha512', $concatenatedString, $this->hmacSecret);
    }

    public function test_successful_payment_transitions_order_to_confirmed(): void
    {
        $owner = User::factory()->create(['role' => UserRole::RestaurantOwner]);
        $restaurant = Restaurant::factory()->create(['user_id' => $owner->id]);
        $customer = User::factory()->create(['role' => UserRole::Customer]);

        $order = Order::factory()->create([
            'user_id' => $customer->id,
            'restaurant_id' => $restaurant->id,
            'status' => OrderStatus::Placed
        ]);

        $obj = [
            'id' => 999888,
            'pending' => false,
            'amount_cents' => 10000,
            'success' => true,
            'is_auth' => false,
            'is_capture' => false,
            'is_standalone_payment' => true,
            'is_voided' => false,
            'is_refunded' => false,
            'is_3d_secure' => true,
            'integration_id' => 123456,
            'profile_id' => 789,
            'has_parent_transaction' => false,
            'order' => [
                'id' => 555666,
                'merchant_order_id' => "{$order->id}-random123"
            ],
            'created_at' => now()->toIso8601String(),
            'currency' => 'EGP',
            'error_occured' => false,
            'owner' => 112233,
            'source_data' => [
                'pan' => '0000',
                'sub_type' => 'MasterCard',
                'type' => 'card'
            ]
        ];

        $hmac = $this->generateHmac($obj);

        $response = $this->postJson('/api/v1/webhooks/paymob?hmac=' . $hmac, [
            'type' => 'TRANSACTION',
            'obj' => $obj
        ]);

        $response->assertStatus(200);

        $order->refresh();
        $this->assertEquals(OrderStatus::Confirmed, $order->status);
        $this->assertEquals(999888, $order->payment_reference);
    }

    public function test_invalid_hmac_is_rejected(): void
    {
        $response = $this->postJson('/api/v1/webhooks/paymob?hmac=invalid_hash', [
            'type' => 'TRANSACTION',
            'obj' => []
        ]);

        $response->assertStatus(401);
        $response->assertJson(['error' => 'Invalid HMAC']);
    }
}
