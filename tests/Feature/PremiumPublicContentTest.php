<?php

$publicPageFiles = [
    'home.blade.php',
    'menu.blade.php',
    'reservation-request.blade.php',
    'order-inquiry.blade.php',
    'gallery.blade.php',
    'banquet-hall.blade.php',
    'contact.blade.php',
];

test('premium public content seed data stays aligned with the approved brand voice', function (): void {
    $pageSeeder = file_get_contents(database_path('seeders/PageSeeder.php'));
    $premiumSeeder = file_get_contents(database_path('seeders/PremiumPublicContentSeeder.php'));
    $databaseSeeder = file_get_contents(database_path('seeders/DatabaseSeeder.php'));

    expect($pageSeeder)
        ->toBeString()
        ->toContain('A Dining Experience, Beautifully Composed')
        ->toContain('A Menu Guided by Season and Craft')
        ->toContain('Plan Your Evening at Le Jardin')
        ->toContain('Bring Le Jardin to Your Table')
        ->toContain('A Portrait of Le Jardin')
        ->toContain('A Setting for Meaningful Occasions')
        ->toContain('Begin a Conversation with Le Jardin')
        ->not->toContain('Welcome to Le Jardin')
        ->not->toContain('Submit your preferred dining date and time for manual restaurant review.');

    expect($premiumSeeder)
        ->toBeString()
        ->toContain('Delicate beginnings designed to awaken the palate.')
        ->toContain('Marbled Wagyu striploin seared to a deep crust')
        ->toContain('The Dining Room')
        ->toContain('A Private Occasion');

    expect($databaseSeeder)
        ->toBeString()
        ->toContain('MenuSeeder::class')
        ->toContain('GalleryImageSeeder::class')
        ->toContain('PremiumPublicContentSeeder::class');
});

test('public Blade fallbacks do not expose implementation language', function () use ($publicPageFiles): void {
    foreach ($publicPageFiles as $file) {
        $source = strtolower((string) file_get_contents(resource_path('views/pages/'.$file)));

        expect($source, $file)
            ->not->toContain('filament')
            ->not->toContain('admin panel')
            ->not->toContain('database record')
            ->not->toContain('visible records')
            ->not->toContain('shopping cart');
    }
});

test('copy changes preserve the validated Menu and Order Inquiry GSAP runtime', function (): void {
    $source = file_get_contents(resource_path('js/public-animations.js'));

    expect($source)
        ->toBeString()
        ->toContain('if (root.dataset.publicMotion === "menu")')
        ->toContain('return initializeMenuMotion(root, { reducedMotion });')
        ->toContain('if (root.matches("[data-order-inquiry-motion]"))')
        ->toContain('return initializeOrderInquiryMotion(root, { desktop, reducedMotion });')
        ->toContain('gsap.set(courseRule, { autoAlpha: 1, scaleX: 0')
        ->toContain('{ animate: false }')
        ->toContain('gsap.killTweensOf(deliveryPanel);');
});
