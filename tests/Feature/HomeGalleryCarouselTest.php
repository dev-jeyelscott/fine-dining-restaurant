<?php

use App\Models\GalleryImage;

test('homepage gallery keeps a server-rendered fallback for progressive enhancement', function (): void {
    GalleryImage::query()->create([
        'title' => 'First gallery moment',
        'alt_text' => 'First gallery moment',
        'image_path' => 'gallery/first-gallery-moment.jpg',
        'category' => 'interior',
        'sort_order' => 1,
        'is_visible' => true,
    ]);
    GalleryImage::query()->create([
        'title' => 'Second gallery moment',
        'alt_text' => 'Second gallery moment',
        'image_path' => 'gallery/second-gallery-moment.jpg',
        'category' => 'dish',
        'sort_order' => 2,
        'is_visible' => true,
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('data-gsap="gallery"', false)
        ->assertSee('data-gsap="tile"', false)
        ->assertSeeText('First gallery moment')
        ->assertSeeText('Second gallery moment');
});

test('homepage carousel initializer keeps accessible interaction and lifecycle contracts', function (): void {
    $carousel = file_get_contents(resource_path('js/home-gallery-carousel.js'));

    expect($carousel)
        ->not->toBeFalse()
        ->toContain('ArrowLeft')
        ->toContain('ArrowRight')
        ->toContain('event.key === "Home"')
        ->toContain('event.key === "End"')
        ->toContain('pointercancel')
        ->toContain('queuedTarget')
        ->toContain('ResizeObserver')
        ->toContain('reducedMotion')
        ->toContain('aria-current')
        ->toContain('aria-hidden')
        ->toContain('setAttribute("inert", "")')
        ->toContain('controls?.remove()')
        ->toContain('gsap.killTweensOf(slides)');
});

test('homepage carousel progressively enhances before generic gallery motion initializes', function (): void {
    $entry = file_get_contents(resource_path('js/app.js'));

    expect($entry)
        ->not->toBeFalse()
        ->toContain('import("./home-gallery-carousel")')
        ->toContain('import("./public-animations")')
        ->toContain('initHomeGalleryCarousel(')
        ->toContain('initPublicAnimations(homeMotionRoot)')
        ->toContain('carouselCleanup()');

    expect(strpos($entry, 'const carouselCleanup = initHomeGalleryCarousel('))
        ->toBeLessThan(strpos($entry, 'const motionCleanup = initPublicAnimations(homeMotionRoot)'));
});

test('homepage carousel styles preserve a responsive stacked landscape composition', function (): void {
    $styles = file_get_contents(resource_path('css/public.css'));

    expect($styles)
        ->not->toBeFalse()
        ->toContain('[data-home-gallery-enhanced] [data-home-gallery-viewport]')
        ->toContain('aspect-ratio: 4 / 3;')
        ->toContain('aspect-ratio: 16 / 10;')
        ->toContain('aspect-ratio: 16 / 9;')
        ->toContain('overflow-x: clip;')
        ->toContain('width: 92%;')
        ->toContain('width: 84%;')
        ->toContain('width: 78%;')
        ->toContain('.home-gallery-controls')
        ->toContain('.home-gallery-button:focus-visible');
});
