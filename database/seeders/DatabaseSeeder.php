<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\RiderLocation;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Reset DB
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('order_histories')->truncate();
        DB::table('reviews')->truncate();
        DB::table('payouts')->truncate();
        DB::table('payments')->truncate();
        DB::table('order_items')->truncate();
        DB::table('orders')->truncate();
        DB::table('rider_locations')->truncate();
        DB::table('item_variants')->truncate();
        DB::table('menu_items')->truncate();
        DB::table('menu_categories')->truncate();
        DB::table('restaurants')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ═══════════════════════════════
        // USERS — password is auto-hashed by User model 'hashed' cast
        // ═══════════════════════════════
        $admin = User::create([
            'name' => 'System Admin',
            'email' => 'admin@delivereats.com',
            'password' => 'password',
            'role' => 'admin',
            'phone' => '01000000001',
        ]);

        $owner = User::create([
            'name' => 'Restaurant Owner',
            'email' => 'owner@delivereats.com',
            'password' => 'password',
            'role' => 'restaurant_owner',
            'phone' => '01000000002',
        ]);

        $customer = User::create([
            'name' => 'Ahmed Customer',
            'email' => 'customer@delivereats.com',
            'password' => 'password',
            'role' => 'customer',
            'phone' => '01000000003',
            'address' => 'Tahrir Square, Cairo',
            'latitude' => 30.0444,
            'longitude' => 31.2357,
        ]);

        $customer2 = User::create([
            'name' => 'Sara Mohamed',
            'email' => 'sara@delivereats.com',
            'password' => 'password',
            'role' => 'customer',
            'phone' => '01000000006',
            'address' => 'Zamalek, Cairo',
            'latitude' => 30.0600,
            'longitude' => 31.2230,
        ]);

        $rider1 = User::create([
            'name' => 'Mohamed Rider',
            'email' => 'rider@delivereats.com',
            'password' => 'password',
            'role' => 'rider',
            'phone' => '01000000004',
            'latitude' => 30.0500,
            'longitude' => 31.2400,
        ]);

        $rider2 = User::create([
            'name' => 'Ali Driver',
            'email' => 'rider2@delivereats.com',
            'password' => 'password',
            'role' => 'rider',
            'phone' => '01000000005',
            'latitude' => 30.0350,
            'longitude' => 31.2300,
        ]);

        // ═══════════════════════════════
        // RIDER LOCATIONS
        // ═══════════════════════════════
        RiderLocation::create([
            'user_id' => $rider1->id,
            'latitude' => 30.0500,
            'longitude' => 31.2400,
            'availability' => 'online',
            'last_ping_at' => now(),
        ]);

        RiderLocation::create([
            'user_id' => $rider2->id,
            'latitude' => 30.0350,
            'longitude' => 31.2300,
            'availability' => 'online',
            'last_ping_at' => now(),
        ]);

        // ═══════════════════════════════
        // RESTAURANTS
        // ═══════════════════════════════
        $restaurants = [];

        $restaurants['basha'] = DB::table('restaurants')->insertGetId([
            'user_id' => $owner->id, 'name' => 'Al-Basha Grill', 'slug' => 'al-basha-grill',
            'description' => 'Authentic Middle Eastern grills and kebabs since 1985. Fresh ingredients, charcoal-grilled to perfection.',
            'category' => 'Middle Eastern', 'phone' => '+20223456789', 'address' => 'Downtown Cairo',
            'latitude' => 30.0444, 'longitude' => 31.2357, 'is_active' => true,
            'avg_rating' => 4.5, 'total_reviews' => 128,
            'created_at' => $now, 'updated_at' => $now,
        ]);

        $restaurants['sakura'] = DB::table('restaurants')->insertGetId([
            'user_id' => $owner->id, 'name' => 'Sakura Sushi House', 'slug' => 'sakura-sushi-house',
            'description' => 'Premium Japanese sushi and ramen. Imported fish, traditional recipes.',
            'category' => 'Japanese', 'phone' => '+20234567890', 'address' => 'Zamalek',
            'latitude' => 30.0600, 'longitude' => 31.2230, 'is_active' => true,
            'avg_rating' => 4.7, 'total_reviews' => 89,
            'created_at' => $now, 'updated_at' => $now,
        ]);

        $restaurants['napoli'] = DB::table('restaurants')->insertGetId([
            'user_id' => $owner->id, 'name' => 'Bella Napoli', 'slug' => 'bella-napoli',
            'description' => 'Wood-fired Italian pizzas and handmade pasta.',
            'category' => 'Italian', 'phone' => '+20245678901', 'address' => 'Maadi',
            'latitude' => 29.9597, 'longitude' => 31.2503, 'is_active' => true,
            'avg_rating' => 4.3, 'total_reviews' => 156,
            'created_at' => $now, 'updated_at' => $now,
        ]);

        $restaurants['forno'] = DB::table('restaurants')->insertGetId([
            'user_id' => $owner->id, 'name' => 'Forno Romano', 'slug' => 'forno-romano',
            'description' => 'Roman-style pizza al taglio.',
            'category' => 'Italian', 'phone' => '+20225551100', 'address' => 'Dokki',
            'latitude' => 30.0370, 'longitude' => 31.2118, 'is_active' => true,
            'avg_rating' => 4.1, 'total_reviews' => 72,
            'created_at' => $now, 'updated_at' => $now,
        ]);

        $restaurants['shawarma'] = DB::table('restaurants')->insertGetId([
            'user_id' => $owner->id, 'name' => 'Sultan Shawarma', 'slug' => 'sultan-shawarma',
            'description' => 'The best shawarma wraps in Cairo.',
            'category' => 'Middle Eastern', 'phone' => '+20225552200', 'address' => 'Nasr City',
            'latitude' => 30.0638, 'longitude' => 31.3411, 'is_active' => true,
            'avg_rating' => 4.6, 'total_reviews' => 234,
            'created_at' => $now, 'updated_at' => $now,
        ]);

        $restaurants['green'] = DB::table('restaurants')->insertGetId([
            'user_id' => $owner->id, 'name' => 'GreenBowl', 'slug' => 'greenbowl',
            'description' => 'Healthy bowls, salads, and smoothies.',
            'category' => 'Healthy', 'phone' => '+20225553300', 'address' => 'Maadi',
            'latitude' => 29.9710, 'longitude' => 31.2560, 'is_active' => true,
            'avg_rating' => 4.4, 'total_reviews' => 67,
            'created_at' => $now, 'updated_at' => $now,
        ]);

        $restaurants['koshary'] = DB::table('restaurants')->insertGetId([
            'user_id' => $owner->id, 'name' => 'Koshary El Prince', 'slug' => 'koshary-el-prince',
            'description' => 'Egypt\'s favourite comfort food.',
            'category' => 'Egyptian', 'phone' => '+20225554400', 'address' => 'Downtown',
            'latitude' => 30.0626, 'longitude' => 31.2497, 'is_active' => true,
            'avg_rating' => 4.8, 'total_reviews' => 412,
            'created_at' => $now, 'updated_at' => $now,
        ]);

        $restaurants['crepe'] = DB::table('restaurants')->insertGetId([
            'user_id' => $owner->id, 'name' => 'Le Petit Crepe', 'slug' => 'le-petit-crepe',
            'description' => 'Sweet and savory French crepes.',
            'category' => 'French', 'phone' => '+20225555500', 'address' => 'Heliopolis',
            'latitude' => 30.0877, 'longitude' => 31.3216, 'is_active' => true,
            'avg_rating' => 4.2, 'total_reviews' => 95,
            'created_at' => $now, 'updated_at' => $now,
        ]);

        // ═══════════════════════════════
        // HELPER FUNCTIONS — now includes restaurant_id
        // ═══════════════════════════════
        $createCategory = function ($restaurantId, $name) use ($now) {
            return DB::table('menu_categories')->insertGetId([
                'restaurant_id' => $restaurantId, 'name' => $name,
                'is_active' => true, 'created_at' => $now, 'updated_at' => $now,
            ]);
        };

        $createItem = function ($categoryId, $restaurantId, $name, $price) use ($now) {
            return DB::table('menu_items')->insertGetId([
                'menu_category_id' => $categoryId, 'restaurant_id' => $restaurantId,
                'name' => $name, 'price' => $price, 'is_available' => true,
                'created_at' => $now, 'updated_at' => $now,
            ]);
        };

        $createVariant = function ($itemId, $name, $priceModifier) use ($now) {
            DB::table('item_variants')->insert([
                'menu_item_id' => $itemId, 'name' => $name,
                'price_modifier' => $priceModifier, 'is_available' => true,
                'created_at' => $now, 'updated_at' => $now,
            ]);
        };

        // ═══════════════════════════════════════════════════════
        // AL-BASHA GRILL
        // ═══════════════════════════════════════════════════════
        $rid = $restaurants['basha'];
        $grills = $createCategory($rid, 'Grills');
        $sides = $createCategory($rid, 'Sides & Salads');
        $drinks = $createCategory($rid, 'Drinks');

        $mixed = $createItem($grills, $rid, 'Mixed Grill Platter', 220);
        $createVariant($mixed, 'Large', 40);
        $kebab = $createItem($grills, $rid, 'Kofta Kebab (4 pcs)', 95);
        $createVariant($kebab, 'Extra Kofta +2 pcs', 45);
        $shish = $createItem($grills, $rid, 'Shish Tawook', 110);
        $createVariant($shish, 'Spicy', 0);
        $createItem($grills, $rid, 'Grilled Lamb Chops', 275);
        $createItem($grills, $rid, 'Grilled Pigeon (Hamam)', 180);
        $createItem($grills, $rid, 'Arayes (Stuffed Bread)', 85);
        $createItem($sides, $rid, 'Fattoush Salad', 45);
        $createItem($sides, $rid, 'Tabouleh', 40);
        $createItem($sides, $rid, 'Hummus with Pita', 50);
        $createItem($drinks, $rid, 'Ayran (Yogurt Drink)', 30);
        $createItem($drinks, $rid, 'Fresh Lemonade', 35);

        // ═══════════════════════════════════════════════════════
        // SAKURA SUSHI HOUSE
        // ═══════════════════════════════════════════════════════
        $rid = $restaurants['sakura'];
        $rolls = $createCategory($rid, 'Sushi Rolls');
        $nigiri = $createCategory($rid, 'Nigiri & Sashimi');
        $hot = $createCategory($rid, 'Hot Dishes');
        $sakDrinks = $createCategory($rid, 'Drinks');

        $cali = $createItem($rolls, $rid, 'California Roll (8 pcs)', 130);
        $createVariant($cali, 'Extra Avocado', 20);
        $createItem($rolls, $rid, 'Spicy Tuna Roll (8 pcs)', 150);
        $createItem($rolls, $rid, 'Dragon Roll (8 pcs)', 175);
        $createItem($rolls, $rid, 'Philadelphia Roll (8 pcs)', 155);
        $createItem($nigiri, $rid, 'Salmon Nigiri (2 pcs)', 80);
        $createItem($nigiri, $rid, 'Tuna Nigiri (2 pcs)', 85);
        $ramen = $createItem($hot, $rid, 'Tonkotsu Ramen', 175);
        $createVariant($ramen, 'Extra Egg', 15);
        $createItem($hot, $rid, 'Gyoza (6 pcs)', 90);
        $createItem($sakDrinks, $rid, 'Matcha Latte', 65);
        $createItem($sakDrinks, $rid, 'Yuzu Lemonade', 55);

        // ═══════════════════════════════════════════════════════
        // BELLA NAPOLI
        // ═══════════════════════════════════════════════════════
        $rid = $restaurants['napoli'];
        $pizza = $createCategory($rid, 'Pizza');
        $pasta = $createCategory($rid, 'Pasta');
        $napoliSides = $createCategory($rid, 'Starters & Sides');

        $marg = $createItem($pizza, $rid, 'Margherita', 120);
        $createVariant($marg, 'Extra Cheese', 20);
        $createItem($pizza, $rid, 'Quattro Formaggi', 165);
        $createItem($pizza, $rid, 'Diavola (Spicy Salami)', 155);
        $carbonara = $createItem($pasta, $rid, 'Spaghetti Carbonara', 145);
        $createVariant($carbonara, 'Extra Guanciale', 25);
        $createItem($pasta, $rid, 'Cacio e Pepe', 130);
        $createItem($pasta, $rid, 'Lasagna al Forno', 150);
        $createItem($napoliSides, $rid, 'Bruschetta al Pomodoro', 55);
        $createItem($napoliSides, $rid, 'Tiramisù', 90);

        // ═══════════════════════════════════════════════════════
        // FORNO ROMANO
        // ═══════════════════════════════════════════════════════
        $rid = $restaurants['forno'];
        $classic = $createCategory($rid, 'Classic Pizza');
        $gourmet = $createCategory($rid, 'Gourmet Pizza');

        $pep = $createItem($classic, $rid, 'Pepperoni', 145);
        $createVariant($pep, 'Double Pepperoni', 30);
        $createItem($classic, $rid, 'Hawaiian', 140);
        $createItem($classic, $rid, 'BBQ Chicken', 155);
        $createItem($gourmet, $rid, 'Black Truffle & Mushroom', 210);
        $createItem($gourmet, $rid, 'Fig & Prosciutto', 195);

        // ═══════════════════════════════════════════════════════
        // SULTAN SHAWARMA
        // ═══════════════════════════════════════════════════════
        $rid = $restaurants['shawarma'];
        $wraps = $createCategory($rid, 'Wraps');
        $plates = $createCategory($rid, 'Plates');
        $sultSides = $createCategory($rid, 'Sides');

        $chickWrap = $createItem($wraps, $rid, 'Chicken Shawarma Wrap', 75);
        $createVariant($chickWrap, 'Extra Garlic', 10);
        $createItem($wraps, $rid, 'Meat Shawarma Wrap', 85);
        $createItem($wraps, $rid, 'Mixed Shawarma Wrap', 90);
        $createItem($wraps, $rid, 'Falafel Wrap', 60);
        $createItem($plates, $rid, 'Chicken Shawarma Plate', 120);
        $createItem($plates, $rid, 'Mixed Plate with Rice', 145);
        $createItem($sultSides, $rid, 'Pickles & Turnip', 20);
        $createItem($sultSides, $rid, 'Crispy Fries', 40);

        // ═══════════════════════════════════════════════════════
        // GREENBOWL
        // ═══════════════════════════════════════════════════════
        $rid = $restaurants['green'];
        $bowls = $createCategory($rid, 'Bowls');
        $salads = $createCategory($rid, 'Salads');
        $smoothies = $createCategory($rid, 'Smoothies & Drinks');

        $protein = $createItem($bowls, $rid, 'Protein Bowl', 175);
        $createVariant($protein, 'Add Grilled Chicken', 35);
        $createItem($bowls, $rid, 'Quinoa & Roasted Veggie Bowl', 160);
        $createItem($bowls, $rid, 'Açaí Bowl', 155);
        $createItem($bowls, $rid, 'Salmon Poke Bowl', 195);
        $caesar = $createItem($salads, $rid, 'Caesar Salad', 95);
        $createVariant($caesar, 'Add Grilled Chicken', 35);
        $createItem($salads, $rid, 'Greek Salad', 85);
        $createItem($smoothies, $rid, 'Green Detox Smoothie', 75);
        $createItem($smoothies, $rid, 'Berry Blast Smoothie', 75);

        // ═══════════════════════════════════════════════════════
        // KOSHARY EL PRINCE
        // ═══════════════════════════════════════════════════════
        $rid = $restaurants['koshary'];
        $kosh = $createCategory($rid, 'Koshary');
        $extras = $createCategory($rid, 'Extras & Sides');
        $koshDrinks = $createCategory($rid, 'Drinks');

        $createItem($kosh, $rid, 'Koshary Small', 25);
        $medium = $createItem($kosh, $rid, 'Koshary Medium', 35);
        $createVariant($medium, 'Extra Dakka (Spicy Tomato)', 5);
        $large = $createItem($kosh, $rid, 'Koshary Large', 45);
        $createVariant($large, 'Extra Crispy Onion', 5);
        $createItem($kosh, $rid, 'Koshary X-Large', 55);
        $createItem($kosh, $rid, 'Family Koshary (serves 4)', 150);
        $createItem($extras, $rid, 'Extra Dakka Sauce (cup)', 10);
        $createItem($extras, $rid, 'Lentil Soup', 30);
        $createItem($koshDrinks, $rid, 'Soft Drink (Can)', 15);
        $createItem($koshDrinks, $rid, 'Tamarind Juice', 25);

        // ═══════════════════════════════════════════════════════
        // LE PETIT CREPE
        // ═══════════════════════════════════════════════════════
        $rid = $restaurants['crepe'];
        $sweet = $createCategory($rid, 'Sweet Crepes');
        $savory = $createCategory($rid, 'Savory Crepes');
        $waffles = $createCategory($rid, 'Waffles');

        $nutella = $createItem($sweet, $rid, 'Nutella Banana', 75);
        $createVariant($nutella, 'Add Whipped Cream', 10);
        $createItem($sweet, $rid, 'Strawberry & Cream', 80);
        $createItem($sweet, $rid, 'Lotus Biscoff', 85);
        $createItem($sweet, $rid, 'Lemon & Sugar Classic', 60);
        $chicken = $createItem($savory, $rid, 'Chicken & Pesto', 95);
        $createVariant($chicken, 'Add Extra Cheese', 15);
        $createItem($savory, $rid, 'Spinach & Feta', 90);
        $createItem($savory, $rid, 'Smoked Salmon & Cream Cheese', 115);
        $createItem($waffles, $rid, 'Classic Waffle with Maple', 85);
        $createItem($waffles, $rid, 'Nutella Waffle', 90);
    }
}