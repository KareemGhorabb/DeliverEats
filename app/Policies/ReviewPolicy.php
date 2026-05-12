<?php

namespace App\Policies;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    /**
     * Any authenticated user can view reviews.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Customers can create reviews only for their own delivered orders.
     */
    public function create(User $user, Order $order): bool
    {
        return $user->isCustomer()
            && $order->user_id === $user->id
            && $order->status === OrderStatus::Delivered
            && ! Review::where('user_id', $user->id)->where('order_id', $order->id)->exists();
    }

    /**
     * Users can view any review.
     */
    public function view(User $user, Review $review): bool
    {
        return true;
    }

    /**
     * Only the review author can update their review.
     */
    public function update(User $user, Review $review): bool
    {
        return $user->id === $review->user_id;
    }

    /**
     * Admins can delete reviews.
     */
    public function delete(User $user, Review $review): bool
    {
        return $user->isAdmin();
    }
}
