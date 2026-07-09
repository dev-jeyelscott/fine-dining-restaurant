<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Appetizers',
                'description' => 'Elegant starters prepared for sharing and tasting.',
                'sort_order' => 1,
                'items' => [
                    ['name' => 'Truffle Mushroom Crostini', 'description' => 'Toasted artisan bread with wild mushrooms, herbs, and truffle oil.', 'price' => 14.00, 'sort_order' => 1],
                    ['name' => 'Seared Scallops', 'description' => 'Pan-seared scallops with citrus butter and microgreens.', 'price' => 22.00, 'sort_order' => 2],
                    ['name' => 'Burrata Caprese', 'description' => 'Creamy burrata, heirloom tomatoes, basil, and aged balsamic.', 'price' => 18.00, 'sort_order' => 3],
                ],
            ],
            [
                'name' => 'Main Courses',
                'description' => 'Signature entrées crafted for a refined dining experience.',
                'sort_order' => 2,
                'items' => [
                    ['name' => 'Herb-Crusted Salmon', 'description' => 'Atlantic salmon with herb crust, seasonal vegetables, and lemon beurre blanc.', 'price' => 34.00, 'sort_order' => 1],
                    ['name' => 'Filet Mignon', 'description' => 'Tender beef filet with garlic mashed potatoes and red wine reduction.', 'price' => 48.00, 'sort_order' => 2],
                    ['name' => 'Roasted Chicken Supreme', 'description' => 'Free-range chicken breast with rosemary jus and root vegetables.', 'price' => 32.00, 'sort_order' => 3],
                ],
            ],
            [
                'name' => 'Desserts',
                'description' => 'House-made desserts for a memorable finish.',
                'sort_order' => 3,
                'items' => [
                    ['name' => 'Classic Crème Brûlée', 'description' => 'Vanilla custard with caramelized sugar crust.', 'price' => 12.00, 'sort_order' => 1],
                    ['name' => 'Chocolate Lava Cake', 'description' => 'Warm chocolate cake with molten center and vanilla cream.', 'price' => 13.00, 'sort_order' => 2],
                    ['name' => 'Seasonal Fruit Tart', 'description' => 'Buttery tart shell with pastry cream and seasonal fruits.', 'price' => 11.00, 'sort_order' => 3],
                ],
            ],
            [
                'name' => 'Beverages',
                'description' => 'Refreshing beverages and curated non-alcoholic selections.',
                'sort_order' => 4,
                'items' => [
                    ['name' => 'Sparkling Citrus Refresher', 'description' => 'Sparkling water, citrus, mint, and house syrup.', 'price' => 8.00, 'sort_order' => 1],
                    ['name' => 'Iced Berry Tea', 'description' => 'Cold-brewed tea with berry infusion.', 'price' => 7.00, 'sort_order' => 2],
                    ['name' => 'Fresh Lemonade', 'description' => 'Fresh lemon juice, simple syrup, and chilled water.', 'price' => 6.00, 'sort_order' => 3],
                ],
            ],
        ];

        foreach ($categories as $categoryData) {
            $items = $categoryData['items'];
            unset($categoryData['items']);

            $category = MenuCategory::updateOrCreate(
                ['slug' => Str::slug($categoryData['name'])],
                [
                    ...$categoryData,
                    'slug' => Str::slug($categoryData['name']),
                    'is_visible' => true,
                ],
            );

            foreach ($items as $itemData) {
                MenuItem::updateOrCreate(
                    [
                        'menu_category_id' => $category->id,
                        'slug' => Str::slug($itemData['name']),
                    ],
                    [
                        ...$itemData,
                        'menu_category_id' => $category->id,
                        'slug' => Str::slug($itemData['name']),
                        'image_path' => null,
                        'is_visible' => true,
                    ],
                );
            }
        }
    }
}
