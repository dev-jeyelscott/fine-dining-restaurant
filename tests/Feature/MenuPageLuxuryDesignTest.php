<?php

use App\Models\MenuCategory;
use App\Models\MenuItem;

test('menu page presents visible categories and items in the luxury menu layout', function (): void {
    $category = MenuCategory::query()->create([
        'name' => 'Chef Selections',
        'slug' => 'chef-selections',
        'description' => 'A considered collection from the kitchen.',
        'sort_order' => 10,
        'is_visible' => true,
    ]);

    MenuItem::query()->create([
        'menu_category_id' => $category->id,
        'name' => 'Truffle-Crusted Beef Tenderloin',
        'slug' => 'truffle-crusted-beef-tenderloin',
        'description' => 'Served with seasonal accompaniments.',
        'price' => '1480.00',
        'sort_order' => 10,
        'is_visible' => true,
    ]);

    $this->get(route('menu'))
        ->assertOk()
        ->assertSee('id="menu-selections"', false)
        ->assertSee('aria-label="Menu categories"', false)
        ->assertSee('href="#category-chef-selections"', false)
        ->assertSee('id="category-chef-selections"', false)
        ->assertSeeTextInOrder([
            'The full menu',
            'Chef Selections',
            'Truffle-Crusted Beef Tenderloin',
            'Submit Order Inquiry',
        ]);
});
