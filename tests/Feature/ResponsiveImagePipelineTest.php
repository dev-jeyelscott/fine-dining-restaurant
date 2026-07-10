<?php

use App\Models\GalleryImage;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Rules\SafeImageDimensions;
use App\Services\ResponsiveImageManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

beforeEach(function (): void {
    Storage::fake('public');
});

function storeResponsiveTestImage(string $directory, string $filename = 'restaurant.jpg'): string
{
    $path = UploadedFile::fake()
        ->image($filename, 2400, 1600)
        ->storeAs($directory, $filename, 'public');

    expect($path)->toBeString();

    return $path;
}

test('gallery and menu uploads generate predictable responsive derivatives', function (): void {
    $manager = app(ResponsiveImageManager::class);
    $galleryPath = storeResponsiveTestImage('gallery', 'dining-room.jpg');
    $menuPath = storeResponsiveTestImage('menu-items', 'signature-dish.jpg');

    $galleryImage = GalleryImage::query()->create([
        'title' => 'Dining Room',
        'image_path' => $galleryPath,
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    $category = MenuCategory::query()->create([
        'name' => 'Signature Dishes',
        'slug' => 'signature-dishes',
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    $menuItem = MenuItem::query()->create([
        'menu_category_id' => $category->id,
        'name' => 'Truffle Tenderloin',
        'image_path' => $menuPath,
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    foreach ([
        ResponsiveImageManager::VARIANT_THUMBNAIL,
        ResponsiveImageManager::VARIANT_SMALL,
        ResponsiveImageManager::VARIANT_CARD,
        ResponsiveImageManager::VARIANT_LARGE,
        ResponsiveImageManager::VARIANT_HERO,
    ] as $variant) {
        Storage::disk('public')->assertExists($manager->variantPath($galleryPath, $variant));
        Storage::disk('public')->assertExists($manager->variantPath($menuPath, $variant));
    }

    $thumbnailDimensions = getimagesize(Storage::disk('public')->path(
        $manager->variantPath($galleryPath, ResponsiveImageManager::VARIANT_THUMBNAIL),
    ));

    expect($thumbnailDimensions)->not->toBeFalse()
        ->and($thumbnailDimensions[0])->toBe(160)
        ->and($thumbnailDimensions[1])->toBe(160)
        ->and($galleryImage->responsiveImageSrcset())->toContain('480w', '768w', '1280w', '1920w')
        ->and($menuItem->responsiveImagePath(ResponsiveImageManager::VARIANT_THUMBNAIL))
        ->toBe($manager->variantPath($menuPath, ResponsiveImageManager::VARIANT_THUMBNAIL));
});

test('oversized image dimensions are rejected before permanent upload storage', function (): void {
    $validator = Validator::make([
        'image' => UploadedFile::fake()->image('too-wide.jpg', 6001, 10),
    ], [
        'image' => [new SafeImageDimensions],
    ]);

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->first('image'))->toContain('dimensions are too large');
});

test('missing derivatives fall back to the original image without breaking rendering', function (): void {
    $originalPath = 'gallery/original-only.jpg';
    Storage::disk('public')->put($originalPath, 'original-image-contents');

    $image = GalleryImage::query()->create([
        'title' => 'Original Only',
        'image_path' => $originalPath,
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    expect($image->responsiveImagePath(ResponsiveImageManager::VARIANT_HERO))->toBe($originalPath)
        ->and($image->responsiveImageUrl(ResponsiveImageManager::VARIANT_HERO))->toBe(Storage::disk('public')->url($originalPath))
        ->and($image->responsiveImageSrcset())->toBeNull();
});

test('public pages render responsive image selection while preserving hero priority and lazy loading', function (): void {
    $galleryPath = storeResponsiveTestImage('gallery', 'gallery-hero.jpg');
    $menuPath = storeResponsiveTestImage('menu-items', 'menu-card.jpg');

    GalleryImage::query()->create([
        'title' => 'Gallery Hero',
        'alt_text' => 'Elegant dining room',
        'image_path' => $galleryPath,
        'category' => 'Ambiance',
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    $category = MenuCategory::query()->create([
        'name' => 'Dinner',
        'slug' => 'dinner',
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    MenuItem::query()->create([
        'menu_category_id' => $category->id,
        'name' => 'Responsive Menu Item',
        'image_path' => $menuPath,
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('<picture', false)
        ->assertSee('srcset=', false)
        ->assertSee('sizes=', false)
        ->assertSee('fetchpriority="high"', false)
        ->assertSee('loading="lazy"', false);

    $this->get(route('menu'))
        ->assertOk()
        ->assertSeeText('Responsive Menu Item')
        ->assertSee('<picture', false)
        ->assertSee('srcset=', false);

    $this->get(route('gallery'))
        ->assertOk()
        ->assertSeeText('Gallery Hero')
        ->assertSee('<picture', false)
        ->assertSee('srcset=', false)
        ->assertSee('fetchpriority="high"', false);
});

test('filament image tables resolve dedicated thumbnail variants', function (): void {
    $galleryTable = File::get(app_path('Filament/Resources/GalleryImages/Tables/GalleryImagesTable.php'));
    $menuTable = File::get(app_path('Filament/Resources/MenuItems/Tables/MenuItemsTable.php'));

    expect($galleryTable)
        ->toContain('ResponsiveImageManager::VARIANT_THUMBNAIL')
        ->toContain("ImageColumn::make('responsive_thumbnail')");

    expect($menuTable)
        ->toContain('ResponsiveImageManager::VARIANT_THUMBNAIL')
        ->toContain("ImageColumn::make('responsive_thumbnail')");
});

test('responsive image regeneration command rebuilds derivatives for existing uploads', function (): void {
    $manager = app(ResponsiveImageManager::class);
    $path = storeResponsiveTestImage('gallery', 'regenerate.jpg');

    GalleryImage::query()->create([
        'title' => 'Regenerate',
        'image_path' => $path,
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    $manager->deleteVariants($path);
    Storage::disk('public')->assertMissing($manager->variantPath(
        $path,
        ResponsiveImageManager::VARIANT_CARD,
    ));

    expect(Artisan::call('images:regenerate-responsive', ['--model' => 'gallery']))->toBe(0);

    Storage::disk('public')->assertExists($manager->variantPath(
        $path,
        ResponsiveImageManager::VARIANT_CARD,
    ));
});

test('responsive derivatives reduce representative page image weight budgets', function (): void {
    $manager = app(ResponsiveImageManager::class);
    $path = storeResponsiveTestImage('gallery', 'weight-evidence.jpg');

    GalleryImage::query()->create([
        'title' => 'Weight Evidence',
        'image_path' => $path,
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    $disk = Storage::disk('public');
    $originalBytes = $disk->size($path);
    $heroBytes = $disk->size($manager->variantPath($path, ResponsiveImageManager::VARIANT_HERO));
    $largeBytes = $disk->size($manager->variantPath($path, ResponsiveImageManager::VARIANT_LARGE));
    $cardBytes = $disk->size($manager->variantPath($path, ResponsiveImageManager::VARIANT_CARD));
    $thumbnailBytes = $disk->size($manager->variantPath($path, ResponsiveImageManager::VARIANT_THUMBNAIL));

    $budgets = [
        'Home' => [
            'before' => $originalBytes * 10,
            'after' => $heroBytes + ($largeBytes * 6) + ($cardBytes * 3),
        ],
        'Menu' => [
            'before' => $originalBytes * 12,
            'after' => $cardBytes * 12,
        ],
        'Gallery' => [
            'before' => $originalBytes * 13,
            'after' => $heroBytes + ($largeBytes * 12),
        ],
        'Filament gallery table' => [
            'before' => $originalBytes * 12,
            'after' => $thumbnailBytes * 12,
        ],
    ];

    $evidence = [];

    foreach ($budgets as $page => $budget) {
        expect($budget['after'])->toBeLessThan($budget['before'], "Expected {$page} responsive image budget to be lower.");

        $evidence[$page] = [
            ...$budget,
            'reduction_percent' => round((1 - ($budget['after'] / $budget['before'])) * 100, 1),
        ];
    }

    fwrite(
        STDERR,
        PHP_EOL.'Responsive image page-weight evidence: '.json_encode(
            $evidence,
            JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR,
        ).PHP_EOL,
    );
});
