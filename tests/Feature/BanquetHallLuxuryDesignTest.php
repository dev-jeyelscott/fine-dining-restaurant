<?php

use App\Models\GalleryImage;

test('banquet hall follows the luxury homepage design language and preserves inquiry actions', function (): void {
    GalleryImage::query()->create([
        'title' => 'Grand Banquet Room',
        'alt_text' => 'Elegant banquet room prepared for dinner',
        'image_path' => 'gallery/grand-banquet-room.webp',
        'category' => 'banquet',
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    GalleryImage::query()->create([
        'title' => 'Intimate Celebration',
        'alt_text' => 'A banquet table set for an intimate celebration',
        'image_path' => 'gallery/intimate-celebration.webp',
        'category' => 'banquet',
        'sort_order' => 2,
        'is_visible' => true,
    ]);

    $this->get(route('banquet-hall'))
        ->assertOk()
        ->assertSee('data-banquet-hero', false)
        ->assertSee('data-banquet-details', false)
        ->assertSee('data-banquet-gallery', false)
        ->assertSee('data-banquet-inquiry', false)
        ->assertSeeText('A refined setting, made personal')
        ->assertSeeText('An atmosphere for every occasion')
        ->assertSeeText('Banquet requests are inquiries only')
        ->assertSeeText('Start a Banquet Inquiry')
        ->assertSeeText('Request a Reservation')
        ->assertSee(route('contact.create'), false)
        ->assertSee(route('reservation-request.create'), false)
        ->assertSee('/storage/gallery/grand-banquet-room.webp', false)
        ->assertSee('Elegant banquet room prepared for dinner');
});

test('banquet hall keeps scope-safe manual review language', function (): void {
    $this->get(route('banquet-hall'))
        ->assertOk()
        ->assertSeeText('manually reviewed')
        ->assertSeeText('before confirming availability')
        ->assertDontSeeText('Book Now')
        ->assertDontSeeText('Confirmed Booking');
});
