<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Services\RestaurantService;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function __construct(private readonly RestaurantService $restaurantService)
    {
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'menu_category_id' => ['required', 'integer', 'exists:menu_categories,id'],
            'restaurant_id' => ['required', 'integer', 'exists:restaurants,id'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'is_available' => ['nullable', 'boolean'],
            'preparation_time_minutes' => ['nullable', 'integer', 'min:1'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $menuCategory = MenuCategory::query()->find($validatedData['menu_category_id']);
        if (! $menuCategory || $menuCategory->restaurant_id !== (int) $validatedData['restaurant_id']) {
            return response()->json([
                'success' => false,
                'message' => 'Category does not belong to the selected restaurant.',
                'errors' => ['invalid_restaurant_category'],
            ], 422);
        }

        if (! $request->user()->isAdmin() && ! $request->user()->restaurantsOwned()->where('id', $validatedData['restaurant_id'])->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
            ], 403);
        }

        $menuItem = $this->restaurantService->createMenuItem($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Menu item created successfully.',
            'data' => $menuItem,
        ], 201);
    }

    public function index()
    {
        $menuItems = MenuItem::query()->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Menu items fetched successfully.',
            'data' => $menuItems,
        ]);
    }

    public function show(int $id)
    {
        $menuItem = MenuItem::query()->find($id);
        if (! $menuItem) {
            return response()->json([
                'success' => false,
                'message' => 'Menu item not found.',
                'errors' => ['menu_item_not_found'],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Menu item fetched successfully.',
            'data' => $menuItem,
        ]);
    }

    public function update(Request $request, int $id)
    {
        $menuItem = MenuItem::query()->find($id);
        if (! $menuItem) {
            return response()->json([
                'success' => false,
                'message' => 'Menu item not found.',
                'errors' => ['menu_item_not_found'],
            ], 404);
        }

        $validatedData = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'menu_category_id' => ['sometimes', 'required', 'integer', 'exists:menu_categories,id'],
            'restaurant_id' => ['sometimes', 'required', 'integer', 'exists:restaurants,id'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'is_available' => ['nullable', 'boolean'],
            'preparation_time_minutes' => ['nullable', 'integer', 'min:1'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $selectedRestaurantId = (int) ($validatedData['restaurant_id'] ?? $menuItem->restaurant_id);
        $selectedCategoryId = (int) ($validatedData['menu_category_id'] ?? $menuItem->menu_category_id);

        $menuCategory = MenuCategory::query()->find($selectedCategoryId);
        if (! $menuCategory || $menuCategory->restaurant_id !== $selectedRestaurantId) {
            return response()->json([
                'success' => false,
                'message' => 'Category does not belong to the selected restaurant.',
                'errors' => ['invalid_restaurant_category'],
            ], 422);
        }

        if (! $request->user()->isAdmin() && ! $request->user()->restaurantsOwned()->where('id', $selectedRestaurantId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
            ], 403);
        }

        $menuItem = $this->restaurantService->updateMenuItem($menuItem, $validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Menu item updated successfully.',
            'data' => $menuItem,
        ]);
    }

    public function destroy(int $id)
    {
        $menuItem = MenuItem::query()->find($id);
        if (! $menuItem) {
            return response()->json([
                'success' => false,
                'message' => 'Menu item not found.',
                'errors' => ['menu_item_not_found'],
            ], 404);
        }

        if (! request()->user()->isAdmin() && ! request()->user()->restaurantsOwned()->where('id', $menuItem->restaurant_id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $menuItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Menu item deleted successfully.',
            'data' => [],
        ]);
    }

    public function toggleAvailability(int $id)
    {
        $menuItem = MenuItem::query()->find($id);
        if (! $menuItem) {
            return response()->json([
                'success' => false,
                'message' => 'Menu item not found.',
                'errors' => ['menu_item_not_found'],
            ], 404);
        }

        if (! request()->user()->isAdmin() && ! request()->user()->restaurantsOwned()->where('id', $menuItem->restaurant_id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $menuItem = $this->restaurantService->toggleItemAvailability($menuItem);

        return response()->json([
            'success' => true,
            'message' => 'Menu item availability updated successfully.',
            'data' => $menuItem,
        ]);
    }
}
