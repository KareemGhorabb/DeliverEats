<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReviewService
{
    /**
     * Submit a review for a delivered order.
     */
    public function submitReview(User $customer, Order $order, array $data): Review
    {
        // Validate order belongs to customer
        if ($order->user_id !== $customer->id) {
            throw new \InvalidArgumentException('You can only review your own orders.');
        }

        // Validate order is delivered
        if ($order->status !== OrderStatus::Delivered) {
            throw new \InvalidArgumentException('You can only review delivered orders.');
        }

        // Check for existing review
        $existing = Review::where('user_id', $customer->id)
            ->where('order_id', $order->id)
            ->first();

        if ($existing) {
            throw new \InvalidArgumentException('You have already reviewed this order.');
        }

        return DB::transaction(function () use ($customer, $order, $data) {
            $review = Review::create([
                'user_id'            => $customer->id,
                'restaurant_id'      => $order->restaurant_id,
                'order_id'           => $order->id,
                'rider_id'           => $order->rider_id,
                'restaurant_rating'  => $data['restaurant_rating'],
                'restaurant_comment' => $data['restaurant_comment'] ?? null,
                'rider_rating'       => $data['rider_rating'] ?? null,
                'rider_comment'      => $data['rider_comment'] ?? null,
            ]);

            // Update restaurant aggregate rating
            $this->updateRestaurantRating($order->restaurant_id);

            return $review;
        });
    }

    /**
     * Recalculate restaurant's average rating.
     */
    private function updateRestaurantRating(int $restaurantId): void
    {
        $stats = Review::where('restaurant_id', $restaurantId)
            ->selectRaw('AVG(restaurant_rating) as avg_rating, COUNT(*) as total')
            ->first();

        Restaurant::where('id', $restaurantId)->update([
            'avg_rating'    => round($stats->avg_rating ?? 0, 2),
            'total_reviews' => $stats->total ?? 0,
        ]);
    }

    /**
     * Get reviews for a restaurant.
     */
    public function getRestaurantReviews(int $restaurantId)
    {
        return Review::where('restaurant_id', $restaurantId)
            ->with('user')
            ->latest()
            ->paginate(15);
    }

    /**
     * Get rider's rating summary.
     */
    public function getRiderRatingSummary(int $riderId): array
    {
        $stats = Review::where('rider_id', $riderId)
            ->whereNotNull('rider_rating')
            ->selectRaw('AVG(rider_rating) as avg_rating, COUNT(*) as total')
            ->first();

        return [
            'avg_rating'    => round($stats->avg_rating ?? 0, 2),
            'total_reviews' => $stats->total ?? 0,
        ];
    }
}
