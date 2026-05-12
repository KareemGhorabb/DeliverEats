<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly Order $order,
    ) {}

    /**
     * Broadcast to customer, restaurant, rider, and admin channels.
     */
    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel("orders.{$this->order->id}"),
            new PrivateChannel("customer.{$this->order->user_id}"),
            new PrivateChannel("restaurant.{$this->order->restaurant_id}"),
            new PrivateChannel('admin.control-tower'),
        ];

        if ($this->order->rider_id) {
            $channels[] = new PrivateChannel("rider.{$this->order->rider_id}");
        }

        return $channels;
    }

    public function broadcastWith(): array
    {
        return [
            'order_id'   => $this->order->id,
            'status'     => $this->order->status->value,
            'label'      => $this->order->status->label(),
            'updated_at' => $this->order->updated_at->toISOString(),
        ];
    }

    public function broadcastAs(): string
    {
        return 'order.status.changed';
    }
}
