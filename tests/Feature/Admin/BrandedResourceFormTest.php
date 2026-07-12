<?php

use App\Filament\Resources\GalleryImages\Schemas\GalleryImageForm;
use App\Filament\Resources\MenuCategories\Schemas\MenuCategoryForm;
use App\Filament\Resources\MenuItems\Schemas\MenuItemForm;
use App\Filament\Resources\Pages\Schemas\PageForm;
use App\Filament\Resources\SiteSettings\Schemas\SiteSettingForm;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Livewire\Component;

/**
 * @param  class-string  $form
 * @return array<int, Section>
 */
function brandedFormSections(string $form): array
{
    return array_values(array_filter(
        $form::configure(Schema::make(Mockery::mock(Component::class, HasSchemas::class)))->getComponents(),
        static fn (mixed $component): bool => $component instanceof Section,
    ));
}

test('approved editable resources use labelled form sections', function (string $form, array $headings) {
    $sections = brandedFormSections($form);

    expect($sections)
        ->toHaveCount(count($headings))
        ->and(array_map(static fn (Section $section): string => (string) $section->getHeading(), $sections))
        ->toBe($headings);
})->with([
    'site settings' => [SiteSettingForm::class, ['Setting details', 'Setting value']],
    'pages' => [PageForm::class, ['Page content', 'Search preview', 'Publication']],
    'menu categories' => [MenuCategoryForm::class, ['Category details', 'Display settings']],
    'menu items' => [MenuItemForm::class, ['Menu item details', 'Menu image', 'Display settings']],
    'gallery images' => [GalleryImageForm::class, ['Image details', 'Gallery image', 'Display settings']],
]);

test('branded image sections preserve the existing upload safeguards', function (string $form, string $directory) {
    $upload = collect(brandedFormSections($form))
        ->flatMap(static fn (Section $section): array => $section->getChildComponents())
        ->first(static fn (mixed $component): bool => $component instanceof FileUpload);

    expect($upload)
        ->toBeInstanceOf(FileUpload::class)
        ->and($upload->getDirectory())
        ->toBe($directory)
        ->and($upload->getDiskName())
        ->toBe('public')
        ->and($upload->getMaxSize())
        ->toBe(2048)
        ->and($upload->getAcceptedFileTypes())
        ->toBe(['image/jpeg', 'image/png', 'image/webp']);
})->with([
    'menu item image' => [MenuItemForm::class, 'menu-items'],
    'gallery image' => [GalleryImageForm::class, 'gallery'],
]);
