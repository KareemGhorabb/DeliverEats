<?php

namespace App\Contracts;

use App\Models\Order;

/**
 * Contract for payment processing.
 *
 * TODO: Implement with Stripe Connect and/or Paymob.
 * See EXTERNAL_INTEGRATIONS_GUIDE.md for setup instructions.
 */
interface PaymentGatewayInterface
{
    /**
     * Create a payment intent for the order.
     *
     * @param Order $order
     * @param string $currency
     * @return array{payment_intent_id: string, client_secret: string}
     */
    public function createPaymentIntent(Order $order, string $currency = 'EGP'): array;

    /**
     * Confirm that a payment was successful.
     *
     * @param string $paymentIntentId
     * @return bool
     */
    public function confirmPayment(string $paymentIntentId): bool;

    /**
     * Refund a payment.
     *
     * @param string $paymentIntentId
     * @param float|null $amount Partial refund amount, null for full refund
     * @return bool
     */
    public function refund(string $paymentIntentId, ?float $amount = null): bool;

    /**
     * Transfer funds to a connected account (restaurant/rider payout).
     *
     * @param string $connectedAccountId
     * @param float $amount
     * @param string $currency
     * @return string Transfer ID
     */
    public function transferToConnectedAccount(string $connectedAccountId, float $amount, string $currency = 'EGP'): string;
}
