<?php

namespace App\Events;

use App\Models\RiderLocation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RiderLocationUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly RiderLocation $location,
        public readonly ?int $activeOrderId = null,
    ) {}

    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel("rider.{$this->location->user_id}"),
            new PrivateChannel('admin.control-tower'),
        ];

        if ($this->activeOrderId) {
            $channels[] = new PrivateChannel("orders.{$this->activeOrderId}");
        }

        return $channels;
    }

    public function broadcastWith(): array
    {
        return [
            'rider_id'     => $this->location->user_id,
            'latitude'     => $this->location->latitude,
            'longitude'    => $this->location->longitude,
            'availability' => $this->location->availability->value,
            'updated_at'   => $this->location->last_ping_at?->toISOString(),
        ];
    }

    public function broadcastAs(): string
    {
        return 'rider.location.updated';
    }
}
