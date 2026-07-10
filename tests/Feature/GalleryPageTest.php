<?php

use App\Models\GalleryImage;
use Illuminate\Support\Facades\Storage;

beforeEach(function (): void {
    Storage::fake('public');
});

test('gallery page presents visible images in a premium editorial layout', function (): void {
    GalleryImage::query()->create([
        'title' => 'Grand Dining Room',
        'alt_text' => 'Warmly lit fine-dining room',
        'image_path' => 'gallery/grand-dining-room.jpg',
        'category' => 'Ambiance',
        'sort_order' => 30,
        'is_visible' => true,
    ]);

    GalleryImage::query()->create([
        'title' => 'Golden Truffle Tenderloin',
        'alt_text' => 'Golden truffle beef tenderloin',
        'image_path' => 'gallery/golden-truffle-tenderloin.jpg',
        'category' => 'Signature Dish',
        'sort_order' => 20,
        'is_visible' => true,
    ]);

    GalleryImage::query()->create([
        'title' => 'Private Celebration',
        'alt_text' => 'Private banquet celebration setup',
        'image_path' => 'gallery/private-celebration.jpg',
        'category' => 'Celebrations',
        'sort_order' => 10,
        'is_visible' => true,
    ]);

    GalleryImage::query()->create([
        'title' => 'Hidden Preparation Area',
        'alt_text' => 'Hidden preparation area',
        'image_path' => 'gallery/hidden-preparation-area.jpg',
        'category' => 'Operations',
        'sort_order' => 40,
        'is_visible' => false,
    ]);

    $this->get(route('gallery'))
        ->assertOk()
        ->assertSee('id="gallery-collection"', false)
        ->assertSee('fetchpriority="high"', false)
        ->assertSeeText('A closer look at the experience')
        ->assertSeeText('The Collection')
        ->assertSeeText('Grand Dining Room')
        ->assertSeeText('Golden Truffle Tenderloin')
        ->assertSeeText('Private Celebration')
        ->assertSeeText('Ambiance')
        ->assertSeeText('Signature Dish')
        ->assertSeeText('Celebrations')
        ->assertDontSeeText('Hidden Preparation Area');
});

test('gallery page keeps its empty state and scope safe continuation links', function (): void {
    $this->get(route('gallery'))
        ->assertOk()
        ->assertSeeText('No visible gallery images yet. Add images from the Filament admin panel.')
        ->assertSeeText('Explore Banquet Hall')
        ->assertSeeText('Contact Us')
        ->assertDontSeeText('Book Now')
        ->assertDontSeeText('Order Now');
});
