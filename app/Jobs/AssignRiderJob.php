<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\DispatchService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class AssignRiderJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $backoff = 30; // Retry after 30 seconds

    public function __construct(
        public readonly int $orderId,
    ) {
        $this->onQueue('dispatch');
    }

    public function handle(DispatchService $dispatchService): void
    {
        $order = Order::with('restaurant')->find($this->orderId);

        if (! $order || ! $order->isActive()) {
            Log::info("AssignRiderJob: Order #{$this->orderId} no longer active, skipping.");
            return;
        }

        if ($order->rider_id) {
            Log::info("AssignRiderJob: Order #{$this->orderId} already has rider assigned.");
            return;
        }

        $rider = $dispatchService->assignNearestRider($order);

        if (! $rider) {
            Log::warning("AssignRiderJob: No available riders for Order #{$this->orderId}. Will retry.");

            // Re-dispatch with delay for retry
            if ($this->attempts() < $this->tries) {
                self::dispatch($this->orderId)->delay(now()->addSeconds(60));
            }
        }
    }
}
