<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateRiderLocationRequest;
use App\Http\Resources\OrderResource;
use App\Events\RiderLocationUpdated;
use App\Models\Order;
use App\Enums\OrderStatus;
use App\Services\DispatchService;
use App\Services\OrderService;
use App\Services\PayoutService;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RiderController extends Controller
{
    public function __construct(
        private readonly DispatchService $dispatchService,
        private readonly PayoutService $payoutService,
        private readonly ReviewService $reviewService,
        private readonly OrderService $orderService,
    ) {}

    /**
     * Rider dashboard data.
     */
    public function dashboard(Request $request): JsonResponse
    {
        $rider = $request->user();

        $activeOrders = Order::where('rider_id', $rider->id)
            ->active()
            ->with(['user', 'restaurant', 'items.menuItem'])
            ->latest()
            ->get();

        $todayDeliveries = Order::where('rider_id', $rider->id)
            ->where('status', OrderStatus::Delivered)
            ->whereDate('delivered_at', today())
            ->count();

        $earnings = $this->payoutService->getRiderEarnings($rider->id);
        $rating = $this->reviewService->getRiderRatingSummary($rider->id);

        return response()->json([
            'success' => true,
            'data'    => [
                'active_orders'     => OrderResource::collection($activeOrders),
                'today_deliveries'  => $todayDeliveries,
                'earnings'          => $earnings,
                'rating'            => $rating,
                'location'          => $rider->riderLocation,
            ],
        ]);
    }

    /**
     * Get available orders nearby.
     */
    public function availableOrders(Request $request): JsonResponse
    {
        $rider = $request->user();
        $location = $rider->riderLocation;

        if (!$location) {
            return response()->json([
                'success' => false,
                'message' => 'Please update your location first.',
            ], 422);
        }

        $orders = $this->orderService->getAvailableOrders(
            $location->latitude,
            $location->longitude,
            $request->query('radius', 50.0)
        );

        return response()->json([
            'success' => true,
            'data'    => OrderResource::collection($orders),
        ]);
    }

    /**
     * Accept an available order.
     */
    public function acceptOrder(Request $request, int $id): JsonResponse
    {
        $order = Order::findOrFail($id);

        // Prevent double assignment/race condition
        if ($order->rider_id !== null) {
            return response()->json([
                'success' => false,
                'message' => 'Order already assigned to another rider.',
            ], 422);
        }

        try {
            // Assign rider manually
            $order->update(['rider_id' => $request->user()->id]);
            
            $this->orderService->updateStatus(
                $order,
                OrderStatus::RiderAssigned,
                $request->user(),
                'Order accepted by rider.'
            );

            return response()->json([
                'success' => true,
                'message' => 'Order accepted.',
                'data'    => new OrderResource($order->load(['user', 'restaurant'])),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Mark order as picked up.
     */
    public function pickupOrder(Request $request, int $id): JsonResponse
    {
        $order = Order::where('rider_id', $request->user()->id)->findOrFail($id);

        try {
            $this->orderService->updateStatus(
                $order,
                OrderStatus::PickedUp,
                $request->user(),
                'Rider picked up the order.'
            );

            return response()->json([
                'success' => true,
                'message' => 'Order picked up.',
                'data'    => new OrderResource($order->load(['user', 'restaurant'])),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Mark order as delivered.
     */
    public function deliverOrder(Request $request, int $id): JsonResponse
    {
        $order = Order::where('rider_id', $request->user()->id)->findOrFail($id);

        try {
            $this->orderService->updateStatus(
                $order,
                OrderStatus::Delivered,
                $request->user(),
                'Order delivered successfully.'
            );

            // Release rider
            $this->dispatchService->releaseRider($request->user());

            return response()->json([
                'success' => true,
                'message' => 'Order delivered.',
                'data'    => new OrderResource($order->load(['user', 'restaurant'])),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Get rider history.
     */
    public function history(Request $request): JsonResponse
    {
        $rider = $request->user();
        
        $orders = Order::where('rider_id', $rider->id)
            ->whereIn('status', [OrderStatus::Delivered, OrderStatus::Cancelled])
            ->with(['user', 'restaurant'])
            ->latest()
            ->paginate(20);

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
     * Update rider's live GPS location.
     */
    public function updateLocation(UpdateRiderLocationRequest $request): JsonResponse
    {
        $rider = $request->user();

        $location = $this->dispatchService->updateRiderLocation(
            $rider,
            $request->latitude,
            $request->longitude,
            $request->availability
        );

        // Get active order ID for broadcasting
        $activeOrder = Order::where('rider_id', $rider->id)->active()->first();
        RiderLocationUpdated::dispatch($location, $activeOrder?->id);

        return response()->json([
            'success' => true,
            'message' => 'Location updated.',
            'data'    => $location,
        ]);
    }

    /**
     * Get rider earnings.
     */
    public function earnings(Request $request): JsonResponse
    {
        $earnings = $this->payoutService->getRiderEarnings($request->user()->id);

        return response()->json([
            'success' => true,
            'data'    => $earnings,
        ]);
    }

    /**
     * Get current delivery details.
     */
    public function delivery(Request $request, int $id): JsonResponse
    {
        $order = Order::where('rider_id', $request->user()->id)
            ->with(['user', 'restaurant', 'items.menuItem'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => new OrderResource($order),
        ]);
    }
}
