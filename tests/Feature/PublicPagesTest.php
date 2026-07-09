<?php

use Illuminate\Support\Facades\Route;

it('registers the phase 2a public page route names', function (): void {
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

it('renders the phase 2a public pages successfully', function (string $routeName): void {
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

it('shows the approved public navigation labels', function (): void {
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

it('uses scope safe reservation and order inquiry wording', function (): void {
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

it('does not expose out of scope ecommerce or live booking ctas on public pages', function (string $routeName): void {
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
