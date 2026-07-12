<?php

use App\Filament\Resources\ContactInquiries\Tables\ContactInquiriesTable;
use App\Filament\Resources\GalleryImages\Tables\GalleryImagesTable;
use App\Filament\Resources\MenuCategories\Tables\MenuCategoriesTable;
use App\Filament\Resources\MenuItems\Tables\MenuItemsTable;
use App\Filament\Resources\OrderInquiries\Tables\OrderInquiriesTable;
use App\Filament\Resources\Pages\Tables\PagesTable;
use App\Filament\Resources\ReservationRequests\Tables\ReservationRequestsTable;
use App\Filament\Resources\SiteSettings\Tables\SiteSettingsTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

/**
 * @param  class-string  $tableDefinition
 */
function brandedResourceTable(string $tableDefinition): Table
{
    return $tableDefinition::configure(Table::make(Mockery::mock(HasTable::class)));
}

test('resource tables provide clear branded empty states', function (string $tableDefinition, string $heading) {
    $table = brandedResourceTable($tableDefinition);

    expect($table->getEmptyStateHeading())
        ->toBe($heading)
        ->and($table->getEmptyStateDescription())
        ->not->toBeEmpty()
        ->and($table->getEmptyStateIcon())
        ->not->toBeEmpty();
})->with([
    'reservation requests' => [ReservationRequestsTable::class, 'No reservation requests yet'],
    'order inquiries' => [OrderInquiriesTable::class, 'No order inquiries yet'],
    'contact inquiries' => [ContactInquiriesTable::class, 'No contact inquiries yet'],
    'menu items' => [MenuItemsTable::class, 'No menu items yet'],
    'menu categories' => [MenuCategoriesTable::class, 'No menu categories yet'],
    'gallery images' => [GalleryImagesTable::class, 'No gallery images yet'],
    'pages' => [PagesTable::class, 'No pages yet'],
    'site settings' => [SiteSettingsTable::class, 'No site settings yet'],
]);

test('inquiry tables use review labels and preserve the review filter', function (string $tableDefinition) {
    $table = brandedResourceTable($tableDefinition);
    $reviewColumn = $table->getColumn('is_read');
    $reviewFilter = $table->getFilter('is_read');

    expect($reviewColumn)
        ->toBeInstanceOf(TextColumn::class)
        ->and($reviewColumn->getLabel())
        ->toBe('Review status')
        ->and($reviewColumn->isBadge())
        ->toBeTrue()
        ->and($reviewFilter)
        ->toBeInstanceOf(TernaryFilter::class)
        ->and($reviewFilter->getTrueLabel())
        ->toBe('Reviewed')
        ->and($reviewFilter->getFalseLabel())
        ->toBe('New');
})->with([
    'reservation requests' => [ReservationRequestsTable::class],
    'order inquiries' => [OrderInquiriesTable::class],
    'contact inquiries' => [ContactInquiriesTable::class],
]);
