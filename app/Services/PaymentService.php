<?php

namespace App\Services;

use App\Contracts\PaymentGatewayInterface;
use App\Enums\PaymentStatus;
use App\Events\PaymentProcessed;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct(
        private readonly PaymentGatewayInterface $paymentGateway,
    ) {}

    /**
     * Process payment confirmation (webhook or manual).
     */
    public function confirmPayment(Order $order): Payment
    {
        $payment = $order->payment;

        if (! $payment) {
            throw new \RuntimeException("No payment record found for Order #{$order->id}.");
        }

        if ($payment->payment_intent_id) {
            $confirmed = $this->paymentGateway->confirmPayment($payment->payment_intent_id);

            if (! $confirmed) {
                $payment->update(['status' => PaymentStatus::Failed]);
                Log::error("Payment confirmation failed for Order #{$order->id}");

                PaymentProcessed::dispatch($order, $payment->refresh());

                throw new \RuntimeException('Payment confirmation failed.');
            }
        }

        $payment->update([
            'status'  => PaymentStatus::Succeeded,
            'paid_at' => now(),
        ]);

        PaymentProcessed::dispatch($order, $payment->refresh());

        return $payment;
    }

    /**
     * Mark cash payment as collected.
     */
    public function markCashCollected(Order $order): Payment
    {
        $payment = $order->payment;

        $payment->update([
            'status'  => PaymentStatus::Succeeded,
            'paid_at' => now(),
        ]);

        PaymentProcessed::dispatch($order, $payment->refresh());

        return $payment;
    }

    /**
     * Refund a payment.
     */
    public function refund(Order $order): Payment
    {
        $payment = $order->payment;

        if ($payment->payment_intent_id) {
            $this->paymentGateway->refund($payment->payment_intent_id);
        }

        $payment->update([
            'status'      => PaymentStatus::Refunded,
            'refunded_at' => now(),
        ]);

        PaymentProcessed::dispatch($order, $payment->refresh());

        return $payment;
    }

    /**
     * Calculate payment split for an order.
     *
     * @return array{platform: float, restaurant: float, rider: float}
     */
    public function calculateSplit(Order $order): array
    {
        $subtotal    = (float) $order->subtotal;
        $deliveryFee = (float) $order->delivery_fee;
        $tax         = (float) $order->tax;

        // Platform takes 15% commission on food subtotal + 10% on delivery fee
        $platformFromFood     = round($subtotal * 0.15, 2);
        $platformFromDelivery = round($deliveryFee * 0.10, 2);
        $platformTotal        = $platformFromFood + $platformFromDelivery + $tax;

        $restaurantShare = round($subtotal - $platformFromFood, 2);
        $riderShare      = round($deliveryFee - $platformFromDelivery, 2);

        return [
            'platform'   => round($platformTotal, 2),
            'restaurant' => $restaurantShare,
            'rider'      => $riderShare,
            'total'      => (float) $order->total,
        ];
    }
}
