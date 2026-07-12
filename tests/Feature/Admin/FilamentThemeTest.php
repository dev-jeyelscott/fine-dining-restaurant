<?php

use Filament\Facades\Filament;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\View\View;

test('it registers the dedicated branded admin theme', function () {
    $panel = Filament::getPanel('admin');

    expect($panel->getId())
        ->toBe('admin')
        ->and($panel->getPath())
        ->toBe('admin')
        ->and($panel->getViteTheme())
        ->toBe('resources/css/filament/admin/theme.css')
        ->and($panel->getFontFamily())
        ->toBe('Instrument Sans')
        ->and($panel->getBrandName())
        ->toBe((string) config('app.name'))
        ->and($panel->getBrandLogo())
        ->toBeInstanceOf(View::class)
        ->and($panel->getBrandLogoHeight())
        ->toBe('3rem')
        ->and($panel->getSimplePageMaxContentWidth())
        ->toBe(Width::Small)
        ->and(file_exists(resource_path('css/filament/admin/theme.css')))
        ->toBeTrue()
        ->and(file_exists(resource_path('css/brand-tokens.css')))
        ->toBeTrue()
        ->and(file_exists(resource_path('views/filament/admin/brand-logo.blade.php')))
        ->toBeTrue()
        ->and(file_exists(resource_path('views/filament/admin/auth/login-intro.blade.php')))
        ->toBeTrue()
        ->and(file_exists(resource_path('views/filament/admin/auth/login-footer.blade.php')))
        ->toBeTrue();
});
