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

test('normal branded accent text meets wcag contrast in light and dark themes', function () {
    $lightAccent = filamentThemeToken('resources/css/brand-tokens.css', '--color-brand-gold-text');
    $darkAccent = filamentThemeToken('resources/css/brand-tokens.css', '--color-brand-gold');

    expect(filamentThemeContrastRatio($lightAccent, '#faf8f2'))
        ->toBeGreaterThanOrEqual(4.5)
        ->and(filamentThemeContrastRatio($lightAccent, '#fffdf9'))
        ->toBeGreaterThanOrEqual(4.5)
        ->and(filamentThemeContrastRatio($darkAccent, '#171916'))
        ->toBeGreaterThanOrEqual(4.5)
        ->and(filamentThemeContrastRatio($darkAccent, '#242720'))
        ->toBeGreaterThanOrEqual(4.5);
});

function filamentThemeToken(string $path, string $token): string
{
    $contents = file_get_contents(base_path($path));

    expect($contents)->not->toBeFalse();

    preg_match('/'.preg_quote($token, '/').':\\s*(#[0-9a-f]{6})/i', $contents, $matches);

    expect($matches)->toHaveKey(1);

    return $matches[1];
}

function filamentThemeContrastRatio(string $foreground, string $background): float
{
    $foregroundLuminance = filamentThemeRelativeLuminance($foreground);
    $backgroundLuminance = filamentThemeRelativeLuminance($background);

    return (max($foregroundLuminance, $backgroundLuminance) + 0.05)
        / (min($foregroundLuminance, $backgroundLuminance) + 0.05);
}

function filamentThemeRelativeLuminance(string $hexColor): float
{
    $channels = array_map(
        function (string $channel): float {
            $value = hexdec($channel) / 255;

            return $value <= 0.04045
                ? $value / 12.92
                : (($value + 0.055) / 1.055) ** 2.4;
        },
        str_split(ltrim($hexColor, '#'), 2),
    );

    return (0.2126 * $channels[0])
        + (0.7152 * $channels[1])
        + (0.0722 * $channels[2]);
}
