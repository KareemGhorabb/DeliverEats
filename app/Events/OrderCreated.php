<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly Order $order,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("customer.{$this->order->user_id}"),
            new PrivateChannel("restaurant.{$this->order->restaurant_id}"),
            new PrivateChannel('admin.control-tower'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'order_id'      => $this->order->id,
            'restaurant_id' => $this->order->restaurant_id,
            'total'         => $this->order->total,
            'status'        => $this->order->status->value,
            'created_at'    => $this->order->created_at->toISOString(),
        ];
    }

    public function broadcastAs(): string
    {
        return 'order.created';
    }
}
