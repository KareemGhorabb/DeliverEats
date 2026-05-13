<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Services\OrderStateMachine;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeWebhookController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService,
        private readonly OrderStateMachine $stateMachine
    ) {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Handle the Stripe success redirect.
     */
    public function handleSuccess(Request $request, int $orderId)
    {
        $sessionId = $request->query('session_id');
        $order = Order::findOrFail($orderId);

        if ($order->status === OrderStatus::PaymentPending) {
            try {
                // Verify session with Stripe
                $session = Session::retrieve($sessionId);

                if ($session->payment_status === 'paid') {
                    $systemUser = User::where('email', 'admin@delivereats.com')->first();
                    
                    // Update payment record via service
                    $this->paymentService->confirmPayment($order);

                    // Transition order state
                    $this->stateMachine->transition(
                        $order,
                        OrderStatus::Pending,
                        $systemUser,
                        "Payment confirmed via Stripe redirect (Session: {$sessionId})"
                    );
                }
            } catch (\Exception $e) {
                Log::error('Stripe Success Redirect Error: ' . $e->getMessage(), [
                    'order_id' => $orderId,
                    'session_id' => $sessionId
                ]);
                return redirect()->route('customer.orders.track', $orderId)
                    ->with('error', 'There was an issue confirming your payment. Please contact support.');
            }
        }

        return redirect()->route('customer.orders.track', $orderId)
            ->with('success', 'Payment successful!');
    }

    /**
     * Handle Stripe Webhooks.
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $orderId = $session->metadata->order_id ?? null;

            if ($orderId) {
                $order = Order::find($orderId);
                if ($order && $order->status === OrderStatus::PaymentPending) {
                    $systemUser = User::where('email', 'admin@delivereats.com')->first();
                    
                    try {
                        $this->paymentService->confirmPayment($order);
                        $this->stateMachine->transition(
                            $order,
                            OrderStatus::Pending,
                            $systemUser,
                            "Payment confirmed via Stripe Webhook"
                        );
                    } catch (\Exception $e) {
                        Log::error('Stripe Webhook Transition Error: ' . $e->getMessage());
                    }
                }
            }
        }

        return response()->json(['status' => 'success']);
    }
}
