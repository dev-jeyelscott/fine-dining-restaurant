<?php

use App\Models\GalleryImage;
use App\Services\ResponsiveImageManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

beforeEach(function (): void {
    Storage::fake('public');
});

function storeRegenerationTestImage(string $filename): string
{
    $path = UploadedFile::fake()
        ->image($filename, 2400, 1600)
        ->storeAs('gallery', $filename, 'public');

    expect($path)->toBeString();

    return $path;
}

/**
 * @return array<string, string>
 */
function storeWorkingRegenerationVariants(ResponsiveImageManager $manager, string $originalPath): array
{
    $stored = [];

    foreach ([
        ResponsiveImageManager::VARIANT_THUMBNAIL,
        ResponsiveImageManager::VARIANT_SMALL,
        ResponsiveImageManager::VARIANT_CARD,
        ResponsiveImageManager::VARIANT_LARGE,
        ResponsiveImageManager::VARIANT_HERO,
    ] as $variant) {
        $variantPath = $manager->variantPath($originalPath, $variant);
        $contents = "working-{$variant}";
        Storage::disk('public')->put($variantPath, $contents);
        $stored[$variantPath] = $contents;
    }

    return $stored;
}

test('successful regeneration replaces an existing derivative set', function (): void {
    $manager = app(ResponsiveImageManager::class);
    $path = storeRegenerationTestImage('replace-success.jpg');

    GalleryImage::query()->create([
        'title' => 'Replace Success',
        'image_path' => $path,
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    $cardPath = $manager->variantPath($path, ResponsiveImageManager::VARIANT_CARD);
    Storage::disk('public')->put($cardPath, 'stale-card');

    expect(Artisan::call('images:regenerate-responsive', ['--model' => 'gallery']))->toBe(0)
        ->and(Storage::disk('public')->get($cardPath))->not->toBe('stale-card')
        ->and(Artisan::output())->toContain('Processed 1 image records; succeeded 1; skipped 0; failed 0.');
});

test('corrupt sources preserve every existing derivative and fail the command', function (): void {
    $manager = app(ResponsiveImageManager::class);
    $path = 'gallery/corrupt-source.jpg';
    Storage::disk('public')->put($path, 'not-an-image');

    GalleryImage::query()->create([
        'title' => 'Corrupt Source',
        'image_path' => $path,
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    $workingVariants = storeWorkingRegenerationVariants($manager, $path);

    expect(Artisan::call('images:regenerate-responsive', ['--model' => 'gallery']))->toBe(1)
        ->and(Artisan::output())->toContain('Processed 1 image records; succeeded 0; skipped 0; failed 1.');

    foreach ($workingVariants as $variantPath => $contents) {
        expect(Storage::disk('public')->get($variantPath))->toBe($contents);
    }
});

test('missing sources preserve every existing derivative and fail the command', function (): void {
    $manager = app(ResponsiveImageManager::class);
    $path = 'gallery/missing-source.jpg';

    GalleryImage::query()->create([
        'title' => 'Missing Source',
        'image_path' => $path,
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    $workingVariants = storeWorkingRegenerationVariants($manager, $path);

    expect(Artisan::call('images:regenerate-responsive', ['--model' => 'gallery']))->toBe(1)
        ->and(Artisan::output())->toContain('Processed 1 image records; succeeded 0; skipped 0; failed 1.');

    foreach ($workingVariants as $variantPath => $contents) {
        expect(Storage::disk('public')->get($variantPath))->toBe($contents);
    }
});

test('mixed regeneration reports accurate totals and preserves failed records', function (): void {
    $manager = app(ResponsiveImageManager::class);
    $validPath = storeRegenerationTestImage('mixed-success.jpg');
    $missingPath = 'gallery/mixed-missing.jpg';

    GalleryImage::query()->create([
        'title' => 'Mixed Success',
        'image_path' => $validPath,
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    GalleryImage::query()->create([
        'title' => 'Mixed Missing',
        'image_path' => $missingPath,
        'sort_order' => 2,
        'is_visible' => true,
    ]);

    $validCardPath = $manager->variantPath($validPath, ResponsiveImageManager::VARIANT_CARD);
    Storage::disk('public')->put($validCardPath, 'stale-card');
    $workingMissingVariants = storeWorkingRegenerationVariants($manager, $missingPath);

    expect(Artisan::call('images:regenerate-responsive', ['--model' => 'gallery']))->toBe(1)
        ->and(Storage::disk('public')->get($validCardPath))->not->toBe('stale-card')
        ->and(Artisan::output())->toContain('Processed 2 image records; succeeded 1; skipped 0; failed 1.');

    foreach ($workingMissingVariants as $variantPath => $contents) {
        expect(Storage::disk('public')->get($variantPath))->toBe($contents);
    }
});
