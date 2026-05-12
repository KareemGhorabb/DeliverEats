<?php

namespace App\Events;

use App\Models\Order;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RiderAssigned implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly Order $order,
        public readonly User $rider,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("orders.{$this->order->id}"),
            new PrivateChannel("customer.{$this->order->user_id}"),
            new PrivateChannel("rider.{$this->rider->id}"),
            new PrivateChannel("restaurant.{$this->order->restaurant_id}"),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'order_id'   => $this->order->id,
            'rider_id'   => $this->rider->id,
            'rider_name' => $this->rider->name,
            'rider_phone' => $this->rider->phone,
        ];
    }

    public function broadcastAs(): string
    {
        return 'rider.assigned';
    }
}
