<?php

use App\Models\GalleryImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function (): void {
    Storage::fake('public');
});

test('home and order inquiry omit the missing legacy hero asset when no managed image exists', function (): void {
    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee('/images/bg-hero.png', false);

    $this->get(route('order-inquiry.create'))
        ->assertOk()
        ->assertDontSee('/images/bg-hero.png', false);
});

test('home and order inquiry render an existing managed responsive hero image', function (): void {
    $imagePath = UploadedFile::fake()
        ->image('dining-room.jpg', 2400, 1600)
        ->storeAs('gallery', 'dining-room.jpg', 'public');

    expect($imagePath)->toBeString();

    GalleryImage::query()->create([
        'title' => 'Grand Dining Room',
        'alt_text' => 'Grand dining room with warm ambient lighting',
        'image_path' => $imagePath,
        'category' => 'interior',
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    $expectedHeroUrl = '/storage/gallery/variants/dining-room-hero.jpg';

    $this->get(route('home'))
        ->assertOk()
        ->assertSee($expectedHeroUrl, false)
        ->assertSee('srcset=', false)
        ->assertSee('loading="eager"', false)
        ->assertSee('fetchpriority="high"', false)
        ->assertDontSee('/images/bg-hero.png', false);

    $this->get(route('order-inquiry.create'))
        ->assertOk()
        ->assertSee($expectedHeroUrl, false)
        ->assertSee('srcset=', false)
        ->assertSee('loading="eager"', false)
        ->assertSee('fetchpriority="high"', false)
        ->assertSee('Grand dining room with warm ambient lighting')
        ->assertDontSee('/images/bg-hero.png', false);
});
