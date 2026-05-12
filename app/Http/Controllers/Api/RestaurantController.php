<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Services\RestaurantService;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function __construct(private readonly RestaurantService $restaurantService)
    {
    }

    public function index()
    {
        $restaurants = $this->restaurantService->getAllRestaurants();

        return response()->json([
            'success' => true,
            'message' => 'Restaurants fetched successfully.',
            'data' => $restaurants,
        ]);
    }

    public function show(Request $request, int $id)
    {
        $user = $request->user();
        $isOwner = $user && ($user->isAdmin() || $user->restaurantsOwned()->where('id', $id)->exists());
        
        $restaurant = $this->restaurantService->getRestaurantMenu($id, $isOwner);

        return response()->json([
            'success' => true,
            'message' => 'Restaurant menu fetched successfully.',
            'data' => $restaurant,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:restaurants,slug'],
            'address' => ['required', 'string'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'delivery_fee' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $restaurant = $this->restaurantService->createRestaurant($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Restaurant created successfully.',
            'data' => $restaurant,
        ], 201);
    }

    public function update(Request $request, int $id)
    {
        $restaurant = Restaurant::query()->find($id);
        if (! $restaurant) {
            return response()->json([
                'success' => false,
                'message' => 'Restaurant not found.',
                'errors' => ['restaurant_not_found'],
            ], 404);
        }

        if (! $request->user()->isAdmin() && $restaurant->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
            ], 403);
        }

        $validatedData = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'required', 'string', 'max:255', 'unique:restaurants,slug,' . $restaurant->id],
            'address' => ['sometimes', 'required', 'string'],
            'latitude' => ['sometimes', 'required', 'numeric'],
            'longitude' => ['sometimes', 'required', 'numeric'],
            'delivery_fee' => ['sometimes', 'required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $restaurant = $this->restaurantService->updateRestaurant($restaurant, $validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Restaurant updated successfully.',
            'data' => $restaurant,
        ]);
    }

    public function destroy(int $id)
    {
        $restaurant = Restaurant::query()->find($id);
        if (! $restaurant) {
            return response()->json([
                'success' => false,
                'message' => 'Restaurant not found.',
                'errors' => ['restaurant_not_found'],
            ], 404);
        }

        if (! request()->user()->isAdmin() && $restaurant->user_id !== request()->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
            ], 403);
        }

        $this->restaurantService->deleteRestaurant($restaurant);

        return response()->json([
            'success' => true,
            'message' => 'Restaurant deleted successfully.',
            'data' => [],
        ]);
    }
}
