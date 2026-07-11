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

test('menu page exposes progressive motion hooks without hiding native content', function (): void {
    $category = MenuCategory::query()->create([
        'name' => 'Seasonal Courses',
        'slug' => 'seasonal-courses',
        'description' => 'A seasonal menu prepared by our kitchen.',
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    MenuItem::query()->create([
        'menu_category_id' => $category->id,
        'name' => 'Roasted Garden Vegetables',
        'slug' => 'roasted-garden-vegetables',
        'description' => 'Finished with herbs and citrus.',
        'price' => '620.00',
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    $this->get(route('menu'))
        ->assertOk()
        ->assertSee('data-home-motion data-public-motion="menu"', false)
        ->assertSee('data-menu-motion="hero"', false)
        ->assertSee('data-menu-motion="category-nav"', false)
        ->assertSee('data-menu-category-link', false)
        ->assertSee('aria-current="true"', false)
        ->assertSee('data-menu-motion="course"', false)
        ->assertSee('data-menu-motion="card-image"', false)
        ->assertSee('data-menu-motion="closing-cta"', false)
        ->assertSeeText('Roasted Garden Vegetables')
        ->assertSeeText('personally review the details and confirm availability');
});
