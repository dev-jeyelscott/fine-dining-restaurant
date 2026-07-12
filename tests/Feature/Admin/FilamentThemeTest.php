<?php

use Filament\Facades\Filament;

it('registers the dedicated branded admin theme', function () {
    $panel = Filament::getPanel('admin');

    expect($panel->getId())
        ->toBe('admin')
        ->and($panel->getPath())
        ->toBe('admin')
        ->and($panel->getViteTheme())
        ->toBe('resources/css/filament/admin/theme.css')
        ->and($panel->getFontFamily())
        ->toBe('Instrument Sans')
        ->and(file_exists(resource_path('css/filament/admin/theme.css')))
        ->toBeTrue()
        ->and(file_exists(resource_path('css/brand-tokens.css')))
        ->toBeTrue();
});
