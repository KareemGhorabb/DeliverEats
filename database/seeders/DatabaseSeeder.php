<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Reset DB
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('item_variants')->truncate();
        DB::table('menu_items')->truncate();
        DB::table('menu_categories')->truncate();
        DB::table('restaurants')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ═══════════════════════════════
        // USERS
        // ═══════════════════════════════
        $owner = User::create([
            'name' => 'Restaurant Owner',
            'email' => 'owner@delivereats.com',
            'password' => bcrypt('password'),
            'role' => 'restaurant_owner',
            'phone' => '01000000002',
        ]);

        // ═══════════════════════════════
        // RESTAURANTS
        // ═══════════════════════════════
        $restaurants = [];

        $restaurants['basha'] = DB::table('restaurants')->insertGetId([
            'user_id' => $owner->id,
            'name' => 'Al-Basha Grill',
            'slug' => 'al-basha-grill',
            'description' => 'Middle Eastern grills',
            'category' => 'Middle Eastern',
            'phone' => '+20223456789',
            'address' => 'Downtown Cairo',
            'latitude' => 30.0444,
            'longitude' => 31.2357,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $restaurants['sakura'] = DB::table('restaurants')->insertGetId([
            'user_id' => $owner->id,
            'name' => 'Sakura Sushi House',
            'slug' => 'sakura-sushi-house',
            'description' => 'Japanese sushi',
            'category' => 'Japanese',
            'phone' => '+20234567890',
            'address' => 'Zamalek',
            'latitude' => 30.0600,
            'longitude' => 31.2230,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $restaurants['napoli'] = DB::table('restaurants')->insertGetId([
            'user_id' => $owner->id,
            'name' => 'Bella Napoli',
            'slug' => 'bella-napoli',
            'description' => 'Italian food',
            'category' => 'Italian',
            'phone' => '+20245678901',
            'address' => 'Maadi',
            'latitude' => 29.9597,
            'longitude' => 31.2503,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $restaurants['forno'] = DB::table('restaurants')->insertGetId([
            'user_id' => $owner->id,
            'name' => 'Forno Romano',
            'slug' => 'forno-romano',
            'description' => 'Roman pizza',
            'category' => 'Italian',
            'phone' => '+20225551100',
            'address' => 'Dokki',
            'latitude' => 30.0370,
            'longitude' => 31.2118,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $restaurants['shawarma'] = DB::table('restaurants')->insertGetId([
            'user_id' => $owner->id,
            'name' => 'Sultan Shawarma',
            'slug' => 'sultan-shawarma',
            'description' => 'Shawarma',
            'category' => 'Middle Eastern',
            'phone' => '+20225552200',
            'address' => 'Nasr City',
            'latitude' => 30.0638,
            'longitude' => 31.3411,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $restaurants['green'] = DB::table('restaurants')->insertGetId([
            'user_id' => $owner->id,
            'name' => 'GreenBowl',
            'slug' => 'greenbowl',
            'description' => 'Healthy food',
            'category' => 'Healthy',
            'phone' => '+20225553300',
            'address' => 'Maadi',
            'latitude' => 29.9710,
            'longitude' => 31.2560,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $restaurants['koshary'] = DB::table('restaurants')->insertGetId([
            'user_id' => $owner->id,
            'name' => 'Koshary El Prince',
            'slug' => 'koshary-el-prince',
            'description' => 'Koshary',
            'category' => 'Egyptian',
            'phone' => '+20225554400',
            'address' => 'Downtown',
            'latitude' => 30.0626,
            'longitude' => 31.2497,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $restaurants['crepe'] = DB::table('restaurants')->insertGetId([
            'user_id' => $owner->id,
            'name' => 'Le Petit Crepe',
            'slug' => 'le-petit-crepe',
            'description' => 'Crepes',
            'category' => 'French',
            'phone' => '+20225555500',
            'address' => 'Heliopolis',
            'latitude' => 30.0877,
            'longitude' => 31.3216,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // ═══════════════════════════════
        // HELPER FUNCTIONS
        // ═══════════════════════════════
        $createCategory = function ($restaurantId, $name) use ($now) {
            return DB::table('menu_categories')->insertGetId([
                'restaurant_id' => $restaurantId,
                'name' => $name,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        };

        $createItem = function ($categoryId, $name, $price) use ($now) {
            return DB::table('menu_items')->insertGetId([
                'menu_category_id' => $categoryId,
                'name' => $name,
                'price' => $price,
                'is_available' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        };

        $createVariant = function ($itemId, $name, $priceModifier) use ($now) {
            DB::table('item_variants')->insert([
                'menu_item_id' => $itemId,
                'name' => $name,
                'price_modifier' => $priceModifier,
                'is_available' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        };

        // ═══════════════════════════════════════════════════════
        // AL-BASHA GRILL
        // ═══════════════════════════════════════════════════════
        $grills   = $createCategory($restaurants['basha'], 'Grills');
        $sides    = $createCategory($restaurants['basha'], 'Sides & Salads');
        $drinks   = $createCategory($restaurants['basha'], 'Drinks');

        $mixed    = $createItem($grills, 'Mixed Grill Platter',       220);
        $createVariant($mixed, 'Large', 40);

        $kebab    = $createItem($grills, 'Kofta Kebab (4 pcs)',        95);
        $createVariant($kebab, 'Extra Kofta +2 pcs', 45);

        $shish    = $createItem($grills, 'Shish Tawook',              110);
        $createVariant($shish, 'Spicy', 0);

        $lamb     = $createItem($grills, 'Grilled Lamb Chops',        275);
        $createVariant($lamb, 'Extra Sauce', 15);

        $hamam    = $createItem($grills, 'Grilled Pigeon (Hamam)',     180);
        $arayes   = $createItem($grills, 'Arayes (Stuffed Bread)',     85);
        $liver    = $createItem($grills, 'Grilled Liver',              70);
        $quail    = $createItem($grills, 'Grilled Quail (2 pcs)',     160);

        $fattoush = $createItem($sides, 'Fattoush Salad',              45);
        $tabouleh = $createItem($sides, 'Tabouleh',                    40);
        $hummus   = $createItem($sides, 'Hummus with Pita',            50);
        $fries    = $createItem($sides, 'Grilled Veggie Platter',      65);

        $ayran    = $createItem($drinks, 'Ayran (Yogurt Drink)',       30);
        $lemon    = $createItem($drinks, 'Fresh Lemonade',             35);


        // ═══════════════════════════════════════════════════════
        // SAKURA SUSHI HOUSE
        // ═══════════════════════════════════════════════════════
        $rolls    = $createCategory($restaurants['sakura'], 'Sushi Rolls');
        $nigiri   = $createCategory($restaurants['sakura'], 'Nigiri & Sashimi');
        $hot      = $createCategory($restaurants['sakura'], 'Hot Dishes');
        $sakDrinks = $createCategory($restaurants['sakura'], 'Drinks');

        $cali     = $createItem($rolls, 'California Roll (8 pcs)',    130);
        $createVariant($cali, 'Extra Avocado', 20);

        $spicy    = $createItem($rolls, 'Spicy Tuna Roll (8 pcs)',    150);
        $dragon   = $createItem($rolls, 'Dragon Roll (8 pcs)',        175);
        $philly   = $createItem($rolls, 'Philadelphia Roll (8 pcs)',  155);
        $rainbow  = $createItem($rolls, 'Rainbow Roll (8 pcs)',       185);
        $tempura  = $createItem($rolls, 'Shrimp Tempura Roll (8 pcs)',160);

        $salmonN  = $createItem($nigiri, 'Salmon Nigiri (2 pcs)',      80);
        $tunaN    = $createItem($nigiri, 'Tuna Nigiri (2 pcs)',        85);
        $salmonS  = $createItem($nigiri, 'Salmon Sashimi (5 pcs)',    120);

        $ramen    = $createItem($hot, 'Tonkotsu Ramen',               175);
        $createVariant($ramen, 'Extra Egg', 15);

        $gyoza    = $createItem($hot, 'Gyoza (6 pcs)',                 90);
        $edamame  = $createItem($hot, 'Edamame',                       50);

        $matchaL  = $createItem($sakDrinks, 'Matcha Latte',            65);
        $yuzu     = $createItem($sakDrinks, 'Yuzu Lemonade',           55);


        // ═══════════════════════════════════════════════════════
        // BELLA NAPOLI
        // ═══════════════════════════════════════════════════════
        $pizza    = $createCategory($restaurants['napoli'], 'Pizza');
        $pasta    = $createCategory($restaurants['napoli'], 'Pasta');
        $napoliSides = $createCategory($restaurants['napoli'], 'Starters & Sides');

        $marg     = $createItem($pizza, 'Margherita',                 120);
        $createVariant($marg, 'Extra Cheese', 20);

        $quattro  = $createItem($pizza, 'Quattro Formaggi',           165);
        $diavola  = $createItem($pizza, 'Diavola (Spicy Salami)',     155);
        $funghi   = $createItem($pizza, 'Funghi e Tartufo',           170);
        $prosciutto = $createItem($pizza, 'Prosciutto e Rucola',      175);
        $vegNap   = $createItem($pizza, 'Verdure (Vegetarian)',       140);

        $carbonara = $createItem($pasta, 'Spaghetti Carbonara',       145);
        $createVariant($carbonara, 'Extra Guanciale', 25);

        $amatriciana = $createItem($pasta, 'Bucatini all\'Amatriciana', 135);
        $cacio    = $createItem($pasta, 'Cacio e Pepe',               130);
        $lasagna  = $createItem($pasta, 'Lasagna al Forno',           150);
        $pesto    = $createItem($pasta, 'Trofie al Pesto',            125);

        $bruschetta = $createItem($napoliSides, 'Bruschetta al Pomodoro', 55);
        $caprese  = $createItem($napoliSides, 'Insalata Caprese',      85);
        $tiramisu = $createItem($napoliSides, 'Tiramisù',              90);


        // ═══════════════════════════════════════════════════════
        // FORNO ROMANO
        // ═══════════════════════════════════════════════════════
        $classic  = $createCategory($restaurants['forno'], 'Classic Pizza');
        $gourmet  = $createCategory($restaurants['forno'], 'Gourmet Pizza');
        $fornoPasta = $createCategory($restaurants['forno'], 'Pasta');
        $fornoSides = $createCategory($restaurants['forno'], 'Sides');

        $pep      = $createItem($classic, 'Pepperoni',                145);
        $createVariant($pep, 'Double Pepperoni', 30);

        $hawaii   = $createItem($classic, 'Hawaiian',                 140);
        $bbq      = $createItem($classic, 'BBQ Chicken',              155);
        $fourSeas = $createItem($classic, 'Four Seasons',             160);
        $napolitana = $createItem($classic, 'Napolitana',             135);

        $truffle  = $createItem($gourmet, 'Black Truffle & Mushroom', 210);
        $figProsc = $createItem($gourmet, 'Fig & Prosciutto',         195);
        $burrata  = $createItem($gourmet, 'Burrata & Cherry Tomato',  185);

        $pennAr   = $createItem($fornoPasta, 'Penne all\'Arrabbiata', 110);
        $gnocchi  = $createItem($fornoPasta, 'Gnocchi al Pomodoro',   125);
        $rigatoni = $createItem($fornoPasta, 'Rigatoni alla Norma',   120);

        $garlic   = $createItem($fornoSides, 'Garlic Bread',           40);
        $mixedSal = $createItem($fornoSides, 'Mixed Green Salad',       55);


        // ═══════════════════════════════════════════════════════
        // SULTAN SHAWARMA
        // ═══════════════════════════════════════════════════════
        $wraps    = $createCategory($restaurants['shawarma'], 'Wraps');
        $plates   = $createCategory($restaurants['shawarma'], 'Plates');
        $sultSides = $createCategory($restaurants['shawarma'], 'Sides');

        $chickWrap = $createItem($wraps, 'Chicken Shawarma Wrap',      75);
        $createVariant($chickWrap, 'Extra Garlic', 10);

        $meatWrap = $createItem($wraps, 'Meat Shawarma Wrap',          85);
        $createVariant($meatWrap, 'Extra Sauce', 10);

        $mixedWrap = $createItem($wraps, 'Mixed Shawarma Wrap',        90);
        $falafelWrap = $createItem($wraps, 'Falafel Wrap',             60);
        $crunchyWrap = $createItem($wraps, 'Crunchy Chicken Wrap',     80);
        $caesarWrap  = $createItem($wraps, 'Caesar Chicken Wrap',      85);

        $chickPlate = $createItem($plates, 'Chicken Shawarma Plate',  120);
        $meatPlate  = $createItem($plates, 'Meat Shawarma Plate',     135);
        $mixedPlate = $createItem($plates, 'Mixed Plate with Rice',   145);
        $falafelPlate = $createItem($plates, 'Falafel Plate',          90);

        $tabbSide = $createItem($sultSides, 'Tabouleh',                35);
        $pickles  = $createItem($sultSides, 'Pickles & Turnip',        20);
        $garSauce = $createItem($sultSides, 'Garlic Sauce (100g)',     25);
        $fries2   = $createItem($sultSides, 'Crispy Fries',            40);


        // ═══════════════════════════════════════════════════════
        // GREENBOWL
        // ═══════════════════════════════════════════════════════
        $bowls    = $createCategory($restaurants['green'], 'Bowls');
        $salads   = $createCategory($restaurants['green'], 'Salads');
        $smoothies = $createCategory($restaurants['green'], 'Smoothies & Drinks');

        $protein  = $createItem($bowls, 'Protein Bowl',               175);
        $createVariant($protein, 'Add Grilled Chicken', 35);

        $quinoa   = $createItem($bowls, 'Quinoa & Roasted Veggie Bowl', 160);
        $acai     = $createItem($bowls, 'Açaí Bowl',                  155);
        $buddha   = $createItem($bowls, 'Buddha Bowl',                150);
        $salmon   = $createItem($bowls, 'Salmon Poke Bowl',           195);
        $falafelBowl = $createItem($bowls, 'Falafel & Hummus Bowl',   140);

        $caesar   = $createItem($salads, 'Caesar Salad',               95);
        $createVariant($caesar, 'Add Grilled Chicken', 35);

        $kale     = $createItem($salads, 'Kale & Avocado Salad',      110);
        $greek    = $createItem($salads, 'Greek Salad',                85);
        $mango    = $createItem($salads, 'Mango & Quinoa Salad',      100);

        $greenSm  = $createItem($smoothies, 'Green Detox Smoothie',    75);
        $berryS   = $createItem($smoothies, 'Berry Blast Smoothie',    75);
        $coldBrew = $createItem($smoothies, 'Cold Brew Coffee',         60);


        // ═══════════════════════════════════════════════════════
        // KOSHARY EL PRINCE
        // ═══════════════════════════════════════════════════════
        $kosh     = $createCategory($restaurants['koshary'], 'Koshary');
        $extras   = $createCategory($restaurants['koshary'], 'Extras & Sides');
        $koshDrinks = $createCategory($restaurants['koshary'], 'Drinks');

        $small    = $createItem($kosh, 'Koshary Small',                25);
        $medium   = $createItem($kosh, 'Koshary Medium',               35);
        $createVariant($medium, 'Extra Dakka (Spicy Tomato)', 5);

        $large    = $createItem($kosh, 'Koshary Large',                45);
        $createVariant($large, 'Extra Crispy Onion', 5);

        $xlarge   = $createItem($kosh, 'Koshary X-Large',              55);
        $family   = $createItem($kosh, 'Family Koshary (serves 4)',   150);
        $vermicelli = $createItem($kosh, 'Vermicelli Koshary',         30);

        $extraDakka = $createItem($extras, 'Extra Dakka Sauce (cup)',  10);
        $extraVinegar = $createItem($extras, 'Extra Vinegar Sauce (cup)', 8);
        $extraOnion = $createItem($extras, 'Extra Crispy Onion (cup)', 10);
        $fried    = $createItem($extras, 'Fried Egg',                  15);
        $sausage  = $createItem($extras, 'Grilled Sausage',            30);
        $lentilSoup = $createItem($extras, 'Lentil Soup',              30);

        $soda     = $createItem($koshDrinks, 'Soft Drink (Can)',        15);
        $water    = $createItem($koshDrinks, 'Mineral Water',           10);
        $tamarind = $createItem($koshDrinks, 'Tamarind Juice',          25);


        // ═══════════════════════════════════════════════════════
        // LE PETIT CREPE
        // ═══════════════════════════════════════════════════════
        $sweet    = $createCategory($restaurants['crepe'], 'Sweet Crepes');
        $savory   = $createCategory($restaurants['crepe'], 'Savory Crepes');
        $waffles  = $createCategory($restaurants['crepe'], 'Waffles');
        $crepeDrinks = $createCategory($restaurants['crepe'], 'Drinks');

        $nutella  = $createItem($sweet, 'Nutella Banana',               75);
        $createVariant($nutella, 'Add Whipped Cream', 10);

        $strawberry = $createItem($sweet, 'Strawberry & Cream',         80);
        $lotus    = $createItem($sweet, 'Lotus Biscoff',                85);
        $lemon    = $createItem($sweet, 'Lemon & Sugar Classic',        60);
        $caramel  = $createItem($sweet, 'Caramel Apple',                80);
        $mixed2   = $createItem($sweet, 'Mixed Berries & Vanilla',      85);

        $mushroom = $createItem($savory, 'Mushroom & Cheese',           90);
        $chicken  = $createItem($savory, 'Chicken & Pesto',             95);
        $createVariant($chicken, 'Add Extra Cheese', 15);

        $spinach  = $createItem($savory, 'Spinach & Feta',              90);
        $smoked   = $createItem($savory, 'Smoked Salmon & Cream Cheese', 115);
        $turkey   = $createItem($savory, 'Turkey & Mushroom',           95);

        $classicW = $createItem($waffles, 'Classic Waffle with Maple',  85);
        $nutellaW = $createItem($waffles, 'Nutella Waffle',             90);
        $berryW   = $createItem($waffles, 'Berry & Ice Cream Waffle',  100);

        $hotChoc  = $createItem($crepeDrinks, 'Hot Chocolate',          55);
        $icedMatch = $createItem($crepeDrinks, 'Iced Matcha Latte',     65);
        $freshOJ  = $createItem($crepeDrinks, 'Fresh Orange Juice',     50);
    }
}