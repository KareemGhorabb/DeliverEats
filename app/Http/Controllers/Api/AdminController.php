<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use App\Enums\OrderStatus;
use App\Services\PayoutService;
use App\Services\SurgeService;
use App\Services\Surge\MultiplierStrategy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct(
        private readonly PayoutService $payoutService,
        private readonly SurgeService $surgeService,
    ) {}

    /**
     * Admin dashboard overview.
     */
    public function dashboard(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => [
                'total_users'       => User::count(),
                'total_restaurants' => Restaurant::count(),
                'total_orders'      => Order::count(),
                'active_orders'     => Order::active()->count(),
                'delivered_today'   => Order::where('status', OrderStatus::Delivered)
                    ->whereDate('delivered_at', today())->count(),
                'revenue'           => $this->payoutService->getPlatformRevenue(),
            ],
        ]);
    }

    /**
     * Live order monitoring (control tower).
     */
    public function controlTower(): JsonResponse
    {
        $activeOrders = Order::active()
            ->with(['user', 'restaurant', 'rider', 'rider.riderLocation'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data'    => [
                'active_orders' => $activeOrders,
                'stats'         => [
                    'pending'          => $activeOrders->where('status', OrderStatus::Pending)->count(),
                    'accepted'         => $activeOrders->where('status', OrderStatus::Accepted)->count(),
                    'preparing'        => $activeOrders->where('status', OrderStatus::Preparing)->count(),
                    'ready_for_pickup' => $activeOrders->where('status', OrderStatus::ReadyForPickup)->count(),
                    'picked_up'        => $activeOrders->where('status', OrderStatus::PickedUp)->count(),
                ],
            ],
        ]);
    }

    /**
     * User management.
     */
    public function users(Request $request): JsonResponse
    {
        $query = User::query()->latest();

        if ($role = $request->query('role')) {
            $query->where('role', $role);
        }

        return response()->json([
            'success' => true,
            'data'    => $query->paginate(20),
        ]);
    }

    /**
     * Restaurant management.
     */
    public function restaurants(): JsonResponse
    {
        $restaurants = Restaurant::with('user')
            ->withCount('menuItems')
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data'    => $restaurants,
        ]);
    }

    /**
     * Surge pricing overview and control.
     */
    public function surgePricing(Request $request): JsonResponse
    {
        $restaurants = Restaurant::all();

        $surgeData = $restaurants->map(fn (Restaurant $r) => [
            'restaurant_id'   => $r->id,
            'restaurant_name' => $r->name,
            'breakdown'       => $this->surgeService->getBreakdown($r->id),
        ]);

        return response()->json([
            'success' => true,
            'data'    => $surgeData,
        ]);
    }

    /**
     * Set manual surge override.
     */
    public function setSurgeOverride(Request $request): JsonResponse
    {
        $request->validate([
            'restaurant_id' => ['required', 'exists:restaurants,id'],
            'multiplier'    => ['required', 'numeric', 'min:1', 'max:5'],
        ]);

        MultiplierStrategy::setOverride(
            $request->restaurant_id,
            $request->multiplier
        );

        // Recalculate
        $this->surgeService->recalculate($request->restaurant_id);

        return response()->json([
            'success' => true,
            'message' => "Surge override set to {$request->multiplier}x.",
        ]);
    }

    /**
     * All platform reviews.
     */
    public function reviews(Request $request): JsonResponse
    {
        $reviews = \App\Models\Review::with(['user', 'restaurant'])
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data'    => $reviews,
        ]);
    }
}
