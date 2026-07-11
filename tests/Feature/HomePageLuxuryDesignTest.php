<?php

use App\Models\GalleryImage;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('homepage renders the premium restaurant experience and approved calls to action', function (): void {
    $this->get(route('home'))
        ->assertOk()
        ->assertSeeText('Fine dining, thoughtfully served')
        ->assertSeeText('Fine dining made warm and welcoming')
        ->assertSeeText('Signature selections')
        ->assertSeeText('Plan your dining experience')
        ->assertSeeText('Bring the experience home')
        ->assertSeeText('A refined setting for meaningful occasions')
        ->assertSeeText('Ambiance, dishes, and celebrations')
        ->assertSeeText('Request a Reservation')
        ->assertSeeText('Submit Order Inquiry')
        ->assertSeeText('View Full Menu')
        ->assertSeeText('Explore Banquet Hall')
        ->assertSeeText('View the Gallery');
});

test('homepage remains scope safe and does not fabricate social proof', function (): void {
    $response = $this->get(route('home'))->assertOk();

    foreach ([
        'Book a Table',
        'Reserve a Table',
        'Order Online',
        'Checkout',
        'Pay Now',
        'Michelin',
        'Forbes',
        'Zagat',
        'New York Times',
    ] as $prohibitedCopy) {
        $response->assertDontSeeText($prohibitedCopy);
    }
});

test('homepage navigation exposes an accessible mobile menu contract', function (): void {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('aria-controls="mobile-navigation"', false)
        ->assertSee(':aria-expanded="open.toString()"', false)
        ->assertSee('aria-label="Primary navigation"', false)
        ->assertSee('aria-label="Mobile navigation"', false);
});

test('homepage exposes semantic hooks for progressive luxury motion', function (): void {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('data-home-motion', false)
        ->assertSee('data-gsap="hero-image"', false)
        ->assertSee('data-gsap="menu"', false)
        ->assertSee('data-gsap="panel"', false)
        ->assertSee('data-gsap="gallery"', false)
        ->assertSee('data-gsap-reveal', false);
});

test('homepage displays only the first three visible menu items in configured order', function (): void {
    $category = MenuCategory::query()->create([
        'name' => 'Chef Selections',
        'slug' => 'chef-selections',
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    foreach ([
        ['First Course', 1, true],
        ['Second Course', 2, true],
        ['Third Course', 3, true],
        ['Fourth Course', 4, true],
        ['Hidden Course', 0, false],
    ] as [$name, $sortOrder, $isVisible]) {
        MenuItem::query()->create([
            'menu_category_id' => $category->id,
            'name' => $name,
            'description' => "Description for {$name}",
            'price' => 100 + $sortOrder,
            'sort_order' => $sortOrder,
            'is_visible' => $isVisible,
        ]);
    }

    $this->get(route('home'))
        ->assertOk()
        ->assertSeeTextInOrder([
            'First Course',
            'Second Course',
            'Third Course',
        ])
        ->assertDontSeeText('Fourth Course')
        ->assertDontSeeText('Hidden Course');
});

test('homepage renders admin managed gallery images through public storage URLs', function (): void {
    Storage::fake('public');

    $imagePath = UploadedFile::fake()
        ->image('grand-dining-room.jpg', 2400, 1600)
        ->storeAs('gallery', 'grand-dining-room.jpg', 'public');

    expect($imagePath)->toBeString();

    GalleryImage::query()->create([
        'title' => 'Grand Dining Room',
        'alt_text' => 'Grand dining room with warm lighting',
        'image_path' => $imagePath,
        'category' => 'interior',
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('/storage/gallery/variants/grand-dining-room-hero.jpg', false)
        ->assertSee('Grand dining room with warm lighting');
});
