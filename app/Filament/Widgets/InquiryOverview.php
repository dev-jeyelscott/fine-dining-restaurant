<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ContactInquiries\ContactInquiryResource;
use App\Filament\Resources\OrderInquiries\OrderInquiryResource;
use App\Filament\Resources\ReservationRequests\ReservationRequestResource;
use App\Models\ContactInquiry;
use App\Models\OrderInquiry;
use App\Models\ReservationRequest;
use Closure;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\QueryException;

class InquiryOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    /**
     * These counts are not operational real-time data.
     * Disabling polling avoids three count queries every five seconds.
     */
    protected ?string $pollingInterval = null;

    protected ?string $heading = 'Inquiry overview';

    protected ?string $description = 'New customer requests awaiting manual restaurant review.';

    protected function getColumns(): int|array|null
    {
        return [
            'default' => 1,
            'md' => 3,
        ];
    }

    public static function canView(): bool
    {
        if (! self::canAccessAdminPanel()) {
            return false;
        }

        return ReservationRequestResource::canViewAny()
            || OrderInquiryResource::canViewAny()
            || ContactInquiryResource::canViewAny();
    }

    /**
     * @return array<Stat>
     */
    protected function getStats(): array
    {
        $stats = [];

        if (ReservationRequestResource::canViewAny()) {
            $stats[] = $this->makeInquiryStat(
                label: 'Unread Reservation Requests',
                count: $this->runCountQuery(
                    fn (): int => ReservationRequest::query()
                        ->unread()
                        ->count('*'),
                ),
                icon: 'heroicon-m-calendar-days',
            );
        }

        if (OrderInquiryResource::canViewAny()) {
            $stats[] = $this->makeInquiryStat(
                label: 'Unread Order Inquiries',
                count: $this->runCountQuery(
                    fn (): int => OrderInquiry::query()
                        ->unread()
                        ->count('*'),
                ),
                icon: 'heroicon-m-shopping-bag',
            );
        }

        if (ContactInquiryResource::canViewAny()) {
            $stats[] = $this->makeInquiryStat(
                label: 'Unread Contact Inquiries',
                count: $this->runCountQuery(
                    fn (): int => ContactInquiry::query()
                        ->unread()
                        ->count('*'),
                ),
                icon: 'heroicon-m-chat-bubble-left-right',
            );
        }

        return $stats;
    }

    private function makeInquiryStat(
        string $label,
        ?int $count,
        string $icon,
    ): Stat {
        $isUnavailable = $count === null;
        $hasUnreadRecords = is_int($count) && $count > 0;

        return Stat::make($label, $count ?? '—')
            ->description(match (true) {
                $isUnavailable => 'Temporarily unavailable',
                $hasUnreadRecords => 'Awaiting manual review',
                default => 'No new inquiries',
            })
            ->descriptionIcon(
                $isUnavailable
                    ? 'heroicon-m-exclamation-triangle'
                    : $icon,
            )
            ->color(match (true) {
                $isUnavailable => 'danger',
                $hasUnreadRecords => 'warning',
                default => 'gray',
            })
            ->extraAttributes([
                'class' => 'admin-dashboard-stat',
            ]);
    }

    private function runCountQuery(Closure $query): ?int
    {
        try {
            return $query();
        } catch (QueryException $exception) {
            report($exception);

            return null;
        }
    }

    private static function canAccessAdminPanel(): bool
    {
        $user = auth()->user();

        return $user instanceof FilamentUser
            && $user->canAccessPanel(Filament::getPanel('admin'));
    }
}
