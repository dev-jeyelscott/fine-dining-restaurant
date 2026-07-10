<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('major public pages render canonical and Open Graph metadata', function (string $routeName): void {
    $url = route($routeName);

    $this->get($url)
        ->assertOk()
        ->assertSee('<meta name="description"', false)
        ->assertSee('<link rel="canonical" href="'.$url.'">', false)
        ->assertSee('<meta property="og:type" content="website">', false)
        ->assertSee('<meta property="og:title"', false)
        ->assertSee('<meta property="og:description"', false)
        ->assertSee('<meta property="og:url" content="'.$url.'">', false);
})->with([
    'home' => 'home',
    'menu' => 'menu',
    'reservation request' => 'reservation-request.create',
    'order inquiry' => 'order-inquiry.create',
    'gallery' => 'gallery',
    'banquet hall' => 'banquet-hall',
    'contact' => 'contact.create',
]);
