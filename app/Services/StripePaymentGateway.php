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
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => strtolower($currency),
                        'product_data' => [
                            'name' => "DeliverEats Order #{$order->id}",
                        ],
                        'unit_amount' => (int) round($order->total * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('stripe.success', ['order' => $order->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => url('/checkout'),
                'metadata' => [
                    'order_id' => $order->id,
                ],
            ]);

            Log::info('Stripe Checkout Session created', [
                'order_id'   => $order->id,
                'session_id' => $session->id,
            ]);

            return [
                'payment_intent_id' => $session->id, // We'll store the session ID here
                'client_secret'     => $session->url, // The frontend will redirect here
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
            // $paymentIntentId is actually the Checkout Session ID (cs_test_...)
            $session = \Stripe\Checkout\Session::retrieve($paymentIntentId);

            return $session->payment_status === 'paid';
        } catch (ApiErrorException $e) {
            Log::error('Stripe confirmPayment failed', [
                'session_id' => $paymentIntentId,
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
            $session = \Stripe\Checkout\Session::retrieve($paymentIntentId);
            $params = ['payment_intent' => $session->payment_intent];

            if ($amount !== null) {
                $params['amount'] = (int) round($amount * 100);
            }

            Refund::create($params);

            Log::info('Stripe refund processed', [
                'session_id' => $paymentIntentId,
                'amount'    => $amount,
            ]);

            return true;
        } catch (ApiErrorException $e) {
            Log::error('Stripe refund failed', [
                'session_id' => $paymentIntentId,
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
