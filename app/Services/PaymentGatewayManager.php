<?php

namespace App\Services;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

/**
 * Payment Gateway Manager with failover support.
 *
 * Attempts the primary gateway first (Paymob), and if it fails,
 * falls back to the secondary gateway (Stripe). This ensures
 * payment resilience without interrupting the customer experience.
 */
class PaymentGatewayManager implements PaymentGatewayInterface
{
    private PaymentGatewayInterface $primary;
    private PaymentGatewayInterface $fallback;

    public function __construct()
    {
        $this->primary  = app(PaymobPaymentGateway::class);
        $this->fallback = app(StripePaymentGateway::class);
    }

    /**
     * Create a payment intent using primary gateway with fallback.
     */
    public function createPaymentIntent(Order $order, string $currency = 'EGP'): array
    {
        try {
            $result = $this->primary->createPaymentIntent($order, $currency);

            // If Paymob returned a mock/error token, treat it as a failure
            if (empty($result['client_secret']) || str_starts_with($result['payment_intent_id'], 'mock_err_')) {
                throw new \RuntimeException('Primary gateway returned invalid response.');
            }

            Log::info('Payment processed via primary gateway (Paymob)', ['order_id' => $order->id]);

            // Tag the payment method on the order for frontend routing
            $order->update(['payment_gateway' => 'paymob']);

            return $result;
        } catch (\Throwable $e) {
            Log::warning('Primary gateway (Paymob) failed, falling back to Stripe', [
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
            ]);
        }

        try {
            $result = $this->fallback->createPaymentIntent($order, $currency);

            Log::info('Payment processed via fallback gateway (Stripe)', ['order_id' => $order->id]);

            $order->update(['payment_gateway' => 'stripe']);

            return $result;
        } catch (\Throwable $e) {
            Log::error('All payment gateways failed', [
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
            ]);

            throw new \RuntimeException('Payment processing is temporarily unavailable. Please try again later.');
        }
    }

    /**
     * Confirm payment — delegates to the correct gateway based on intent prefix.
     */
    public function confirmPayment(string $paymentIntentId): bool
    {
        if (str_starts_with($paymentIntentId, 'pi_')) {
            return $this->fallback->confirmPayment($paymentIntentId);
        }

        return $this->primary->confirmPayment($paymentIntentId);
    }

    /**
     * Refund — delegates to the correct gateway based on intent prefix.
     */
    public function refund(string $paymentIntentId, ?float $amount = null): bool
    {
        if (str_starts_with($paymentIntentId, 'pi_')) {
            return $this->fallback->refund($paymentIntentId, $amount);
        }

        return $this->primary->refund($paymentIntentId, $amount);
    }

    /**
     * Transfer to connected account — always via Stripe for payout support.
     */
    public function transferToConnectedAccount(string $connectedAccountId, float $amount, string $currency = 'EGP'): string
    {
        return $this->fallback->transferToConnectedAccount($connectedAccountId, $amount, $currency);
    }
}
