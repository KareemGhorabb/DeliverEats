<?php

namespace App\Services;

use App\Contracts\PaymentGatewayInterface;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Events\OrderCreated;
use App\Events\OrderStatusChanged;
use App\Jobs\AssignRiderJob;
use App\Jobs\RecalculateSurgeJob;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        private readonly OrderStateMachine $stateMachine,
        private readonly SurgeService $surgeService,
        private readonly PaymentGatewayInterface $paymentGateway,
    ) {}

    /**
     * Place a new order from validated request data.
     */
    public function placeOrder(User $customer, array $data): Order
    {
        $restaurant = Restaurant::findOrFail($data['restaurant_id']);

        return DB::transaction(function () use ($customer, $restaurant, $data) {
            // Calculate pricing
            $pricing = $this->calculatePricing($restaurant, $data['items']);

            $initialStatus = \App\Enums\PaymentMethod::from($data['payment_method']) === \App\Enums\PaymentMethod::Cash 
                ? OrderStatus::Pending 
                : OrderStatus::PaymentPending;

            // Create the order
            $order = Order::create([
                'user_id'              => $customer->id,
                'restaurant_id'        => $restaurant->id,
                'status'               => $initialStatus,
                'subtotal'             => $pricing['subtotal'],
                'delivery_fee'         => $pricing['delivery_fee'],
                'surge_multiplier'     => $pricing['surge_multiplier'],
                'tax'                  => $pricing['tax'],
                'total'                => $pricing['total'],
                'delivery_address'     => $data['delivery_address'],
                'delivery_lat'         => $data['delivery_lat'] ?? null,
                'delivery_lng'         => $data['delivery_lng'] ?? null,
                'special_instructions' => $data['special_instructions'] ?? null,
            ]);

            // Create order items with price snapshots
            foreach ($data['items'] as $itemData) {
                $menuItem = MenuItem::with('itemVariants')->findOrFail($itemData['menu_item_id']);
                $variant = isset($itemData['item_variant_id'])
                    ? $menuItem->itemVariants->find($itemData['item_variant_id'])
                    : null;

                $unitPrice = $menuItem->price + ($variant?->price_modifier ?? 0);
                $quantity = $itemData['quantity'];

                OrderItem::create([
                    'order_id'         => $order->id,
                    'menu_item_id'     => $menuItem->id,
                    'item_variant_id'  => $variant?->id,
                    'quantity'         => $quantity,
                    'unit_price'       => $unitPrice,
                    'total_price'      => $unitPrice * $quantity,
                    'special_requests' => $itemData['special_requests'] ?? null,
                ]);
            }

            // Create payment record
            $paymentMethod = PaymentMethod::from($data['payment_method']);
            $paymentData = [
                'order_id' => $order->id,
                'method'   => $paymentMethod,
                'status'   => PaymentStatus::Pending,
                'amount'   => $pricing['total'],
            ];

            if ($paymentMethod === PaymentMethod::Cash) {
                $paymentData['status'] = PaymentStatus::Pending;
            } else {
                // Create payment intent via gateway
                $intent = $this->paymentGateway->createPaymentIntent($order);
                $paymentData['payment_intent_id'] = $intent['payment_intent_id'];
                $paymentData['status'] = PaymentStatus::Processing;
                $order->payment_token = $intent['client_secret'] ?? null;
            }

            Payment::create($paymentData);

            // Log initial state
            OrderHistory::create([
                'order_id'    => $order->id,
                'from_status' => null,
                'to_status'   => $initialStatus->value,
                'changed_by'  => $customer->id,
                'note'        => 'Order placed by customer.',
            ]);

            // Dispatch async jobs
            RecalculateSurgeJob::dispatch($restaurant->id);

            // Fire events
            $order = $order->load(['items.menuItem', 'items.itemVariant', 'payment', 'restaurant']);
            OrderCreated::dispatch($order);
            OrderStatusChanged::dispatch($order);

            return $order;
        });
    }

    /**
     * Transition order status with event firing.
     */
    public function updateStatus(Order $order, OrderStatus $newStatus, User $actor, ?string $note = null): Order
    {
        $order = $this->stateMachine->transition($order, $newStatus, $actor, $note);

        // If accepted, dispatch rider assignment
        if ($newStatus === OrderStatus::Accepted) {
            AssignRiderJob::dispatch($order->id);
        }

        // Fire real-time event
        OrderStatusChanged::dispatch($order);

        return $order;
    }

    /**
     * Cancel an order with reason tracking.
     */
    public function cancelOrder(Order $order, User $actor, ?string $reason = null): Order
    {
        $order = $this->stateMachine->transition(
            $order,
            OrderStatus::Cancelled,
            $actor,
            $reason ?? 'Order cancelled.'
        );

        // Update cancellation fields
        $order->update([
            'cancelled_at'        => now(),
            'cancellation_reason' => $reason,
        ]);

        // Fire event
        OrderStatusChanged::dispatch($order->refresh());

        return $order;
    }

    /**
     * Get full order history (transition log) for an order.
     */
    public function getOrderHistory(int $orderId): \Illuminate\Database\Eloquent\Collection
    {
        return OrderHistory::where('order_id', $orderId)
            ->with('changedBy')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get orders for a customer.
     */
    public function getCustomerOrders(User $customer, ?string $status = null)
    {
        $query = Order::where('user_id', $customer->id)
            ->with(['restaurant', 'items.menuItem', 'payment'])
            ->latest();

        if ($status) {
            $query->where('status', $status);
        }

        return $query->get();
    }

    /**
     * Get orders for a restaurant (or multiple restaurants).
     * Excludes PaymentPending orders and failed payments.
     */
    public function getRestaurantOrders(int|array $restaurantIds, ?string $status = null)
    {
        $query = Order::with(['user', 'items.menuItem', 'rider'])
            ->where('status', '!=', 'payment_pending')
            ->latest();

        // If filtering by cancelled, only show those that were actually paid or are cash
        if ($status === OrderStatus::Cancelled->value) {
            $query->where(function ($q) {
                $q->whereNotNull('confirmed_at') // Was at least accepted
                  ->orWhereHas('payment', function ($pq) {
                      $pq->where('method', 'cash')
                        ->orWhere('status', 'succeeded');
                  });
            });
        }

        if (is_array($restaurantIds)) {
            $query->whereIn('restaurant_id', $restaurantIds);
        } else {
            $query->where('restaurant_id', $restaurantIds);
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->paginate(20);
    }

    /**
     * Get active orders for a rider.
     */
    public function getRiderOrders(User $rider)
    {
        return Order::where('rider_id', $rider->id)
            ->active()
            ->with(['user', 'restaurant', 'items.menuItem'])
            ->latest()
            ->get();
    }

    /**
     * Get available orders for riders near their location.
     */
    public function getAvailableOrders(float $lat, float $lng, float $radiusKm = 50.0)
    {
        // Haversine formula to find orders within radius
        // We filter by: status=ReadyForPickup, rider_id=null
        return Order::with(['restaurant', 'user'])
            ->where('status', OrderStatus::ReadyForPickup)
            ->whereNull('rider_id')
            ->select('orders.*')
            ->join('restaurants', 'orders.restaurant_id', '=', 'restaurants.id')
            ->selectRaw(
                '(6371 * acos(cos(radians(?)) * cos(radians(restaurants.latitude)) * cos(radians(restaurants.longitude) - radians(?)) + sin(radians(?)) * sin(radians(restaurants.latitude)))) AS distance',
                [$lat, $lng, $lat]
            )
            ->having('distance', '<=', $radiusKm)
            ->orderBy('distance')
            ->get();
    }

    /**
     * Calculate order pricing.
     */
    private function calculatePricing(Restaurant $restaurant, array $items): array
    {
        $subtotal = 0;

        foreach ($items as $itemData) {
            $menuItem = MenuItem::findOrFail($itemData['menu_item_id']);
            $variant = isset($itemData['item_variant_id'])
                ? $menuItem->itemVariants()->find($itemData['item_variant_id'])
                : null;

            $unitPrice = $menuItem->price + ($variant?->price_modifier ?? 0);
            $subtotal += $unitPrice * $itemData['quantity'];
        }

        $surgeMultiplier = $this->surgeService->getCurrentMultiplier($restaurant->id);
        $baseDeliveryFee = $restaurant->delivery_fee ?? 25.00;
        $deliveryFee = $baseDeliveryFee * $surgeMultiplier; // Base delivery fee * surge
        $taxRate = 0.14; // 14% VAT (Egypt)
        $tax = round($subtotal * $taxRate, 2);
        $total = round($subtotal + $deliveryFee + $tax, 2);

        return [
            'subtotal'         => round($subtotal, 2),
            'delivery_fee'     => round($deliveryFee, 2),
            'surge_multiplier' => $surgeMultiplier,
            'tax'              => $tax,
            'total'            => $total,
        ];
    }
}
