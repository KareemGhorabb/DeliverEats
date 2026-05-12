<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PayoutStatus;
use App\Models\Order;
use App\Models\Payout;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PayoutService
{
    /**
     * Platform commission rates.
     */
    private const RESTAURANT_COMMISSION_RATE = 0.15; // 15% platform fee on subtotal
    private const RIDER_COMMISSION_RATE = 0.10;      // 10% platform fee on delivery fee

    /**
     * Calculate and create payouts for a delivered order.
     */
    public function processOrderPayout(Order $order): array
    {
        if ($order->status !== OrderStatus::Delivered) {
            throw new \InvalidArgumentException('Can only process payouts for delivered orders.');
        }

        return DB::transaction(function () use ($order) {
            $payouts = [];

            // Restaurant payout
            $restaurantEarnings = (float) $order->subtotal;
            $restaurantCommission = round($restaurantEarnings * self::RESTAURANT_COMMISSION_RATE, 2);
            $restaurantNet = round($restaurantEarnings - $restaurantCommission, 2);

            $payouts['restaurant'] = Payout::create([
                'restaurant_id'       => $order->restaurant_id,
                'amount'              => $restaurantEarnings,
                'platform_commission' => $restaurantCommission,
                'net_amount'          => $restaurantNet,
                'status'              => PayoutStatus::Pending,
            ]);

            // Rider payout (if rider assigned)
            if ($order->rider_id) {
                $riderEarnings = (float) $order->delivery_fee;
                $riderCommission = round($riderEarnings * self::RIDER_COMMISSION_RATE, 2);
                $riderNet = round($riderEarnings - $riderCommission, 2);

                $payouts['rider'] = Payout::create([
                    'rider_id'            => $order->rider_id,
                    'amount'              => $riderEarnings,
                    'platform_commission' => $riderCommission,
                    'net_amount'          => $riderNet,
                    'status'              => PayoutStatus::Pending,
                ]);
            }

            return $payouts;
        });
    }

    /**
     * Get earnings summary for a restaurant.
     */
    public function getRestaurantEarnings(int $restaurantId): array
    {
        $payouts = Payout::where('restaurant_id', $restaurantId);

        return [
            'total_gross'      => round($payouts->sum('amount'), 2),
            'total_commission' => round($payouts->sum('platform_commission'), 2),
            'total_net'        => round($payouts->sum('net_amount'), 2),
            'pending_count'    => (clone $payouts)->pending()->count(),
            'pending_amount'   => round((clone $payouts)->pending()->sum('net_amount'), 2),
            'completed_count'  => (clone $payouts)->completed()->count(),
            'completed_amount' => round((clone $payouts)->completed()->sum('net_amount'), 2),
        ];
    }

    /**
     * Get earnings summary for a rider.
     */
    public function getRiderEarnings(int $riderId): array
    {
        $payouts = Payout::where('rider_id', $riderId);

        return [
            'total_gross'        => round($payouts->sum('amount'), 2),
            'total_commission'   => round($payouts->sum('platform_commission'), 2),
            'total_net'          => round($payouts->sum('net_amount'), 2),
            'total_deliveries'   => $payouts->count(),
            'pending_amount'     => round((clone $payouts)->pending()->sum('net_amount'), 2),
        ];
    }

    /**
     * Get platform-wide revenue summary (admin).
     */
    public function getPlatformRevenue(): array
    {
        return [
            'total_commissions'    => round(Payout::sum('platform_commission'), 2),
            'restaurant_payouts'   => round(Payout::whereNotNull('restaurant_id')->sum('net_amount'), 2),
            'rider_payouts'        => round(Payout::whereNotNull('rider_id')->sum('net_amount'), 2),
            'pending_payouts'      => round(Payout::where('status', PayoutStatus::Pending)->sum('net_amount'), 2),
            'total_orders_paid'    => Payout::completed()->distinct('restaurant_id')->count(),
        ];
    }
}
