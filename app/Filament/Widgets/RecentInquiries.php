<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ContactInquiries\ContactInquiryResource;
use App\Filament\Resources\OrderInquiries\OrderInquiryResource;
use App\Filament\Resources\ReservationRequests\ReservationRequestResource;
use App\Models\ContactInquiry;
use App\Models\OrderInquiry;
use App\Models\ReservationRequest;
use Carbon\CarbonInterface;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Filament\Widgets\Widget;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @phpstan-type RecentInquiry array{
 *     type: 'Reservation Request'|'Order Inquiry'|'Contact Inquiry',
 *     customer_name: string,
 *     summary: string,
 *     is_read: bool,
 *     created_at: CarbonInterface,
 *     url: string
 * }
 */
class RecentInquiries extends Widget
{
    private const DISPLAY_LIMIT = 8;

    protected static ?int $sort = 2;

    protected string $view = 'filament.widgets.recent-inquiries';

    protected int|string|array $columnSpan = [
        'default' => 1,
        'lg' => 8,
    ];

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
     * @return array{
     *     inquiries: Collection<int, RecentInquiry>,
     *     loadError: bool
     * }
     */
    protected function getViewData(): array
    {
        try {
            $inquiries = collect()
                ->concat($this->reservationRequests())
                ->concat($this->orderInquiries())
                ->concat($this->contactInquiries())
                ->sortByDesc(
                    fn (array $inquiry): int => $inquiry['created_at']->getTimestamp(),
                )
                ->take(self::DISPLAY_LIMIT)
                ->values();

            return [
                'inquiries' => $inquiries,
                'loadError' => false,
            ];
        } catch (QueryException $exception) {
            report($exception);

            return [
                'inquiries' => collect(),
                'loadError' => true,
            ];
        }
    }

    /**
     * @return Collection<int, RecentInquiry>
     */
    private function reservationRequests(): Collection
    {
        if (! ReservationRequestResource::canViewAny()) {
            return collect();
        }

        return ReservationRequest::query()
            ->select([
                'id',
                'customer_name',
                'preferred_date',
                'preferred_time',
                'guest_count',
                'is_read',
                'created_at',
            ])
            ->latestFirst()
            ->limit(self::DISPLAY_LIMIT)
            ->get()
            ->map(fn (ReservationRequest $request): array => $this->makeRecentInquiry(
                type: 'Reservation Request',
                customerName: $request->customer_name,
                summary: sprintf(
                    '%d %s · %s at %s',
                    $request->guest_count,
                    Str::plural('guest', $request->guest_count),
                    $request->preferred_date->format('M j'),
                    $request->preferred_time,
                ),
                isRead: $request->is_read,
                createdAt: $request->created_at ?? now(),
                url: ReservationRequestResource::getUrl('view', [
                    'record' => $request,
                ]),
            ));
    }

    /**
     * @return Collection<int, RecentInquiry>
     */
    private function orderInquiries(): Collection
    {
        if (! OrderInquiryResource::canViewAny()) {
            return collect();
        }

        return OrderInquiry::query()
            ->select([
                'id',
                'customer_name',
                'fulfillment_type',
                'preferred_time',
                'is_read',
                'created_at',
            ])
            ->latestFirst()
            ->limit(self::DISPLAY_LIMIT)
            ->get()
            ->map(fn (OrderInquiry $inquiry): array => $this->makeRecentInquiry(
                type: 'Order Inquiry',
                customerName: $inquiry->customer_name,
                summary: sprintf(
                    '%s · %s',
                    Str::headline($inquiry->fulfillment_type),
                    $inquiry->preferred_time,
                ),
                isRead: $inquiry->is_read,
                createdAt: $inquiry->created_at ?? now(),
                url: OrderInquiryResource::getUrl('view', [
                    'record' => $inquiry,
                ]),
            ));
    }

    /**
     * @return Collection<int, RecentInquiry>
     */
    private function contactInquiries(): Collection
    {
        if (! ContactInquiryResource::canViewAny()) {
            return collect();
        }

        return ContactInquiry::query()
            ->select([
                'id',
                'customer_name',
                'subject',
                'is_read',
                'created_at',
            ])
            ->latestFirst()
            ->limit(self::DISPLAY_LIMIT)
            ->get()
            ->map(fn (ContactInquiry $inquiry): array => $this->makeRecentInquiry(
                type: 'Contact Inquiry',
                customerName: $inquiry->customer_name,
                summary: filled($inquiry->subject)
                    ? Str::limit($inquiry->subject, 60)
                    : 'General inquiry',
                isRead: $inquiry->is_read,
                createdAt: $inquiry->created_at ?? now(),
                url: ContactInquiryResource::getUrl('view', [
                    'record' => $inquiry,
                ]),
            ));
    }

    /**
     * @param  'Reservation Request'|'Order Inquiry'|'Contact Inquiry'  $type
     * @return RecentInquiry
     */
    private function makeRecentInquiry(
        string $type,
        string $customerName,
        string $summary,
        bool $isRead,
        CarbonInterface $createdAt,
        string $url,
    ): array {
        return [
            'type' => $type,
            'customer_name' => $customerName,
            'summary' => $summary,
            'is_read' => $isRead,
            'created_at' => $createdAt,
            'url' => $url,
        ];
    }

    private static function canAccessAdminPanel(): bool
    {
        $user = auth()->user();

        return $user instanceof FilamentUser
            && $user->canAccessPanel(Filament::getPanel('admin'));
    }
}
