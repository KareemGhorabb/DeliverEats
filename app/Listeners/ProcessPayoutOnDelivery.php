<?php

namespace App\Listeners;

use App\Enums\OrderStatus;
use App\Events\OrderStatusChanged;
use App\Services\PayoutService;

class ProcessPayoutOnDelivery
{
    public function __construct(
        private readonly PayoutService $payoutService,
    ) {}

    public function handle(OrderStatusChanged $event): void
    {
        $order = $event->order;

        if ($order->status === OrderStatus::Delivered) {
            $this->payoutService->processOrderPayout($order);
        }
    }
}
