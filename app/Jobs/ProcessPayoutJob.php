<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\PayoutService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessPayoutJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(
        public readonly int $orderId,
    ) {
        $this->onQueue('payouts');
    }

    public function handle(PayoutService $payoutService): void
    {
        $order = Order::with(['restaurant', 'rider'])->find($this->orderId);

        if (! $order) {
            Log::warning("ProcessPayoutJob: Order #{$this->orderId} not found.");
            return;
        }

        try {
            $payoutService->processOrderPayout($order);
            Log::info("ProcessPayoutJob: Payouts created for Order #{$this->orderId}.");
        } catch (\InvalidArgumentException $e) {
            Log::warning("ProcessPayoutJob: {$e->getMessage()}");
        }
    }
}
