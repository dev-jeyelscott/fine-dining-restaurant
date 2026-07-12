<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName((string) config('app.name'))
            ->brandLogo(
                fn (): View => view('filament.admin.brand-logo'),
            )
            ->brandLogoHeight('3rem')
            ->font('Instrument Sans')
            ->colors([
                'primary' => '#c9a45d',
                'gray' => Color::Stone,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->simplePageMaxContentWidth(Width::Small)
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('18rem')
            ->collapsedSidebarWidth('5rem')
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Website Content')
                    ->collapsible(false),

                NavigationGroup::make()
                    ->label('Menu Management')
                    ->collapsible(false),

                NavigationGroup::make()
                    ->label('Media')
                    ->collapsible(false),

                NavigationGroup::make()
                    ->label('Customer Inquiries')
                    ->collapsible(false),
            ])
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
                fn (): View => view('filament.admin.auth.login-intro'),
            )
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
                fn (): View => view('filament.admin.auth.login-footer'),
            )
            ->discoverResources(
                in: app_path('Filament/Resources'),
                for: 'App\Filament\Resources',
            )
            ->discoverPages(
                in: app_path('Filament/Pages'),
                for: 'App\Filament\Pages',
            )
            ->pages([])
            ->discoverWidgets(
                in: app_path('Filament/Widgets'),
                for: 'App\Filament\Widgets',
            )
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
