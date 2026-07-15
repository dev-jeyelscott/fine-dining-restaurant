<?php

test('homepage gallery carousel preserves server rendered markup across motion lifecycle rebuilds', function (): void {
    $carouselModule = (string) file_get_contents(resource_path('js/home-gallery-carousel.js'));
    $carouselComponent = (string) file_get_contents(resource_path('views/components/public/home-gallery-carousel.blade.php'));

    expect($carouselComponent)
        ->toContain('data-home-gallery-controls')
        ->toContain('data-home-gallery-slide')
        ->toContain('data-home-gallery-viewport');

    expect($carouselModule)
        ->toContain('const rootAttributes = captureAttributes')
        ->toContain('const viewportAttributes = captureAttributes')
        ->toContain('const slideAttributes = slides.map')
        ->toContain('const imageAttributes = images.map')
        ->toContain('restoreAttributes(slide, slideAttributes[index])')
        ->toContain('restoreAttributes(image, imageAttributes[index])')
        ->toContain('restoreAttributes(viewport, viewportAttributes)')
        ->toContain('restoreAttributes(root, rootAttributes)')
        ->not->toContain('controls?.remove()');
});
