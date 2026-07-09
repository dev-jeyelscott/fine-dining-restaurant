<?php

use App\Filament\Resources\ContactInquiries\Pages\ListContactInquiries;
use App\Filament\Resources\GalleryImages\Pages\CreateGalleryImage;
use App\Filament\Resources\MenuCategories\Pages\CreateMenuCategory;
use App\Filament\Resources\MenuItems\Pages\CreateMenuItem;
use App\Filament\Resources\OrderInquiries\Pages\ListOrderInquiries;
use App\Filament\Resources\Pages\PageResource as FilamentPageResource;
use App\Filament\Resources\ReservationRequests\Pages\ListReservationRequests;
use App\Filament\Resources\SiteSettings\SiteSettingResource;
use App\Models\ContactInquiry;
use App\Models\GalleryImage;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\OrderInquiry;
use App\Models\ReservationRequest;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Livewire;

function phase2bAdminUser(): User
{
    config(['admin.seed_user.email' => 'admin@example.com']);

    return User::factory()->create([
        'email' => 'admin@example.com',
    ]);
}

test('admin panel only allows the configured admin user', function () {
    config(['admin.seed_user.email' => 'admin@example.com']);

    $this->get('/admin')->assertRedirect();

    $staffUser = User::factory()->create([
        'email' => 'staff@example.com',
    ]);

    $this->actingAs($staffUser)
        ->get('/admin')
        ->assertForbidden();

    $this->actingAs(User::factory()->create([
        'email' => 'admin@example.com',
    ]))
        ->get('/admin')
        ->assertOk();
});

test('admin can create menu categories and menu items', function () {
    $this->actingAs(phase2bAdminUser());

    Livewire::test(CreateMenuCategory::class)
        ->fillForm([
            'name' => 'Chef Specials',
            'slug' => 'chef-specials',
            'description' => 'Seasonal fine-dining selections.',
            'sort_order' => 1,
            'is_visible' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $category = MenuCategory::query()->where('slug', 'chef-specials')->firstOrFail();

    Livewire::test(CreateMenuItem::class)
        ->fillForm([
            'menu_category_id' => $category->id,
            'name' => 'Truffle Pasta',
            'slug' => 'truffle-pasta',
            'description' => 'House pasta with truffle cream.',
            'price' => '18.50',
            'sort_order' => 1,
            'is_visible' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(MenuItem::class, [
        'menu_category_id' => $category->id,
        'name' => 'Truffle Pasta',
        'slug' => 'truffle-pasta',
        'price' => '18.50',
        'is_visible' => true,
    ]);
});

test('admin can upload safe gallery images', function () {
    Storage::fake('public');

    $this->actingAs(phase2bAdminUser());

    Livewire::test(CreateGalleryImage::class)
        ->fillForm([
            'title' => 'Dining Room',
            'alt_text' => 'Fine-dining restaurant dining room',
            'image_path' => UploadedFile::fake()->image('dining-room.jpg'),
            'category' => 'interior',
            'sort_order' => 1,
            'is_visible' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $galleryImage = GalleryImage::query()->where('title', 'Dining Room')->firstOrFail();

    expect($galleryImage->image_path)->toStartWith('gallery/');

    Storage::disk('public')->assertExists($galleryImage->image_path);
});

test('site settings and page content resources are accessible to admins', function () {
    $this->actingAs(phase2bAdminUser());

    $this->get(SiteSettingResource::getUrl('index'))->assertOk();
    $this->get(FilamentPageResource::getUrl('index'))->assertOk();
});

test('admin can view and mark inquiry records as reviewed without editing customer details', function () {
    $this->actingAs(phase2bAdminUser());

    $reservationRequest = ReservationRequest::query()->create([
        'customer_name' => 'Reservation Guest',
        'phone' => '+63 900 000 0001',
        'email' => 'reservation@example.com',
        'preferred_date' => now()->addDay()->toDateString(),
        'preferred_time' => '7:00 PM',
        'guest_count' => 4,
        'special_requests' => 'Window seat if available.',
        'is_banquet_or_event' => false,
        'is_read' => false,
    ]);

    $orderInquiry = OrderInquiry::query()->create([
        'customer_name' => 'Order Guest',
        'phone' => '+63 900 000 0002',
        'email' => 'order@example.com',
        'fulfillment_type' => 'pickup',
        'preferred_time' => 'Tomorrow afternoon',
        'order_details' => 'Two pasta trays.',
        'special_instructions' => 'Please call before preparing.',
        'is_read' => false,
    ]);

    $contactInquiry = ContactInquiry::query()->create([
        'customer_name' => 'Contact Guest',
        'email' => 'contact@example.com',
        'phone' => '+63 900 000 0003',
        'subject' => 'Private dinner inquiry',
        'message' => 'I would like to ask about a private dinner.',
        'is_read' => false,
    ]);

    Livewire::test(ListReservationRequests::class)
        ->assertCanSeeTableRecords([$reservationRequest])
        ->assertTableActionExists('view')
        ->assertTableActionExists('markAsReviewed')
        ->assertTableActionDoesNotExist('edit')
        ->callTableAction('markAsReviewed', $reservationRequest);

    Livewire::test(ListOrderInquiries::class)
        ->assertCanSeeTableRecords([$orderInquiry])
        ->assertTableActionExists('view')
        ->assertTableActionExists('markAsReviewed')
        ->assertTableActionDoesNotExist('edit')
        ->callTableAction('markAsReviewed', $orderInquiry);

    Livewire::test(ListContactInquiries::class)
        ->assertCanSeeTableRecords([$contactInquiry])
        ->assertTableActionExists('view')
        ->assertTableActionExists('markAsReviewed')
        ->assertTableActionDoesNotExist('edit')
        ->callTableAction('markAsReviewed', $contactInquiry);

    expect($reservationRequest->refresh()->is_read)->toBeTrue();
    expect($orderInquiry->refresh()->is_read)->toBeTrue();
    expect($contactInquiry->refresh()->is_read)->toBeTrue();
});

test('inquiry admin tables do not expose destructive bulk actions', function () {
    $tableFiles = [
        app_path('Filament/Resources/ReservationRequests/Tables/ReservationRequestsTable.php'),
        app_path('Filament/Resources/OrderInquiries/Tables/OrderInquiriesTable.php'),
        app_path('Filament/Resources/ContactInquiries/Tables/ContactInquiriesTable.php'),
    ];

    foreach ($tableFiles as $tableFile) {
        $contents = file_get_contents($tableFile);

        expect($contents)->not->toContain('DeleteBulkAction');
        expect($contents)->toContain('markAsReviewed');
    }
});

test('admin routes remain within approved website cms and inquiry scope', function () {
    $routes = collect(Route::getRoutes())
        ->map(fn ($route): string => trim($route->uri().' '.$route->getName()))
        ->implode("\n");

    $normalizedRoutes = Str::lower($routes);

    foreach ([
        'cart',
        'checkout',
        'payment',
        'inventory',
        'kitchen',
        'order-status',
        'customer-account',
        'customer-login',
    ] as $unsupportedFeature) {
        expect($normalizedRoutes)->not->toContain($unsupportedFeature);
    }
});
