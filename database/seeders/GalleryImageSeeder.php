<?php

namespace Database\Seeders;

use App\Models\GalleryImage;
use Illuminate\Database\Seeder;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\File as HttpFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class GalleryImageSeeder extends Seeder
{
    private const IMAGE_SOURCE_DIRECTORY = 'seeders/images/menu';

    private const IMAGE_STORAGE_DIRECTORY = 'gallery';

    private const MAX_IMAGE_SIZE_IN_BYTES = 2 * 1024 * 1024;

    /**
     * @var array<string, string>
     */
    private const IMAGE_EXTENSIONS_BY_MIME_TYPE = [
        'image/jpeg' => 'jpg',
        'image/webp' => 'webp',
    ];

    public function run(): void
    {
        $disk = Storage::disk('public');

        $disk->makeDirectory(self::IMAGE_STORAGE_DIRECTORY);

        $images = [
            [
                'title' => 'Elegant Dining Room',
                'alt_text' => 'Elegant fine-dining restaurant interior',
                'image' => 'dining-room.webp',
                'category' => 'interior',
                'sort_order' => 1,
                'is_visible' => true,
            ],
            [
                'title' => 'Signature Dish',
                'alt_text' => 'Chef-prepared signature dish presentation',
                'image' => 'Seared-Hokkaido-Scallops.webp',
                'category' => 'dish',
                'sort_order' => 2,
                'is_visible' => true,
            ],
            [
                'title' => 'Banquet Setup',
                'alt_text' => 'Banquet hall setup for a private event',
                'image' => 'banquet-room.webp',
                'category' => 'banquet',
                'sort_order' => 3,
                'is_visible' => true,
            ],
            [
                'title' => 'Warm Restaurant Ambiance',
                'alt_text' => 'Warm restaurant lighting and ambiance',
                'image' => 'warm-ambiance.webp',
                'category' => 'ambiance',
                'sort_order' => 4,
                'is_visible' => true,
            ],
        ];

        foreach ($images as $imageData) {
            $imageFilename = $imageData['image'];

            unset($imageData['image']);

            GalleryImage::updateOrCreate(
                ['title' => $imageData['title']],
                [
                    ...$imageData,
                    'image_path' => $this->storeSeedImage(
                        disk: $disk,
                        sourceFilename: $imageFilename,
                    ),
                ],
            );
        }
    }

    private function storeSeedImage(
        FilesystemAdapter $disk,
        string $sourceFilename,
    ): string {
        $sourcePath = database_path(self::IMAGE_SOURCE_DIRECTORY.'/'.$sourceFilename);

        if (! File::isFile($sourcePath)) {
            throw new RuntimeException("Gallery seed image does not exist: {$sourcePath}");
        }

        if (! File::isReadable($sourcePath)) {
            throw new RuntimeException("Gallery seed image is not readable: {$sourcePath}");
        }

        $size = File::size($sourcePath);

        if ($size > self::MAX_IMAGE_SIZE_IN_BYTES) {
            throw new RuntimeException(
                sprintf(
                    'Gallery seed image exceeds the 2 MB upload limit (%d bytes): %s',
                    $size,
                    $sourcePath,
                ),
            );
        }

        $mimeType = File::mimeType($sourcePath);

        if (! is_string($mimeType) || ! array_key_exists($mimeType, self::IMAGE_EXTENSIONS_BY_MIME_TYPE)) {
            throw new RuntimeException(
                sprintf(
                    'Unsupported gallery seed image type "%s" for file: %s',
                    $mimeType ?: 'unknown',
                    $sourcePath,
                ),
            );
        }

        $contentHash = hash_file('sha256', $sourcePath);

        if (! is_string($contentHash)) {
            throw new RuntimeException("Unable to hash gallery seed image: {$sourcePath}");
        }

        $destinationFilename = $contentHash.'.'.self::IMAGE_EXTENSIONS_BY_MIME_TYPE[$mimeType];
        $destinationPath = self::IMAGE_STORAGE_DIRECTORY.'/'.$destinationFilename;

        if ($disk->exists($destinationPath)) {
            return $destinationPath;
        }

        $storedPath = $disk->putFileAs(
            self::IMAGE_STORAGE_DIRECTORY,
            new HttpFile($sourcePath),
            $destinationFilename,
            ['visibility' => 'public'],
        );

        if ($storedPath === false) {
            throw new RuntimeException("Unable to store gallery seed image: {$destinationPath}");
        }

        return $storedPath;
    }
}
