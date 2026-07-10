<?php

use App\Models\GalleryImage;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Services\ResponsiveImageManager;
use Illuminate\Support\Facades\Storage;

beforeEach(function (): void {
    Storage::fake('public');
});

test('stale gallery and menu image paths do not emit broken storage urls', function (): void {
    $galleryPath = 'gallery/missing-gallery-image.jpg';
    $menuPath = 'menu-items/missing-menu-image.jpg';
    $manager = app(ResponsiveImageManager::class);

    $galleryImage = GalleryImage::query()->create([
        'title' => 'Missing Gallery Image',
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
        'name' => 'Missing Menu Image',
        'image_path' => $menuPath,
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    expect($manager->resolvePath($galleryPath, ResponsiveImageManager::VARIANT_HERO))->toBeNull()
        ->and($manager->resolveUrl($galleryPath, ResponsiveImageManager::VARIANT_HERO))->toBeNull()
        ->and($galleryImage->responsiveImagePath(ResponsiveImageManager::VARIANT_HERO))->toBeNull()
        ->and($galleryImage->responsiveImageUrl(ResponsiveImageManager::VARIANT_HERO))->toBeNull()
        ->and($galleryImage->image_url)->toBeNull()
        ->and($menuItem->responsiveImagePath(ResponsiveImageManager::VARIANT_CARD))->toBeNull()
        ->and($menuItem->responsiveImageUrl(ResponsiveImageManager::VARIANT_CARD))->toBeNull()
        ->and($menuItem->image_url)->toBeNull();
});

test('reservation hero renders its approved fallback for a stale managed image path', function (): void {
    $missingPath = 'gallery/missing-reservation-hero.jpg';

    GalleryImage::query()->create([
        'title' => 'Missing Reservation Hero',
        'alt_text' => 'Missing reservation hero image',
        'image_path' => $missingPath,
        'category' => 'interior',
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    $this->get(route('reservation-request.create'))
        ->assertOk()
        ->assertDontSee(Storage::disk('public')->url($missingPath), false)
        ->assertSee('linear-gradient(135deg,#353126,#171916_68%)', false);
});
