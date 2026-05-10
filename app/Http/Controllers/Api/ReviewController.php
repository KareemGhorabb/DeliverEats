<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Order;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(
        private readonly ReviewService $reviewService,
    ) {}

    /**
     * Submit a review for a delivered order.
     */
    public function store(StoreReviewRequest $request, int $orderId): JsonResponse
    {
        $order = Order::with('restaurant')->findOrFail($orderId);
        $user = $request->user();

        // Security check
        if ($order->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        if ($order->status !== \App\Enums\OrderStatus::Delivered) {
            return response()->json(['success' => false, 'message' => 'You can only review delivered orders.'], 422);
        }

        try {
            $data = $request->validated();
            $data['rider_id'] = $order->rider_id;
            $data['restaurant_id'] = $order->restaurant_id;

            $review = $this->reviewService->submitReview(
                $user,
                $order,
                $data
            );

            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully.',
                'data'    => new ReviewResource($review),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Get reviews for a restaurant.
     */
    public function restaurantReviews(int $restaurantId): JsonResponse
    {
        $reviews = $this->reviewService->getRestaurantReviews($restaurantId);

        return response()->json([
            'success' => true,
            'data'    => ReviewResource::collection($reviews),
            'meta'    => [
                'current_page' => $reviews->currentPage(),
                'last_page'    => $reviews->lastPage(),
                'total'        => $reviews->total(),
            ],
        ]);
    }
}
