<?php

use App\Models\GalleryImage;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('homepage renders the premium restaurant experience and approved calls to action', function (): void {
    $this->get(route('home'))
        ->assertOk()
        ->assertSeeText('A table worth remembering')
        ->assertSeeText('An invitation to linger')
        ->assertSeeText('The art of the menu')
        ->assertSeeText('Make an evening of it')
        ->assertSeeText('A taste of the house, at home')
        ->assertSeeText('A setting for life’s finest gatherings')
        ->assertSeeText('A glimpse of the experience')
        ->assertSeeText('Request a Reservation')
        ->assertSeeText('Submit Order Inquiry')
        ->assertSeeText('View Full Menu')
        ->assertSeeText('Explore Banquet Hall')
        ->assertSeeText('Discover the Gallery');
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

test('homepage gallery preview renders an accessible editorial carousel contract', function (): void {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('data-home-gallery', false)
        ->assertSee('data-home-gallery-viewport', false)
        ->assertSee('data-home-gallery-track', false)
        ->assertSee('data-home-gallery-slide', false)
        ->assertSee('data-home-gallery-previous', false)
        ->assertSee('data-home-gallery-next', false)
        ->assertSee('data-home-gallery-current', false)
        ->assertSee('data-home-gallery-total', false);
});

test('public entry lazy loads homepage motion without statically importing gsap', function (): void {
    $publicEntry = (string) file_get_contents(resource_path('js/app.js'));

    expect($publicEntry)
        ->toContain('document.querySelector("[data-home-motion]")')
        ->toContain('import("./public-animations")')
        ->not->toContain('import { initPublicAnimations } from "./public-animations"');
});

test('homepage motion reads media conditions and reverts its match media lifecycle', function (): void {
    $motionModule = (string) file_get_contents(resource_path('js/public-animations.js'));

    expect($motionModule)
        ->toContain('context.conditions')
        ->toContain('reducedMotion')
        ->toContain('media.revert()')
        ->not->toContain('({ reducedMotion }) =>');
});

test('homepage carousel initializer keeps the required interaction and lifecycle contracts', function (): void {
    $carouselModule = (string) file_get_contents(resource_path('js/home-gallery-carousel.js'));

    expect($carouselModule)
        ->toContain('Draggable')
        ->toContain('ArrowLeft')
        ->toContain('ArrowRight')
        ->toContain('event.key === "Home"')
        ->toContain('event.key === "End"')
        ->toContain('ResizeObserver')
        ->toContain('draggable?.kill()')
        ->toContain('reducedMotion');
});

test('homepage carousel keeps the six-image controller bound and safe one-image state', function (): void {
    expect((string) file_get_contents(app_path('Http/Controllers/PublicSite/HomeController.php')))
        ->toContain('->limit(6)');

    expect((string) file_get_contents(resource_path('views/components/public/home-gallery-carousel.blade.php')))
        ->not->toContain('take(5)')
        ->toContain('$galleryImages->count() > 1');

    GalleryImage::query()->delete();
    GalleryImage::query()->create([
        'title' => 'A single gallery moment',
        'alt_text' => 'A single gallery moment',
        'image_path' => 'gallery/single-gallery-moment.jpg',
        'category' => 'interior',
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('data-home-gallery-slide', false)
        ->assertDontSee('data-home-gallery-previous', false)
        ->assertDontSee('data-home-gallery-next', false);
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
