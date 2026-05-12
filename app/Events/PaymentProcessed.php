<?php

namespace App\Events;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentProcessed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly Order $order,
        public readonly Payment $payment,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("orders.{$this->order->id}"),
            new PrivateChannel("customer.{$this->order->user_id}"),
            new PrivateChannel("restaurant.{$this->order->restaurant_id}"),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'order_id'       => $this->order->id,
            'payment_status' => $this->payment->status->value,
            'amount'         => $this->payment->amount,
            'method'         => $this->payment->method->value,
            'paid_at'        => $this->payment->paid_at?->toISOString(),
        ];
    }

    public function broadcastAs(): string
    {
        return 'payment.processed';
    }
}
