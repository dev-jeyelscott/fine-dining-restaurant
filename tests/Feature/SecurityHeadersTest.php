<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('public pages include baseline browser security headers', function (): void {
    $this->get(route('home'))
        ->assertOk()
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
        ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
        ->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
});

test('strict transport security is only added to secure production requests', function (): void {
    $this->get(route('home'))
        ->assertHeaderMissing('Strict-Transport-Security');

    app()->detectEnvironment(fn (): string => 'production');

    $this->withServerVariables(['HTTPS' => 'on'])
        ->get(route('home'))
        ->assertHeader(
            'Strict-Transport-Security',
            'max-age=31536000; includeSubDomains',
        );
});
