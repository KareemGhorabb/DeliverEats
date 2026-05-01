<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@delivereats.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '01000000001',
        ]);

        $restaurantOwner = User::create([
            'name' => 'Restaurant Owner',
            'email' => 'owner@burgerking.com',
            'password' => bcrypt('password'),
            'role' => 'restaurant_owner',
            'phone' => '01000000002',
        ]);

        $rider = User::create([
            'name' => 'Test Rider',
            'email' => 'rider@delivereats.com',
            'password' => bcrypt('password'),
            'role' => 'rider',
            'phone' => '01000000003',
        ]);

        $customer = User::create([
            'name' => 'Test Customer',
            'email' => 'customer@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'phone' => '01000000004',
        ]);

        // 2. Seed Restaurant
        $restaurantId = \Illuminate\Support\Facades\DB::table('restaurants')->insertGetId([
            'user_id' => $restaurantOwner->id,
            'name' => 'Burger King',
            'slug' => 'burger-king',
            'description' => 'Home of the Whopper',
            'phone' => '19999',
            'address' => '123 Main St, Cairo',
            'latitude' => 30.0444,
            'longitude' => 31.2357,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Seed Menu Category
        $categoryId = \Illuminate\Support\Facades\DB::table('menu_categories')->insertGetId([
            'restaurant_id' => $restaurantId,
            'name' => 'Burgers',
            'sort_order' => 1,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Seed Menu Items
        $whopperId = \Illuminate\Support\Facades\DB::table('menu_items')->insertGetId([
            'menu_category_id' => $categoryId,
            'name' => 'Whopper',
            'description' => 'Flame-grilled beef patty, topped with tomatoes, fresh cut lettuce, mayo, pickles, a swirl of ketchup, and sliced white onions on a soft sesame seed bun.',
            'price' => 150.00,
            'is_available' => true,
            'preparation_time_minutes' => 10,
            'sort_order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $chickenRoyaleId = \Illuminate\Support\Facades\DB::table('menu_items')->insertGetId([
            'menu_category_id' => $categoryId,
            'name' => 'Chicken Royale',
            'description' => 'Crispy chicken fillet topped with iceberg lettuce and creamy mayo on a sesame seed bun.',
            'price' => 120.00,
            'is_available' => true,
            'preparation_time_minutes' => 12,
            'sort_order' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 5. Seed Item Variants
        \Illuminate\Support\Facades\DB::table('item_variants')->insert([
            [
                'menu_item_id' => $whopperId,
                'name' => 'Large',
                'price_modifier' => 40.00,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'menu_item_id' => $whopperId,
                'name' => 'Extra Cheese',
                'price_modifier' => 20.00,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
