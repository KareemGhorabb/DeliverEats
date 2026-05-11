<?php

namespace App\Services;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Refund;
use Stripe\Stripe;
use Stripe\Transfer;

class StripePaymentGateway implements PaymentGatewayInterface
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create a Stripe PaymentIntent for the order.
     */
    public function createPaymentIntent(Order $order, string $currency = 'EGP'): array
    {
        try {
            $intent = PaymentIntent::create([
                'amount'   => (int) round($order->total * 100),
                'currency' => strtolower($currency),
                'metadata' => [
                    'order_id'      => $order->id,
                    'customer_name' => $order->user->name ?? 'Guest',
                    'restaurant'    => $order->restaurant->name ?? 'Unknown',
                ],
                'description' => "DeliverEats Order #{$order->id}",
            ]);

            Log::info('Stripe PaymentIntent created', [
                'order_id'   => $order->id,
                'intent_id'  => $intent->id,
                'amount'     => $intent->amount,
            ]);

            return [
                'payment_intent_id' => $intent->id,
                'client_secret'     => $intent->client_secret,
            ];
        } catch (ApiErrorException $e) {
            Log::error('Stripe createPaymentIntent failed', [
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Confirm a Stripe payment.
     */
    public function confirmPayment(string $paymentIntentId): bool
    {
        try {
            $intent = PaymentIntent::retrieve($paymentIntentId);

            return $intent->status === 'succeeded';
        } catch (ApiErrorException $e) {
            Log::error('Stripe confirmPayment failed', [
                'intent_id' => $paymentIntentId,
                'error'     => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Refund a Stripe payment.
     */
    public function refund(string $paymentIntentId, ?float $amount = null): bool
    {
        try {
            $params = ['payment_intent' => $paymentIntentId];

            if ($amount !== null) {
                $params['amount'] = (int) round($amount * 100);
            }

            Refund::create($params);

            Log::info('Stripe refund processed', [
                'intent_id' => $paymentIntentId,
                'amount'    => $amount,
            ]);

            return true;
        } catch (ApiErrorException $e) {
            Log::error('Stripe refund failed', [
                'intent_id' => $paymentIntentId,
                'error'     => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Transfer funds to a Stripe connected account.
     */
    public function transferToConnectedAccount(string $connectedAccountId, float $amount, string $currency = 'EGP'): string
    {
        try {
            $transfer = Transfer::create([
                'amount'      => (int) round($amount * 100),
                'currency'    => strtolower($currency),
                'destination' => $connectedAccountId,
                'description' => 'DeliverEats restaurant/rider payout',
            ]);

            Log::info('Stripe transfer created', [
                'transfer_id' => $transfer->id,
                'destination' => $connectedAccountId,
                'amount'      => $amount,
            ]);

            return $transfer->id;
        } catch (ApiErrorException $e) {
            Log::error('Stripe transfer failed', [
                'destination' => $connectedAccountId,
                'error'       => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
