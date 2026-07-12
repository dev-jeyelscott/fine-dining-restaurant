<?php

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

test('homepage carousel is initialized through the shared public animation runtime', function (): void {
    $entry = file_get_contents(resource_path('js/app.js'));

    expect($entry)
        ->not->toBeFalse()
        ->toContain('import("./public-animations")')
        ->toContain('const motionCleanup = initPublicAnimations(homeMotionRoot)')
        ->not->toContain('from "gsap"');
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
        ->toContain('touch-action: pan-y;')
        ->toContain('width: 92%;')
        ->toContain('width: 84%;')
        ->toContain('width: 78%;')
        ->toContain('.home-gallery-controls')
        ->toContain('.home-gallery-button:focus-visible');
});
