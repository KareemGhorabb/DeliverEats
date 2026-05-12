<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PaymentPending = 'payment_pending';
    case Pending        = 'pending';      // Was 'placed'
    case Accepted       = 'accepted';     // Was 'confirmed'
    case Preparing      = 'preparing';
    case ReadyForPickup = 'ready_for_pickup';
    case RiderAssigned  = 'rider_assigned';
    case PickedUp       = 'picked_up';
    case Delivered      = 'delivered';
    case Cancelled      = 'cancelled';

    /**
     * Valid transitions from this status.
     *
     * @return array<self>
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::PaymentPending => [self::Pending, self::Cancelled],
            self::Pending        => [self::Accepted, self::Cancelled],
            self::Accepted       => [self::Preparing, self::Cancelled],
            self::Preparing      => [self::ReadyForPickup, self::Cancelled],
            self::ReadyForPickup => [self::RiderAssigned],
            self::RiderAssigned  => [self::PickedUp, self::Cancelled],
            self::PickedUp       => [self::Delivered],
            self::Delivered      => [],
            self::Cancelled      => [],
        };
    }

    /**
     * Check if transitioning to $target is valid.
     */
    public function canTransitionTo(self $target): bool
    {
        return in_array($target, $this->allowedTransitions(), true);
    }

    /**
     * Human-readable label.
     */
    public function label(): string
    {
        return match ($this) {
            self::PaymentPending => 'Awaiting Payment',
            self::Pending        => 'Pending',
            self::Accepted       => 'Accepted',
            self::Preparing      => 'Preparing',
            self::ReadyForPickup => 'Ready for Pickup',
            self::RiderAssigned  => 'Rider Assigned',
            self::PickedUp       => 'Picked Up',
            self::Delivered      => 'Delivered',
            self::Cancelled      => 'Cancelled',
        };
    }

    /**
     * Which role is allowed to trigger this transition?
     */
    public function requiredActorRole(): ?UserRole
    {
        return match ($this) {
            self::Accepted       => UserRole::RestaurantOwner,
            self::Preparing      => UserRole::RestaurantOwner,
            self::ReadyForPickup => UserRole::RestaurantOwner,
            self::RiderAssigned  => UserRole::Rider,
            self::PickedUp       => UserRole::Rider,
            self::Delivered      => UserRole::Rider,
            self::Cancelled      => null, // any actor can cancel (with conditions)
            default              => null,
        };
    }

    /**
     * Timestamp column that should be filled on this transition.
     */
    public function timestampColumn(): ?string
    {
        return match ($this) {
            self::Accepted       => 'confirmed_at',
            self::Preparing      => 'preparing_at',
            self::ReadyForPickup => 'ready_at',
            self::PickedUp       => 'picked_up_at',
            self::Delivered      => 'delivered_at',
            self::Cancelled      => 'cancelled_at',
            default              => null,
        };
    }

    /**
     * Whether the order is still active (not terminal).
     */
    public function isActive(): bool
    {
        return !in_array($this, [self::Delivered, self::Cancelled], true);
    }
}
