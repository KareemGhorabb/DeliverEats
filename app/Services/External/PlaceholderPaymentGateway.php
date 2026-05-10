<?php

namespace App\Services\External;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Order;
use Illuminate\Support\Str;

/**
 * Placeholder payment gateway — simulates successful payments.
 *
 * TODO: Replace with Stripe Connect and/or Paymob for production.
 *
 * Required environment variables for Stripe:
 *   - STRIPE_KEY
 *   - STRIPE_SECRET
 *   - STRIPE_WEBHOOK_SECRET
 *
 * Required environment variables for Paymob:
 *   - PAYMOB_API_KEY
 *   - PAYMOB_INTEGRATION_ID
 *   - PAYMOB_IFRAME_ID
 *   - PAYMOB_HMAC_SECRET
 *
 * @see EXTERNAL_INTEGRATIONS_GUIDE.md
 */
class PlaceholderPaymentGateway implements PaymentGatewayInterface
{
    public function createPaymentIntent(Order $order, string $currency = 'EGP'): array
    {
        // TODO: Replace with actual Stripe/Paymob payment intent creation
        $fakeId = 'pi_placeholder_' . Str::random(24);

        return [
            'payment_intent_id' => $fakeId,
            'client_secret'     => $fakeId . '_secret_' . Str::random(12),
        ];
    }

    public function confirmPayment(string $paymentIntentId): bool
    {
        // TODO: Replace with actual Stripe/Paymob payment confirmation
        return true;
    }

    public function refund(string $paymentIntentId, ?float $amount = null): bool
    {
        // TODO: Replace with actual Stripe/Paymob refund
        return true;
    }

    public function transferToConnectedAccount(string $connectedAccountId, float $amount, string $currency = 'EGP'): string
    {
        // TODO: Replace with actual Stripe Connect transfer
        return 'tr_placeholder_' . Str::random(24);
    }
}
