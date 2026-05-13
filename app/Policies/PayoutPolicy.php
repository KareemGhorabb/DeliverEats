<?php

namespace App\Policies;

use App\Models\Payout;
use App\Models\User;

class PayoutPolicy
{
    /**
     * Admins can view all payouts. Restaurant owners and riders can view their own.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isRestaurantOwner() || $user->isRider();
    }

    /**
     * A user can view a payout if they are admin, or the payout belongs to their restaurant/rider.
     */
    public function view(User $user, Payout $payout): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isRestaurantOwner() && $payout->restaurant_id) {
            return $user->restaurantsOwned()->where('id', $payout->restaurant_id)->exists();
        }

        if ($user->isRider() && $payout->rider_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Only admins can mark payouts as paid.
     */
    public function markPaid(User $user): bool
    {
        return $user->isAdmin();
    }
}
