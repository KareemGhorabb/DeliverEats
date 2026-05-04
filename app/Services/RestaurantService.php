<?php

namespace App\Services;

use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;

class RestaurantService
{
    public function getAllRestaurants(): Collection
    {
        return Restaurant::query()
            ->with('menuCategories')
            ->latest()
            ->get();
    }

    public function getRestaurantMenu(int $restaurantId): Restaurant
    {
        return Restaurant::query()
            ->with(['menuCategories.menuItems.itemVariants'])
            ->findOrFail($restaurantId);
    }

    public function createRestaurant(array $data): Restaurant
    {
        return Restaurant::query()->create($data);
    }

    public function updateRestaurant(Restaurant $restaurant, array $data): Restaurant
    {
        $restaurant->update($data);

        return $restaurant->refresh();
    }

    public function deleteRestaurant(Restaurant $restaurant): void
    {
        $restaurant->delete();
    }

    public function createMenuItem(array $data): MenuItem
    {
        if (! Schema::hasColumn('menu_items', 'restaurant_id')) {
            unset($data['restaurant_id']);
        }

        $menuItem = new MenuItem();
        $menuItem->fill($data);
        $menuItem->save();

        return $menuItem->refresh();
    }

    public function updateMenuItem(MenuItem $menuItem, array $data): MenuItem
    {
        if (! Schema::hasColumn('menu_items', 'restaurant_id')) {
            unset($data['restaurant_id']);
        }

        $menuItem->update($data);

        return $menuItem->refresh();
    }

    public function toggleItemAvailability(MenuItem $menuItem): MenuItem
    {
        $menuItem->is_available = ! $menuItem->is_available;
        $menuItem->save();

        return $menuItem->refresh();
    }
}
