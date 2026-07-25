<?php

use App\Models\GalleryImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

function storeBanquetHallTestImage(string $filename): string
{
    $path = UploadedFile::fake()
        ->image($filename, 2400, 1600)
        ->storeAs('gallery', $filename, 'public');

    expect($path)->toBeString();

    return $path;
}

test('banquet hall follows the luxury homepage design language and preserves inquiry actions', function (): void {
    Storage::fake('public');

    GalleryImage::query()->create([
        'title' => 'Grand Banquet Room',
        'alt_text' => 'Elegant banquet room prepared for dinner',
        'image_path' => storeBanquetHallTestImage('grand-banquet-room.jpg'),
        'category' => 'banquet',
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    GalleryImage::query()->create([
        'title' => 'Intimate Celebration',
        'alt_text' => 'A banquet table set for an intimate celebration',
        'image_path' => storeBanquetHallTestImage('intimate-celebration.jpg'),
        'category' => 'banquet',
        'sort_order' => 2,
        'is_visible' => true,
    ]);

    $this->get(route('banquet-hall'))
        ->assertOk()
        ->assertSee('data-home-motion', false)
        ->assertSee('data-banquet-hero', false)
        ->assertSee('data-gsap="hero-image"', false)
        ->assertSee('data-gsap="hero-content"', false)
        ->assertSee('data-banquet-details', false)
        ->assertSee('data-gsap="section"', false)
        ->assertSee('data-gsap="image"', false)
        ->assertSee('data-gsap="frame"', false)
        ->assertSee('data-banquet-gallery', false)
        ->assertSee('data-gsap="gallery"', false)
        ->assertSee('data-banquet-inquiry', false)
        ->assertSee('data-gsap="parallax"', false)
        ->assertSeeText('A refined setting, made personal')
        ->assertSeeText('An atmosphere for every occasion')
        ->assertSeeText('Banquet requests are inquiries only')
        ->assertSeeText('Start a Banquet Inquiry')
        ->assertSeeText('Request a Reservation')
        ->assertSee(route('contact.create').'#contact-inquiry', false)
        ->assertSee(route('reservation-request.create'), false)
        ->assertSee('/storage/gallery/variants/grand-banquet-room-hero.jpg', false)
        ->assertSee('Elegant banquet room prepared for dinner');
});

test('banquet inquiry contact CTA lands on the approved inquiry form', function (): void {
    $this->get(route('banquet-hall'))
        ->assertOk()
        ->assertSee('href="'.route('contact.create').'#contact-inquiry"', false)
        ->assertSeeText('Banquet requests are inquiries only');

    $this->get(route('contact.create'))
        ->assertOk()
        ->assertSee('id="contact-inquiry"', false)
        ->assertSee('scroll-mt-20', false)
        ->assertSeeText('private-event detail');
});

test('banquet hall keeps scope-safe manual review language', function (): void {
    $this->get(route('banquet-hall'))
        ->assertOk()
        ->assertSeeText('manually reviewed')
        ->assertSeeText('before confirming availability')
        ->assertDontSeeText('Book Now')
        ->assertDontSeeText('Confirmed Booking');
});
