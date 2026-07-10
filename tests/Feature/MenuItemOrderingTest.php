<?php

use App\Models\MenuCategory;
use App\Models\MenuItem;

test('home and menu pages use the same menu item ordering', function (): void {
    $category = MenuCategory::query()->create([
        'name' => 'Main Courses',
        'slug' => 'main-courses',
        'description' => 'Main course dishes.',
        'sort_order' => 10,
        'is_visible' => true,
    ]);

    foreach ([
        ['name' => 'Third Dish', 'sort_order' => 30],
        ['name' => 'First Dish', 'sort_order' => 10],
        ['name' => 'Second Dish', 'sort_order' => 20],
    ] as $item) {
        MenuItem::query()->create([
            'menu_category_id' => $category->id,
            'name' => $item['name'],
            'slug' => str($item['name'])->slug()->toString(),
            'description' => "Description for {$item['name']}.",
            'price' => '100.00',
            'sort_order' => $item['sort_order'],
            'is_visible' => true,
        ]);
    }

    foreach (['home', 'menu'] as $routeName) {
        $this->get(route($routeName))
            ->assertOk()
            ->assertSeeInOrder([
                'First Dish',
                'Second Dish',
                'Third Dish',
            ]);
    }
});
