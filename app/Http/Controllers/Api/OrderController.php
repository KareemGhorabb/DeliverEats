<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService,
    ) {}

    /**
     * Place a new order (Customer).
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        try {
            $order = $this->orderService->placeOrder(
                $request->user(),
                $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully.',
                'data'    => new OrderResource($order),
            ], 201);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Order Placement Failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * List orders for the authenticated user (role-aware).
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $status = $request->query('status');

        if ($user->isCustomer()) {
            $orders = $this->orderService->getCustomerOrders($user, $status);
            return response()->json([
                'success' => true,
                'data'    => OrderResource::collection($orders),
            ]);
        } elseif ($user->isRider()) {
            $orders = $this->orderService->getRiderOrders($user);
            return response()->json([
                'success' => true,
                'data'    => OrderResource::collection($orders),
            ]);
        } elseif ($user->isRestaurantOwner()) {
            $restaurantId = $request->query('restaurant_id');
            $restaurantIds = [];
            
            if (! $restaurantId) {
                $restaurantIds = $user->restaurantsOwned()->pluck('id')->toArray();
                if (empty($restaurantIds)) {
                    \Illuminate\Support\Facades\Log::info("Restaurant dashboard: User {$user->id} owns no restaurants.");
                    return response()->json(['success' => true, 'data' => [], 'meta' => ['total' => 0]]);
                }
                $orders = $this->orderService->getRestaurantOrders($restaurantIds, $status);
            } else {
                $restaurantIds = [(int) $restaurantId];
                $orders = $this->orderService->getRestaurantOrders((int) $restaurantId, $status);
            }

            \Illuminate\Support\Facades\Log::info("Restaurant dashboard: User {$user->id} viewing restaurants " . implode(',', $restaurantIds) . ". Found {$orders->total()} orders.");

            // For dashboard, we often prefer a flatter structure if pagination isn't strictly used by the JS
            return response()->json([
                'success' => true,
                'data'    => OrderResource::collection($orders->items())->toArray($request),
                'meta'    => [
                    'current_page' => $orders->currentPage(),
                    'last_page'    => $orders->lastPage(),
                    'total'        => $orders->total(),
                ],
            ]);
        } else {
            // Admin - see all
            $query = Order::with(['user', 'restaurant', 'rider', 'items.menuItem', 'payment'])->latest();
            if ($status) {
                $query->where('status', $status);
            }
            $orders = $query->paginate(20);
        }

        return response()->json([
            'success' => true,
            'data'    => OrderResource::collection($orders),
            'meta'    => [
                'current_page' => $orders->currentPage(),
                'last_page'    => $orders->lastPage(),
                'total'        => $orders->total(),
            ],
        ]);
    }

    /**
     * Show a single order.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $order = Order::with([
            'user', 'restaurant', 'rider',
            'items.menuItem', 'items.itemVariant',
            'payment', 'histories.changedBy', 'review',
        ])->findOrFail($id);

        $user = $request->user();
        if (! $user->isAdmin()) {
            if ($user->isCustomer() && $order->user_id !== $user->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
            }
            if ($user->isRestaurantOwner()) {
                if (! $user->restaurantsOwned()->where('id', $order->restaurant_id)->exists()) {
                    return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
                }
                // Restaurants cannot see orders awaiting payment
                if ($order->status === OrderStatus::PaymentPending) {
                    return response()->json(['success' => false, 'message' => 'Order is awaiting payment confirmation.'], 403);
                }
            }
            if ($user->isRider() && $order->rider_id !== $user->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
            }
        }

        return response()->json([
            'success' => true,
            'data'    => new OrderResource($order),
        ]);
    }

    /**
     * Update order status (with FSM guards).
     */
    public function updateStatus(UpdateOrderStatusRequest $request, int $id): JsonResponse
    {
        $order = Order::findOrFail($id);
        
        $user = $request->user();
        if (! $user->isAdmin()) {
            if ($user->isRestaurantOwner() && ! $user->restaurantsOwned()->where('id', $order->restaurant_id)->exists()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
            }
            if ($user->isRider() && $order->rider_id !== $user->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
            }
            if ($user->isCustomer() && $order->user_id !== $user->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
            }
        }

        $newStatus = OrderStatus::from($request->status);

        try {
            $note = $request->cancellation_reason ?? null;
            $order = $this->orderService->updateStatus($order, $newStatus, $request->user(), $note);

            return response()->json([
                'success' => true,
                'message' => "Order status updated to: {$newStatus->label()}",
                'data'    => new OrderResource($order->load(['user', 'restaurant', 'rider'])),
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 403);
        }
    }

    /**
     * Get order transition history.
     */
    public function history(int $id): JsonResponse
    {
        $order = Order::with('histories.changedBy')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $order->histories->map(fn ($h) => [
                'from_status' => $h->from_status,
                'to_status'   => $h->to_status,
                'changed_by'  => $h->changedBy?->name ?? 'System',
                'note'        => $h->note,
                'created_at'  => $h->created_at->toISOString(),
            ]),
        ]);
    }

    /**
     * Cancel an order.
     */
    public function cancel(Request $request, int $id): JsonResponse
    {
        $order = Order::findOrFail($id);
        
        $user = $request->user();
        if (! $user->isAdmin()) {
            if ($user->isCustomer() && $order->user_id !== $user->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
            }
            if ($user->isRestaurantOwner() && ! $user->restaurantsOwned()->where('id', $order->restaurant_id)->exists()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
            }
        }

        try {
            $reason = $request->input('reason', 'Customer requested cancellation.');
            $order = $this->orderService->cancelOrder($order, $request->user(), $reason);

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully.',
                'data'    => new OrderResource($order),
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
