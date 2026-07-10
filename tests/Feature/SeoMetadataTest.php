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

test('robots file allows public crawling and protects the admin path', function (): void {
    $this->get(route('robots'))
        ->assertOk()
        ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
        ->assertSeeText('User-agent: *')
        ->assertSeeText('Allow: /')
        ->assertSeeText('Disallow: /admin')
        ->assertSeeText('Sitemap: '.route('sitemap'));
});

test('sitemap contains each major public page and excludes admin routes', function (): void {
    $response = $this->get(route('sitemap'))
        ->assertOk()
        ->assertHeader('Content-Type', 'application/xml; charset=UTF-8');

    foreach ([
        'home',
        'menu',
        'reservation-request.create',
        'order-inquiry.create',
        'gallery',
        'banquet-hall',
        'contact.create',
    ] as $routeName) {
        $response->assertSee(e(route($routeName)), false);
    }

    $response->assertDontSee('/admin');
});
