<?php

namespace App\Listeners;

use App\Contracts\NotificationServiceInterface;
use App\Events\OrderCreated;
use App\Events\OrderStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyOrderStatusChange implements ShouldQueue
{
    public function __construct(
        private readonly NotificationServiceInterface $notifications,
    ) {}

    /**
     * Handle OrderStatusChanged or OrderCreated events.
     */
    public function handle(OrderStatusChanged|OrderCreated $event): void
    {
        $order = $event->order;
        $statusLabel = $order->status->label();

        // Notify the customer
        if ($order->user) {
            $this->notifications->sendPush(
                $order->user,
                "Order #{$order->id} Update",
                "Your order is now: {$statusLabel}",
                ['order_id' => $order->id, 'status' => $order->status->value]
            );
        }

        // Notify the restaurant owner
        if ($order->restaurant?->user) {
            $this->notifications->sendPush(
                $order->restaurant->user,
                "Order #{$order->id} Update",
                "Order status changed to: {$statusLabel}",
                ['order_id' => $order->id, 'status' => $order->status->value]
            );
        }

        // Notify the rider (if assigned)
        if ($order->rider) {
            $this->notifications->sendPush(
                $order->rider,
                "Order #{$order->id} Update",
                "Order status is now: {$statusLabel}",
                ['order_id' => $order->id, 'status' => $order->status->value]
            );
        }
    }
}
