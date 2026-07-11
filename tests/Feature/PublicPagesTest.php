<?php

use App\Models\GalleryImage;
use App\Models\SiteSetting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

function storePublicPageTestImage(string $filename): string
{
    $path = UploadedFile::fake()
        ->image($filename, 2400, 1600)
        ->storeAs('gallery', $filename, 'public');

    expect($path)->toBeString();

    return $path;
}

test('approved public page routes are registered with stable paths', function (): void {
    $expectedRoutes = [
        'home' => '/',
        'menu' => '/menu',
        'gallery' => '/gallery',
        'banquet-hall' => '/banquet-hall',
        'reservation-request.create' => '/reservation-request',
        'order-inquiry.create' => '/order-inquiry',
        'contact.create' => '/contact',
    ];

    foreach ($expectedRoutes as $name => $path) {
        expect(Route::has($name))->toBeTrue("Expected route [{$name}] to be registered.");
        expect(route($name, [], false))->toBe($path);
    }
});

test('public pages use an isolated Vite stylesheet without Flux sources', function (): void {
    $publicLayout = File::get(resource_path('views/components/layouts/public.blade.php'));
    $publicStyles = File::get(resource_path('css/public.css'));
    $appStyles = File::get(resource_path('css/app.css'));
    $viteConfig = File::get(base_path('vite.config.js'));

    expect($publicLayout)
        ->toContain('resources/css/public.css');
    expect(str_contains($publicLayout, 'resources/css/app.css'))->toBeFalse();

    expect($publicStyles)
        ->toContain('@import "tailwindcss" source(none);')
        ->toContain("@source '../views/pages/**/*.blade.php';")
        ->toContain("@source '../views/components/public/**/*.blade.php';")
        ->toContain("@source '../views/components/layouts/public.blade.php';")
        ->toContain("@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';");
    expect(str_contains($publicStyles, 'livewire/flux'))->toBeFalse();
    expect(str_contains($publicStyles, 'flux-pro'))->toBeFalse();

    expect($appStyles)
        ->toContain('../../vendor/livewire/flux/dist/flux.css')
        ->toContain('../../vendor/livewire/flux/stubs/**/*.blade.php');
    expect(str_contains($appStyles, 'flux-pro'))->toBeFalse();

    expect($viteConfig)
        ->toContain("'resources/css/app.css'")
        ->toContain("'resources/css/public.css'");
});

test('approved public pages render successfully', function (string $routeName): void {
    $this->get(route($routeName))->assertOk();
})->with([
    'home' => 'home',
    'menu' => 'menu',
    'gallery' => 'gallery',
    'banquet hall' => 'banquet-hall',
    'reservation request' => 'reservation-request.create',
    'order inquiry' => 'order-inquiry.create',
    'contact' => 'contact.create',
]);

test('all public pages share the homepage navigation and hero contract', function (string $routeName): void {
    $this->get(route($routeName))
        ->assertOk()
        ->assertSee('class="fixed inset-x-0 top-0 z-50', false)
        ->assertSee('data-public-hero', false)
        ->assertSee('min-h-[44rem] items-center', false)
        ->assertSee('lg:min-h-screen', false);
})->with([
    'home' => 'home',
    'menu' => 'menu',
    'gallery' => 'gallery',
    'banquet hall' => 'banquet-hall',
    'reservation request' => 'reservation-request.create',
    'order inquiry' => 'order-inquiry.create',
    'contact' => 'contact.create',
]);

test('representative public pages execute one site settings query on a cold cache', function (string $routeName): void {
    foreach ([
        'restaurant_name' => 'Shared Query Bistro',
        'phone' => '+63 912 345 6789',
        'email' => 'hello@example.com',
        'address' => '123 Dining Avenue',
    ] as $key => $value) {
        SiteSetting::query()->create([
            'key' => $key,
            'value' => $value,
            'group' => 'contact',
        ]);
    }

    SiteSetting::forgetCachedValues();
    DB::flushQueryLog();
    DB::enableQueryLog();

    $this->get(route($routeName))
        ->assertOk()
        ->assertSeeText('Shared Query Bistro');

    $siteSettingQueries = collect(DB::getQueryLog())
        ->filter(fn (array $query): bool => str_contains(strtolower($query['query']), 'site_settings'));

    DB::disableQueryLog();

    expect($siteSettingQueries)->toHaveCount(1);
})->with([
    'home' => 'home',
    'menu' => 'menu',
    'contact' => 'contact.create',
]);

test('site setting updates and deletes invalidate cached public values', function (): void {
    $setting = SiteSetting::query()->create([
        'key' => 'restaurant_name',
        'value' => 'Original Restaurant Name',
        'group' => 'general',
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSeeText('Original Restaurant Name');

    $setting->update(['value' => 'Updated Restaurant Name']);

    $this->get(route('menu'))
        ->assertOk()
        ->assertSeeText('Updated Restaurant Name')
        ->assertDontSeeText('Original Restaurant Name');

    $setting->delete();

    $this->get(route('contact.create'))
        ->assertOk()
        ->assertDontSeeText('Updated Restaurant Name');

    expect(SiteSetting::value('restaurant_name', 'Fallback Restaurant Name'))
        ->toBe('Fallback Restaurant Name');
});

test('gallery page opts into the shared public motion runtime', function (): void {
    $this->get(route('gallery'))
        ->assertOk()
        ->assertSee('data-home-motion', false)
        ->assertSee('data-gallery-motion', false)
        ->assertSee('data-gsap="hero-content"', false)
        ->assertSee('data-gsap="gallery"', false);
});

test('shared public settings keep malformed public links out of rendered pages', function (): void {
    SiteSetting::query()->create([
        'key' => 'map_link',
        'value' => 'javascript:alert(1)',
        'group' => 'contact',
    ]);

    $this->get(route('contact.create'))
        ->assertOk()
        ->assertDontSee('javascript:alert(1)', false)
        ->assertDontSeeText('Open location map');
});

test('public navigation uses the approved workflow labels', function (): void {
    $this->get(route('home'))
        ->assertOk()
        ->assertSeeText('Home')
        ->assertSeeText('Menu')
        ->assertSeeText('Reservation Request')
        ->assertSeeText('Order Inquiry')
        ->assertSeeText('Gallery')
        ->assertSeeText('Banquet Hall')
        ->assertSeeText('Contact');
});

test('reservation and order pages explain manual review boundaries', function (): void {
    $this->get(route('reservation-request.create'))
        ->assertOk()
        ->assertSeeText('Reservation Request')
        ->assertSeeText('not a confirmed reservation')
        ->assertSeeText('manually review your request');

    $this->get(route('order-inquiry.create'))
        ->assertOk()
        ->assertSeeText('Order Inquiry')
        ->assertSeeText('rather than checkout')
        ->assertSeeText('personally confirm availability')
        ->assertSee('data-home-motion', false)
        ->assertSee('data-order-inquiry-motion', false)
        ->assertSee('data-gsap="fulfillment-cards"', false)
        ->assertSee('order-inquiry:fulfillment-change', false)
        ->assertSee('x-bind:disabled="fulfillmentType !== \'delivery\'"', false)
        ->assertSee('x-bind:aria-hidden="fulfillmentType !== \'delivery\'"', false);
});

test('contact page uses the reservation request design language while preserving inquiry actions', function (): void {
    $this->get(route('contact.create'))
        ->assertOk()
        ->assertSee('data-contact-hero', false)
        ->assertSee('data-contact-hero-fallback', false)
        ->assertSee('data-home-motion', false)
        ->assertSee('data-contact-motion', false)
        ->assertSee('data-gsap="hero-content"', false)
        ->assertSee('data-gsap="section"', false)
        ->assertSee('data-gsap="panel"', false)
        ->assertSee('data-gsap-reveal', false)
        ->assertDontSee('/storage/gallery/', false)
        ->assertSee('data-contact-form', false)
        ->assertSeeText('A warm response, thoughtfully given')
        ->assertSeeText('Send Inquiry')
        ->assertSee(route('contact-inquiries.store'), false)
        ->assertSee(route('reservation-request.create'), false)
        ->assertSee(route('order-inquiry.create'), false);
});

test('contact hero uses managed responsive image derivatives', function (): void {
    Storage::fake('public');

    $imagePath = UploadedFile::fake()
        ->image('contact-dining-room.jpg', 2400, 1600)
        ->storeAs('gallery', 'contact-dining-room.jpg', 'public');

    expect($imagePath)->toBeString();

    GalleryImage::query()->create([
        'title' => 'Contact Dining Room',
        'alt_text' => 'Elegant dining room prepared for evening service',
        'image_path' => $imagePath,
        'category' => 'interior',
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    $this->get(route('contact.create'))
        ->assertOk()
        ->assertDontSee('data-contact-hero-fallback', false)
        ->assertSee('/storage/gallery/variants/contact-dining-room-hero.jpg', false)
        ->assertSee('srcset=', false)
        ->assertSee('sizes="100vw"', false)
        ->assertSee('loading="eager"', false)
        ->assertSee('fetchpriority="high"', false)
        ->assertSee('Elegant dining room prepared for evening service');
});

test('contact hero renders its fallback for a stale managed image path', function (): void {
    Storage::fake('public');

    $missingPath = 'gallery/missing-contact-hero.jpg';

    GalleryImage::query()->create([
        'title' => 'Missing Contact Hero',
        'alt_text' => 'Missing contact hero image',
        'image_path' => $missingPath,
        'category' => 'interior',
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    $this->get(route('contact.create'))
        ->assertOk()
        ->assertSee('data-contact-hero-fallback', false)
        ->assertDontSee(Storage::disk('public')->url($missingPath), false)
        ->assertDontSee('Missing contact hero image', false);
});

test('contact hero prefers an interior image beyond the first six ordered records', function (): void {
    Storage::fake('public');

    foreach (range(1, 6) as $position) {
        GalleryImage::query()->create([
            'title' => "Earlier non-interior image {$position}",
            'alt_text' => "Earlier non-interior alt {$position}",
            'image_path' => "gallery/non-interior-{$position}.jpg",
            'category' => 'dish',
            'sort_order' => $position,
            'is_visible' => true,
        ]);
    }

    GalleryImage::query()->create([
        'title' => 'Preferred later interior image',
        'alt_text' => 'Preferred later interior alt',
        'image_path' => storePublicPageTestImage('preferred-later-interior.jpg'),
        'category' => 'interior',
        'sort_order' => 7,
        'is_visible' => true,
    ]);

    $this->get(route('contact.create'))
        ->assertOk()
        ->assertSee('Preferred later interior alt', false)
        ->assertDontSee('Earlier non-interior alt 1', false);
});

test('contact hero falls back to the first visible ordered image when no interior image exists', function (): void {
    Storage::fake('public');

    GalleryImage::query()->create([
        'title' => 'Second fallback image',
        'alt_text' => 'Second fallback alt',
        'image_path' => 'gallery/second-fallback.jpg',
        'category' => 'event',
        'sort_order' => 2,
        'is_visible' => true,
    ]);

    GalleryImage::query()->create([
        'title' => 'First fallback image',
        'alt_text' => 'First fallback alt',
        'image_path' => storePublicPageTestImage('first-fallback.jpg'),
        'category' => 'dish',
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    $this->get(route('contact.create'))
        ->assertOk()
        ->assertSee('First fallback alt', false)
        ->assertDontSee('Second fallback alt', false);
});

test('public pages do not expose out of scope ecommerce or live booking calls to action', function (string $routeName): void {
    $response = $this->get(route($routeName))->assertOk();

    foreach ([
        'Book Now',
        'Order Now',
        'Pay Online',
        'Track Order',
        'Confirmed Booking',
        'Add to Cart',
    ] as $outOfScopeLabel) {
        $response->assertDontSeeText($outOfScopeLabel);
    }
})->with([
    'home' => 'home',
    'menu' => 'menu',
    'gallery' => 'gallery',
    'banquet hall' => 'banquet-hall',
    'reservation request' => 'reservation-request.create',
    'order inquiry' => 'order-inquiry.create',
    'contact' => 'contact.create',
]);
