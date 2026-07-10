<?php

use App\Models\SiteSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

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
        ->assertSeeText('not checkout or online payment')
        ->assertSeeText('manually confirm availability');
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
