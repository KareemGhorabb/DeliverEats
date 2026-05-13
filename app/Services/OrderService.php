<?php

namespace App\Services;

use App\Jobs\AssignRiderJob;
use App\Jobs\RecalculateSurgeJob;
use App\Jobs\SendNotificationJob;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function __construct(private readonly SurgeService $surgeService)
    {
    }

    /**
     * Place a new order for a customer.
     *
     * Creates the order record, dispatches background jobs for rider assignment,
     * notifications, and surge pricing recalculation.
     *
     * @param  array  $data      Validated order data
     * @param  User   $customer  The authenticated customer
     * @return Order             The newly created order
     */
    public function placeOrder(array $data, User $customer): Order
    {
        // 1. Fetch current surge multiplier from Redis cache (or DB fallback)
        $surgeMultiplier = $this->surgeService->getCurrentMultiplier($data['restaurant_id']);

        // 2. Compute totals
        $subtotal    = $data['subtotal'];
        $tax         = round($subtotal * 0.14, 2);          // 14% tax
        $deliveryFee = $data['delivery_fee'] ?? 5.00;
        $total       = round(($subtotal + $tax + $deliveryFee) * $surgeMultiplier, 2);

        // 3. Create order inside a DB transaction
        $order = DB::transaction(function () use ($data, $customer, $subtotal, $tax, $deliveryFee, $total, $surgeMultiplier) {
            return Order::create([
                'user_id'              => $customer->id,
                'restaurant_id'        => $data['restaurant_id'],
                'status'               => 'pending',
                'subtotal'             => $subtotal,
                'delivery_fee'         => $deliveryFee,
                'surge_multiplier'     => $surgeMultiplier,
                'tax'                  => $tax,
                'total'                => $total,
                'delivery_address'     => $data['delivery_address'],
                'delivery_lat'         => $data['delivery_lat'] ?? null,
                'delivery_lng'         => $data['delivery_lng'] ?? null,
                'special_instructions' => $data['special_instructions'] ?? null,
            ]);
        });

        Log::info("OrderService: Order [{$order->id}] created for customer [{$customer->id}].");

        // 4. Dispatch background jobs to the Redis queues ──────────────────────

        // Assign a rider asynchronously
        AssignRiderJob::dispatch($order);

        // Confirm notification to customer
        SendNotificationJob::dispatch(
            $customer,
            'Order Confirmed! 🎉',
            "Your order #{$order->id} has been placed successfully. Total: \${$order->total}. We are finding you a rider!"
        );

        // Recalculate surge pricing for the restaurant (demand just increased)
        RecalculateSurgeJob::dispatch($data['restaurant_id']);

        return $order;
    }
}
