<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Services\RestaurantService;

class RestaurantPageController extends Controller
{
    public function __construct(private readonly RestaurantService $restaurantService)
    {
    }

    public function browse()
    {
        $restaurants = $this->restaurantService->getAllRestaurants();

        return view('customer.home', [
            'restaurants' => $restaurants,
        ]);
    }

    public function landing()
    {
        $restaurants = $this->restaurantService->getAllRestaurants();

        return view('landing', [
            'restaurants' => $restaurants,
        ]);
    }

    public function show(string $slug)
    {
        $restaurant = Restaurant::query()->where('slug', $slug)->firstOrFail();
        $restaurantWithMenu = $this->restaurantService->getRestaurantMenu($restaurant->id);

        return view('customer.restaurant', [
            'restaurant' => $restaurantWithMenu,
            'menuCategories' => $restaurantWithMenu->menuCategories,
        ]);
    }

    public function menu(\Illuminate\Http\Request $request)
    {
        $user = $request->user();
        $restaurant = $user?->restaurantsOwned()->first();
        
        $menuCategories = collect();
        if ($restaurant) {
            $restaurantWithMenu = $this->restaurantService->getRestaurantMenu($restaurant->id);
            $menuCategories = $restaurantWithMenu->menuCategories;
        }

        return view('restaurant.menu', [
            'menuCategories' => $menuCategories,
            'restaurant' => $restaurant,
        ]);
    }
}
