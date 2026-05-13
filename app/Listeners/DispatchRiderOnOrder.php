<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Enums\OrderStatus;
use App\Services\DispatchService;
use Illuminate\Contracts\Queue\ShouldQueue;

class DispatchRiderOnOrder implements ShouldQueue
{
    public function __construct(
        private readonly DispatchService $dispatchService,
    ) {}

    /**
     * Auto-release rider on delivery completion.
     */
    public function handle(OrderStatusChanged $event): void
    {
        $order = $event->order;

        if ($order->status === OrderStatus::Delivered && $order->rider) {
            $this->dispatchService->releaseRider($order->rider);
        }
    }
}
