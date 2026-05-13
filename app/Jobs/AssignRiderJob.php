<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\RiderLocation;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class AssignRiderJob implements ShouldQueue
{
    use Queueable;

    /**
     * Number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * Number of seconds to wait before retrying.
     */
    public int $backoff = 30;

    /**
     * Create a new job instance.
     *
     * @param  Order  $order  The order that needs a rider
     */
    public function __construct(public readonly Order $order)
    {
        // Route this job to the dedicated 'orders' Redis queue
        $this->onQueue('orders');
    }

    /**
     * Execute the job.
     * Finds the nearest available rider and assigns them to the order.
     */
    public function handle(): void
    {
        $order = $this->order->fresh();

        if (! $order || $order->rider_id !== null) {
            // Order already has a rider or was cancelled — skip.
            Log::info("AssignRiderJob: Order [{$this->order->id}] already assigned or not found, skipping.");
            return;
        }

        // Find available riders near the restaurant, ordered by distance
        // Using a simplified approach: pick any active rider without a current delivery
        $availableRider = User::where('role', 'rider')
            ->where('is_active', true)
            ->whereDoesntHave('orders', function ($q) {
                $q->whereIn('status', ['confirmed', 'preparing', 'ready', 'picked_up']);
            })
            ->first();

        if (! $availableRider) {
            Log::warning("AssignRiderJob: No available riders for Order [{$order->id}]. Will retry.");
            // Release back to queue to retry after backoff
            $this->release($this->backoff);
            return;
        }

        // Assign the rider
        $order->update([
            'rider_id' => $availableRider->id,
            'status'   => 'assigned',
        ]);

        // Log to order history
        OrderHistory::create([
            'order_id'   => $order->id,
            'status'     => 'assigned',
            'note'       => "Rider [{$availableRider->name}] assigned automatically.",
            'created_by' => null,
        ]);

        // Notify the rider
        SendNotificationJob::dispatch(
            $availableRider,
            'New Delivery Assignment',
            "You have been assigned to deliver Order #{$order->id}. Please pick up from the restaurant."
        )->onQueue('notifications');

        // Notify the customer
        SendNotificationJob::dispatch(
            $order->user,
            'Rider Assigned',
            "A rider has been assigned to your order #{$order->id} and is on the way!"
        )->onQueue('notifications');

        Log::info("AssignRiderJob: Rider [{$availableRider->id}] assigned to Order [{$order->id}].");
    }
}
