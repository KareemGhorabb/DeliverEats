<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Finite State Machine for order status transitions.
 *
 * Enforces valid transitions, actor role guards, and
 * logs every transition for event sourcing-inspired history.
 */
class OrderStateMachine
{
    /**
     * Transition order to a new status with guards.
     *
     * @throws \InvalidArgumentException if transition is invalid
     * @throws \UnauthorizedHttpException if actor role is not allowed
     */
    public function transition(Order $order, OrderStatus $targetStatus, User $actor, ?string $note = null): Order
    {
        // Guard: check valid transition
        if (! $order->status->canTransitionTo($targetStatus)) {
            throw new \InvalidArgumentException(
                "Invalid transition: {$order->status->value} → {$targetStatus->value}. "
                . 'Allowed: ' . implode(', ', array_map(
                    fn (OrderStatus $s) => $s->value,
                    $order->status->allowedTransitions()
                ))
            );
        }

        // Guard: check actor role (admin can override any)
        $requiredRole = $targetStatus->requiredActorRole();
        if ($requiredRole !== null && $actor->role !== $requiredRole && ! $actor->isAdmin()) {
            throw new \UnauthorizedHttpException(
                '',
                "Only {$requiredRole->label()} can transition orders to {$targetStatus->label()}."
            );
        }

        // Guard: cancellation-specific rules
        if ($targetStatus === OrderStatus::Cancelled) {
            $this->validateCancellation($order, $actor);
        }

        return DB::transaction(function () use ($order, $targetStatus, $actor, $note) {
            $previousStatus = $order->status;

            // Update order status
            $order->status = $targetStatus;

            // Set the corresponding timestamp
            $timestampColumn = $targetStatus->timestampColumn();
            if ($timestampColumn) {
                $order->{$timestampColumn} = now();
            }

            $order->save();

            // Log the transition (event sourcing)
            OrderHistory::create([
                'order_id'    => $order->id,
                'from_status' => $previousStatus->value,
                'to_status'   => $targetStatus->value,
                'changed_by'  => $actor->id,
                'note'        => $note,
            ]);

            return $order->refresh();
        });
    }

    /**
     * Additional validation for cancellation.
     */
    private function validateCancellation(Order $order, User $actor): void
    {
        // Customers can only cancel before food is being prepared
        if ($actor->isCustomer()) {
            $nonCancellableByCustomer = [
                OrderStatus::Preparing,
                OrderStatus::ReadyForPickup,
                OrderStatus::PickedUp,
            ];

            if (in_array($order->status, $nonCancellableByCustomer, true)) {
                throw new \InvalidArgumentException(
                    'Customers cannot cancel orders that are already being prepared.'
                );
            }
        }

        // Riders cannot cancel orders
        if ($actor->isRider()) {
            throw new \InvalidArgumentException('Riders cannot cancel orders.');
        }
    }
}
