<?php

namespace App\Policies;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Any authenticated user can view orders list (filtered by role in controller).
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Users can view an order if they are the customer, the assigned rider,
     * the restaurant owner, or an admin.
     */
    public function view(User $user, Order $order): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isCustomer() && $order->user_id === $user->id) {
            return true;
        }

        if ($user->isRider() && $order->rider_id === $user->id) {
            return true;
        }

        if ($user->isRestaurantOwner()) {
            return $user->restaurantsOwned()->where('id', $order->restaurant_id)->exists();
        }

        return false;
    }

    /**
     * Only customers can create orders.
     */
    public function create(User $user): bool
    {
        return $user->isCustomer();
    }

    /**
     * Determine who can update order status.
     * The OrderStateMachine handles granular role checks per transition.
     */
    public function updateStatus(User $user, Order $order): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // Customer can only cancel their own orders
        if ($user->isCustomer()) {
            return $order->user_id === $user->id;
        }

        // Restaurant owner can update orders for their restaurant
        if ($user->isRestaurantOwner()) {
            return $user->restaurantsOwned()->where('id', $order->restaurant_id)->exists();
        }

        // Rider can update orders assigned to them
        if ($user->isRider()) {
            return $order->rider_id === $user->id;
        }

        return false;
    }

    /**
     * Only customers who own the order can review it.
     */
    public function review(User $user, Order $order): bool
    {
        return $user->isCustomer()
            && $order->user_id === $user->id
            && $order->status === OrderStatus::Delivered;
    }
}
