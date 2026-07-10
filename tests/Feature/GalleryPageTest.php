<?php

use App\Models\GalleryImage;
use App\Models\Page;
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

test('gallery page renders sanitized rich editor content without escaped tags', function (): void {
    Page::query()->create([
        'slug' => 'gallery',
        'title' => 'Gallery',
        'content' => '<p>Every image reflects <strong>refined hospitality</strong>.</p><p onclick="alert(1)">Managed through the CMS.</p>',
        'is_published' => true,
    ]);

    $this->get(route('gallery'))
        ->assertOk()
        ->assertSee('<strong>refined hospitality</strong>', false)
        ->assertSeeText('Managed through the CMS.')
        ->assertDontSee('&lt;p&gt;', false)
        ->assertDontSee('onclick=', false);
});

test('gallery page keeps plain seeded content readable', function (): void {
    Page::query()->create([
        'slug' => 'gallery',
        'title' => 'Gallery',
        'content' => 'A plain seeded gallery introduction remains readable.',
        'is_published' => true,
    ]);

    $this->get(route('gallery'))
        ->assertOk()
        ->assertSeeText('A plain seeded gallery introduction remains readable.');
});

test('gallery page uses the approved introduction when page content is empty', function (): void {
    Page::query()->create([
        'slug' => 'gallery',
        'title' => 'Gallery',
        'content' => '',
        'is_published' => true,
    ]);

    $this->get(route('gallery'))
        ->assertOk()
        ->assertSeeText('From softly lit dining rooms to carefully finished plates and thoughtfully prepared celebrations, every image reflects the warmth, refinement, and attention to detail guests can expect.');
});

test('gallery page handles a single visible image', function (): void {
    GalleryImage::query()->create([
        'title' => 'Intimate Dining Room',
        'alt_text' => 'Intimate dining room with warm lighting',
        'image_path' => 'gallery/intimate-dining-room.jpg',
        'category' => 'Ambiance',
        'sort_order' => 10,
        'is_visible' => true,
    ]);

    $this->get(route('gallery'))
        ->assertOk()
        ->assertSeeText('Intimate Dining Room')
        ->assertSeeText('Visible moments')
        ->assertSeeText('Gallery categories');
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
