<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Models\User;
use App\Models\Restaurant;

class ControlTowerMap extends Component
{
    public function getMapData()
    {
        $riders = User::where('role', 'rider')
            ->where('is_online', true)
            ->select('id', 'name', 'latitude', 'longitude')
            ->get();

        $restaurants = Restaurant::select('id', 'name', 'latitude', 'longitude')->get();

        $orders = Order::whereNotIn('status', ['delivered', 'cancelled', 'payment_pending'])
            ->with(['restaurant:id,latitude,longitude', 'rider:id,latitude,longitude'])
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'status' => $order->status,
                    'lat' => $order->restaurant ? $order->restaurant->latitude : null,
                    'lng' => $order->restaurant ? $order->restaurant->longitude : null,
                ];
            });

        return [
            'riders' => $riders,
            'restaurants' => $restaurants,
            'orders' => $orders,
        ];
    }

    public function render()
    {
        return view('livewire.admin.control-tower-map', [
            'initialData' => $this->getMapData()
        ]);
    }
}
