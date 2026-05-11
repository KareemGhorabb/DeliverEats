<?php

namespace App\Services;

use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Collection;

class RestaurantService
{
    public function getAllRestaurants(): Collection
    {
        return Restaurant::query()
            ->with(['menuCategories', 'user'])
            ->latest()
            ->get();
    }

    public function getRestaurantMenu(int $restaurantId, bool $fullMenu = false): Restaurant
    {
        $query = Restaurant::query();

        if ($fullMenu) {
            $query->with(['menuCategories.menuItems.itemVariants']);
        } else {
            $query->with(['menuCategories' => function ($query) {
                $query->where('is_active', true)->with(['menuItems' => function ($q) {
                    $q->where('is_available', true)->with('itemVariants');
                }]);
            }]);
        }

        return $query->findOrFail($restaurantId);
    }

    public function createRestaurant(array $data): Restaurant
    {
        $restaurant = Restaurant::query()->create($data);
        
        // Create default categories for the new restaurant
        $defaultCategories = ['Mains', 'Sides', 'Drinks'];
        foreach ($defaultCategories as $index => $categoryName) {
            $restaurant->menuCategories()->create([
                'name' => $categoryName,
                'sort_order' => $index,
                'is_active' => true,
            ]);
        }
        
        return $restaurant->load('menuCategories');
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
        return MenuItem::create($data);
    }

    public function updateMenuItem(MenuItem $menuItem, array $data): MenuItem
    {
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
