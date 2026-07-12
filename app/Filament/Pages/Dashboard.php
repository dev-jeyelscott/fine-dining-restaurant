<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ContentQuickActions;
use App\Filament\Widgets\InquiryOverview;
use App\Filament\Widgets\RecentInquiries;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Website overview';

    /**
     * @return int|array<string, int>
     */
    public function getColumns(): int|array
    {
        return [
            'default' => 1,
            'lg' => 12,
        ];
    }

    /**
     * @return array<class-string>
     */
    public function getWidgets(): array
    {
        return [
            InquiryOverview::class,
            RecentInquiries::class,
            ContentQuickActions::class,
        ];
    }
}
