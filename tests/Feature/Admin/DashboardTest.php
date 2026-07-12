<?php

use App\Filament\Resources\GalleryImages\GalleryImageResource;
use App\Filament\Resources\MenuItems\MenuItemResource;
use App\Filament\Resources\Pages\PageResource;
use App\Filament\Resources\SiteSettings\SiteSettingResource;
use App\Filament\Widgets\ContentQuickActions;
use App\Filament\Widgets\InquiryOverview;
use App\Filament\Widgets\RecentInquiries;
use App\Models\ContactInquiry;
use App\Models\OrderInquiry;
use App\Models\ReservationRequest;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

beforeEach(function (): void {
    config()->set('admin.seed_user.email', 'admin@example.test');

    Filament::setCurrentPanel(
        Filament::getPanel('admin'),
    );
});

it('protects dashboard data from users without panel access', function (): void {
    $user = User::factory()->create([
        'email' => 'staff@example.test',
    ]);

    $this->actingAs($user);

    $this->get('/admin')->assertForbidden();

    expect(InquiryOverview::canView())->toBeFalse()
        ->and(RecentInquiries::canView())->toBeFalse()
        ->and(ContentQuickActions::canView())->toBeFalse();
});

it('renders the branded dashboard for the configured admin', function (): void {
    $this->actingAs(dashboardTestAdmin());

    $this->get('/admin')
        ->assertOk()
        ->assertSee('Website overview');
});

it('shows accurate unread inquiry counts', function (): void {
    $this->actingAs(dashboardTestAdmin());

    dashboardTestReservationRequest();
    dashboardTestReservationRequest();
    dashboardTestReservationRequest(['is_read' => true]);

    dashboardTestOrderInquiry();
    dashboardTestOrderInquiry();
    dashboardTestOrderInquiry();

    dashboardTestContactInquiry();
    dashboardTestContactInquiry();
    dashboardTestContactInquiry();
    dashboardTestContactInquiry();

    Livewire::test(InquiryOverview::class)
        ->assertSee('Unread Reservation Requests')
        ->assertSee('2')
        ->assertSee('Unread Order Inquiries')
        ->assertSee('3')
        ->assertSee('Unread Contact Inquiries')
        ->assertSee('4')
        ->assertSee('Awaiting manual review');
});

it('shows recent inquiries without exposing unnecessary customer data', function (): void {
    $this->actingAs(dashboardTestAdmin());

    dashboardTestContactInquiry([
        'customer_name' => 'Maria Santos',
        'email' => 'private-customer@example.test',
        'phone' => '09171234567',
        'subject' => 'Private dining question',
        'message' => 'Sensitive event information should not appear on the dashboard.',
    ]);

    Livewire::test(RecentInquiries::class)
        ->assertSee('Recent inquiries')
        ->assertSee('Maria Santos')
        ->assertSee('Private dining question')
        ->assertSee('New')
        ->assertDontSee('private-customer@example.test')
        ->assertDontSee('09171234567')
        ->assertDontSee('Sensitive event information');
});

it('renders a polished empty inquiry state', function (): void {
    $this->actingAs(dashboardTestAdmin());

    Livewire::test(RecentInquiries::class)
        ->assertSee('No inquiries yet')
        ->assertSee('Reservation Requests')
        ->assertSee('Order Inquiries')
        ->assertSee('Contact Inquiries');
});

it('links quick actions to approved protected resources', function (): void {
    $this->actingAs(dashboardTestAdmin());

    Livewire::test(ContentQuickActions::class)
        ->assertSee('Add menu item')
        ->assertSee('Upload gallery image')
        ->assertSee('Manage page content')
        ->assertSee('Update site settings')
        ->assertSeeHtml(
            'href="'.MenuItemResource::getUrl('create').'"',
        )
        ->assertSeeHtml(
            'href="'.GalleryImageResource::getUrl('create').'"',
        )
        ->assertSeeHtml(
            'href="'.PageResource::getUrl('index').'"',
        )
        ->assertSeeHtml(
            'href="'.SiteSettingResource::getUrl('index').'"',
        );
});

it('loads recent inquiries within a fixed query budget', function (): void {
    $this->actingAs(dashboardTestAdmin());

    dashboardTestReservationRequest();
    dashboardTestOrderInquiry();
    dashboardTestContactInquiry();

    DB::flushQueryLog();
    DB::enableQueryLog();

    Livewire::test(RecentInquiries::class)
        ->assertSee('Recent inquiries');

    $selectQueryCount = collect(DB::getQueryLog())
        ->filter(
            fn (array $query): bool => str_starts_with(
                strtolower(ltrim($query['query'])),
                'select',
            ),
        )
        ->count();

    DB::disableQueryLog();

    expect($selectQueryCount)->toBeLessThanOrEqual(4);
});

function dashboardTestAdmin(): User
{
    return User::factory()->create([
        'email' => 'admin@example.test',
    ]);
}

/**
 * @param  array<string, mixed>  $overrides
 */
function dashboardTestReservationRequest(
    array $overrides = [],
): ReservationRequest {
    return ReservationRequest::query()->create(array_merge([
        'customer_name' => 'Reservation Guest',
        'phone' => '09170000001',
        'email' => 'reservation@example.test',
        'preferred_date' => now()->addDay()->toDateString(),
        'preferred_time' => '7:00 PM',
        'guest_count' => 2,
        'special_requests' => null,
        'is_banquet_or_event' => false,
        'is_read' => false,
    ], $overrides));
}

/**
 * @param  array<string, mixed>  $overrides
 */
function dashboardTestOrderInquiry(
    array $overrides = [],
): OrderInquiry {
    return OrderInquiry::query()->create(array_merge([
        'customer_name' => 'Order Guest',
        'phone' => '09170000002',
        'email' => 'order@example.test',
        'fulfillment_type' => 'pickup',
        'preferred_time' => '6:30 PM',
        'order_details' => 'Two dinner sets',
        'special_instructions' => null,
        'delivery_address' => null,
        'is_read' => false,
    ], $overrides));
}

/**
 * @param  array<string, mixed>  $overrides
 */
function dashboardTestContactInquiry(
    array $overrides = [],
): ContactInquiry {
    return ContactInquiry::query()->create(array_merge([
        'customer_name' => 'Contact Guest',
        'email' => 'contact@example.test',
        'phone' => null,
        'subject' => 'General inquiry',
        'message' => 'Please contact me about the restaurant.',
        'is_read' => false,
    ], $overrides));
}
