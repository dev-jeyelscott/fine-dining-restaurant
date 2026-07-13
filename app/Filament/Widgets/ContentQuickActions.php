<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\GalleryImages\GalleryImageResource;
use App\Filament\Resources\MenuItems\MenuItemResource;
use App\Filament\Resources\Pages\PageResource;
use App\Filament\Resources\SiteSettings\SiteSettingResource;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Filament\Widgets\Widget;

class ContentQuickActions extends Widget
{
    protected static ?int $sort = 3;

    protected string $view = 'filament.widgets.content-quick-actions';

    protected int|string|array $columnSpan = [
        'default' => 1,
        'lg' => 4,
    ];

    public static function canView(): bool
    {
        if (! self::canAccessAdminPanel()) {
            return false;
        }

        return MenuItemResource::canCreate()
            || GalleryImageResource::canCreate()
            || PageResource::canViewAny()
            || SiteSettingResource::canViewAny();
    }

    /**
     * @return array{
     *     quickActions: array<int, array{
     *         label: string,
     *         description: string,
     *         url: string,
     *         icon: string
     *     }>
     * }
     */
    protected function getViewData(): array
    {
        $actions = [];

        if (MenuItemResource::canCreate()) {
            $actions[] = [
                'label' => 'Add menu item',
                'description' => 'Create a new dish, description, and price.',
                'url' => MenuItemResource::getUrl('create'),
                'icon' => 'heroicon-o-plus-circle',
            ];
        }

        if (GalleryImageResource::canCreate()) {
            $actions[] = [
                'label' => 'Upload gallery image',
                'description' => 'Add a restaurant, dish, event, or ambiance photo.',
                'url' => GalleryImageResource::getUrl('create'),
                'icon' => 'heroicon-o-photo',
            ];
        }

        if (PageResource::canViewAny()) {
            $actions[] = [
                'label' => 'Manage page content',
                'description' => 'Review approved public-page content and metadata.',
                'url' => PageResource::getUrl('index'),
                'icon' => 'heroicon-o-document-text',
            ];
        }

        if (SiteSettingResource::canViewAny()) {
            $actions[] = [
                'label' => 'Update site settings',
                'description' => 'Manage contact details, social links, and site values.',
                'url' => SiteSettingResource::getUrl('index'),
                'icon' => 'heroicon-o-cog-6-tooth',
            ];
        }

        return [
            'quickActions' => $actions,
        ];
    }

    private static function canAccessAdminPanel(): bool
    {
        $user = auth()->user();

        return $user instanceof FilamentUser
            && $user->canAccessPanel(Filament::getPanel('admin'));
    }
}
