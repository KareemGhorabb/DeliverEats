<?php

namespace App\Policies;

use App\Models\Restaurant;
use App\Models\User;

class RestaurantPolicy
{
    /**
     * Anyone can view restaurants.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Anyone can view a single restaurant.
     */
    public function view(?User $user, Restaurant $restaurant): bool
    {
        return true;
    }

    /**
     * Only restaurant owners can create restaurants.
     */
    public function create(User $user): bool
    {
        return $user->isRestaurantOwner() || $user->isAdmin();
    }

    /**
     * Only the owner of the restaurant or an admin can update it.
     */
    public function update(User $user, Restaurant $restaurant): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isRestaurantOwner() && $restaurant->user_id === $user->id;
    }

    /**
     * Only the owner or an admin can delete.
     */
    public function delete(User $user, Restaurant $restaurant): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isRestaurantOwner() && $restaurant->user_id === $user->id;
    }

    /**
     * Only the owner or admin can manage menu items.
     */
    public function manageMenu(User $user, Restaurant $restaurant): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isRestaurantOwner() && $restaurant->user_id === $user->id;
    }
}
