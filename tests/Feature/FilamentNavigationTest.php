<?php

use App\Filament\Pages\Dashboard;
use App\Filament\Resources\ContactInquiries\ContactInquiryResource;
use App\Filament\Resources\GalleryImages\GalleryImageResource;
use App\Filament\Resources\MenuCategories\MenuCategoryResource;
use App\Filament\Resources\MenuItems\MenuItemResource;
use App\Filament\Resources\OrderInquiries\OrderInquiryResource;
use App\Filament\Resources\Pages\PageResource;
use App\Filament\Resources\ReservationRequests\ReservationRequestResource;
use App\Filament\Resources\SiteSettings\SiteSettingResource;
use App\Models\User;

function filamentNavigationAdminUser(): User
{
    config(['admin.seed_user.email' => 'admin@example.com']);

    return User::factory()->create([
        'email' => 'admin@example.com',
    ]);
}

dataset('filament navigation hierarchy', [
    'pages' => [
        PageResource::class,
        'Website Content',
        'Pages',
        10,
    ],
    'site settings' => [
        SiteSettingResource::class,
        'Website Content',
        'Site Settings',
        20,
    ],
    'menu categories' => [
        MenuCategoryResource::class,
        'Menu Management',
        'Menu Categories',
        10,
    ],
    'menu items' => [
        MenuItemResource::class,
        'Menu Management',
        'Menu Items',
        20,
    ],
    'gallery images' => [
        GalleryImageResource::class,
        'Media',
        'Gallery Images',
        10,
    ],
    'reservation requests' => [
        ReservationRequestResource::class,
        'Customer Inquiries',
        'Reservation Requests',
        10,
    ],
    'order inquiries' => [
        OrderInquiryResource::class,
        'Customer Inquiries',
        'Order Inquiries',
        20,
    ],
    'contact inquiries' => [
        ContactInquiryResource::class,
        'Customer Inquiries',
        'Contact Inquiries',
        30,
    ],
]);

test(
    'filament resources use the approved navigation hierarchy',
    function (
        string $resource,
        string $expectedGroup,
        string $expectedLabel,
        int $expectedSort,
    ) {
        expect($resource::getNavigationGroup())
            ->toBe($expectedGroup)
            ->and($resource::getNavigationLabel())
            ->toBe($expectedLabel)
            ->and($resource::getNavigationSort())
            ->toBe($expectedSort)
            ->and($resource::getNavigationIcon())
            ->not->toBeNull();
    },
)->with('filament navigation hierarchy');

test('dashboard is presented as overview', function () {
    expect(Dashboard::getNavigationLabel())
        ->toBe('Website overview')
        ->and(Dashboard::getNavigationIcon())
        ->not->toBeNull();
});

test('authorized admin can reach every navigation destination', function () {
    $this->actingAs(filamentNavigationAdminUser());

    foreach ([
        Dashboard::getUrl(),
        PageResource::getUrl('index'),
        SiteSettingResource::getUrl('index'),
        MenuCategoryResource::getUrl('index'),
        MenuItemResource::getUrl('index'),
        GalleryImageResource::getUrl('index'),
        ReservationRequestResource::getUrl('index'),
        OrderInquiryResource::getUrl('index'),
        ContactInquiryResource::getUrl('index'),
    ] as $url) {
        $this->get($url)->assertOk();
    }
});

test('unauthorized users remain blocked from navigation destinations', function () {
    config(['admin.seed_user.email' => 'admin@example.com']);

    $staffUser = User::factory()->create([
        'email' => 'staff@example.com',
    ]);

    $this->actingAs($staffUser);

    foreach ([
        Dashboard::getUrl(),
        PageResource::getUrl('index'),
        SiteSettingResource::getUrl('index'),
        MenuCategoryResource::getUrl('index'),
        MenuItemResource::getUrl('index'),
        GalleryImageResource::getUrl('index'),
        ReservationRequestResource::getUrl('index'),
        OrderInquiryResource::getUrl('index'),
        ContactInquiryResource::getUrl('index'),
    ] as $url) {
        $this->get($url)->assertForbidden();
    }
});
