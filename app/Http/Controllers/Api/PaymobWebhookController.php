<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Services\OrderStateMachine;
use App\Services\PayoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PaymobWebhookController extends Controller
{
    public function handleCallback(Request $request, OrderStateMachine $stateMachine, PayoutService $payoutService)
    {
        // ... (existing handleCallback logic)
    }

    /**
     * Handle the frontend redirection (GET callback) from Paymob.
     * This serves as a fallback if the asynchronous webhook is delayed.
     */
    public function checkoutProcessed(Request $request, OrderStateMachine $stateMachine)
    {
        $success = $request->query('success');
        $merchantOrderId = $request->query('merchant_order_id');
        $transactionId = $request->query('id');

        if (!$merchantOrderId) {
            return redirect('/orders')->with('error', 'Invalid order reference.');
        }

        $parts = explode('-', $merchantOrderId);
        $orderId = $parts[0];
        $order = Order::find($orderId);

        if (!$order) {
            return redirect('/orders')->with('error', 'Order not found.');
        }

        if ($success === 'true') {
            // Only transition if still pending, to avoid double-processing
            if ($order->status === OrderStatus::PaymentPending) {
                $systemUser = User::where('email', 'admin@delivereats.com')->first();
                try {
                    $order->update(['payment_reference' => $transactionId]);
                    $stateMachine->transition($order, OrderStatus::Pending, $systemUser, "Payment confirmed via frontend redirect.");
                } catch (\Exception $e) {
                    Log::error('Frontend Callback Transition Error: ' . $e->getMessage());
                }
            }
            return redirect("/orders/{$orderId}/track")->with('success', 'Payment successful!');
        }

        return redirect('/orders')->with('error', 'Payment failed or was cancelled.');
    }

    private function verifyHmac(array $requestData, ?string $requestHmac): bool
    {
        if (!$requestHmac) return false;

        $secret = config('services.paymob.hmac');
        if (!$secret) {
            \Illuminate\Support\Facades\Log::warning('Paymob HMAC secret not configured. Bypassing HMAC verification for development.');
            return true;
        }

        $obj = $requestData['obj'] ?? [];
        $order = $obj['order'] ?? [];

        // Lexicographical string concatenated according to Paymob documentation
        // Note: boolean values MUST be literal 'true' or 'false' strings
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
            'order_id' => $order['id'] ?? '',
            'owner',
            'pending',
            'source_data_pan' => $obj['source_data']['pan'] ?? '',
            'source_data_sub_type' => $obj['source_data']['sub_type'] ?? '',
            'source_data_type' => $obj['source_data']['type'] ?? '',
            'success',
        ];

        $concatenatedString = '';
        foreach ($keys as $key => $val) {
            $v = is_int($key) ? ($obj[$val] ?? '') : $val;
            
            if ($v === true || $v === 'true') $v = 'true';
            elseif ($v === false || $v === 'false') $v = 'false';
            
            $concatenatedString .= $v;
        }

        $hashed = hash_hmac('sha512', $concatenatedString, $secret);
        
        if (!hash_equals($hashed, $requestHmac)) {
            Log::debug('HMAC Calculation Debug:', [
                'concatenated' => $concatenatedString,
                'calculated' => $hashed,
                'received' => $requestHmac
            ]);
            return false;
        }

        return true;
    }
}
