<?php

namespace App\Services;

use GdImage;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

final class ResponsiveImageManager
{
    public const VARIANT_THUMBNAIL = 'thumbnail';

    public const VARIANT_SMALL = 'small';

    public const VARIANT_CARD = 'card';

    public const VARIANT_LARGE = 'large';

    public const VARIANT_HERO = 'hero';

    private const MAX_SOURCE_WIDTH = 6000;

    private const MAX_SOURCE_HEIGHT = 6000;

    private const MAX_SOURCE_PIXELS = 30_000_000;

    /**
     * @var array<string, array{width: int, height: int|null, crop: bool, quality: int}>
     */
    private const VARIANTS = [
        self::VARIANT_THUMBNAIL => [
            'width' => 160,
            'height' => 160,
            'crop' => true,
            'quality' => 76,
        ],
        self::VARIANT_SMALL => [
            'width' => 480,
            'height' => null,
            'crop' => false,
            'quality' => 80,
        ],
        self::VARIANT_CARD => [
            'width' => 768,
            'height' => null,
            'crop' => false,
            'quality' => 82,
        ],
        self::VARIANT_LARGE => [
            'width' => 1280,
            'height' => null,
            'crop' => false,
            'quality' => 84,
        ],
        self::VARIANT_HERO => [
            'width' => 1920,
            'height' => null,
            'crop' => false,
            'quality' => 84,
        ],
    ];

    /**
     * @var array<string, list<string>>
     */
    private const FALLBACK_VARIANTS = [
        self::VARIANT_THUMBNAIL => [self::VARIANT_THUMBNAIL, self::VARIANT_SMALL],
        self::VARIANT_SMALL => [self::VARIANT_SMALL],
        self::VARIANT_CARD => [self::VARIANT_CARD, self::VARIANT_SMALL],
        self::VARIANT_LARGE => [self::VARIANT_LARGE, self::VARIANT_CARD, self::VARIANT_SMALL],
        self::VARIANT_HERO => [self::VARIANT_HERO, self::VARIANT_LARGE, self::VARIANT_CARD, self::VARIANT_SMALL],
    ];

    public function isAvailable(): bool
    {
        return function_exists('imagecreatefromstring')
            && function_exists('imagecreatetruecolor')
            && function_exists('imagecopyresampled')
            && function_exists('imagejpeg');
    }

    /**
     * @return array<string, string>
     */
    public function generate(string $originalPath): array
    {
        $disk = $this->disk();

        if (! $this->isAvailable() || blank($originalPath) || ! $disk->exists($originalPath)) {
            return [];
        }

        try {
            $contents = $disk->get($originalPath);
            $encodedVariants = $this->encodeVariants($contents, $originalPath);

            return $this->replaceVariantSet($disk, $originalPath, $encodedVariants);
        } catch (Throwable $exception) {
            Log::warning('Responsive image generation failed; the original image and any existing derivatives remain available.', [
                'path' => $originalPath,
                'exception' => $exception,
            ]);

            return [];
        }
    }

    public function deleteVariants(string $originalPath): void
    {
        if (blank($originalPath)) {
            return;
        }

        $this->disk()->delete($this->variantPaths($originalPath));
    }

    public function resolvePath(string $originalPath, string $preferredVariant): ?string
    {
        if (blank($originalPath)) {
            return null;
        }

        $disk = $this->disk();

        foreach (self::FALLBACK_VARIANTS[$preferredVariant] ?? [$preferredVariant] as $variant) {
            $variantPath = $this->variantPath($originalPath, $variant);

            if ($disk->exists($variantPath)) {
                return $variantPath;
            }
        }

        return $disk->exists($originalPath) ? $originalPath : null;
    }

    public function resolveUrl(string $originalPath, string $preferredVariant): ?string
    {
        $path = $this->resolvePath($originalPath, $preferredVariant);

        return $path === null ? null : $this->disk()->url($path);
    }

    public function originalUrl(string $originalPath): ?string
    {
        if (blank($originalPath)) {
            return null;
        }

        return $this->disk()->url($originalPath);
    }

    public function srcset(string $originalPath): ?string
    {
        if (blank($originalPath)) {
            return null;
        }

        $disk = $this->disk();
        $sources = [];

        foreach ([self::VARIANT_SMALL, self::VARIANT_CARD, self::VARIANT_LARGE, self::VARIANT_HERO] as $variant) {
            $path = $this->variantPath($originalPath, $variant);

            if (! $disk->exists($path)) {
                continue;
            }

            $sources[] = sprintf(
                '%s %dw',
                $disk->url($path),
                self::VARIANTS[$variant]['width'],
            );
        }

        return $sources === [] ? null : implode(', ', $sources);
    }

    public function variantPath(string $originalPath, string $variant): string
    {
        $this->assertKnownVariant($variant);

        $directory = pathinfo($originalPath, PATHINFO_DIRNAME);
        $filename = pathinfo($originalPath, PATHINFO_FILENAME);
        $prefix = $directory === '.' ? '' : $directory.'/';

        return sprintf('%svariants/%s-%s.jpg', $prefix, $filename, $variant);
    }

    public function variantWidth(string $variant): int
    {
        $this->assertKnownVariant($variant);

        return self::VARIANTS[$variant]['width'];
    }

    private function disk(): FilesystemAdapter
    {
        return Storage::disk('public');
    }

    /**
     * @return array<string, string>
     */
    private function encodeVariants(string $contents, string $originalPath): array
    {
        $dimensions = @getimagesizefromstring($contents);

        if ($dimensions === false) {
            throw new RuntimeException('Responsive image dimensions could not be read.');
        }

        $sourceWidth = (int) $dimensions[0];
        $sourceHeight = (int) $dimensions[1];

        if (! $this->hasSafeSourceDimensions($sourceWidth, $sourceHeight)) {
            throw new RuntimeException(sprintf(
                'Responsive image source dimensions exceed the safety budget for [%s].',
                $originalPath,
            ));
        }

        $source = @imagecreatefromstring($contents);

        if (! $source instanceof GdImage) {
            throw new RuntimeException('Responsive image source could not be decoded.');
        }

        $encodedVariants = [];

        try {
            foreach (self::VARIANTS as $name => $definition) {
                if (! $definition['crop'] && $sourceWidth < $definition['width']) {
                    continue;
                }

                $variant = $definition['crop']
                    ? $this->resizeToCover(
                        $source,
                        $sourceWidth,
                        $sourceHeight,
                        $definition['width'],
                        $definition['height'],
                    )
                    : $this->resizeToWidth(
                        $source,
                        $sourceWidth,
                        $sourceHeight,
                        $definition['width'],
                    );

                try {
                    $encoded = $this->encodeJpeg($variant, $definition['quality']);
                } finally {
                    imagedestroy($variant);
                }

                if ($encoded === null) {
                    throw new RuntimeException("Responsive image variant [{$name}] could not be encoded.");
                }

                $encodedVariants[$name] = $encoded;
            }
        } finally {
            imagedestroy($source);
        }

        return $encodedVariants;
    }

    /**
     * @param  array<string, string>  $encodedVariants
     * @return array<string, string>
     */
    private function replaceVariantSet(
        FilesystemAdapter $disk,
        string $originalPath,
        array $encodedVariants,
    ): array {
        $transactionId = bin2hex(random_bytes(16));
        $variantPaths = $this->variantPaths($originalPath);
        $stagedPaths = [];
        $backupPaths = [];
        $promotionStarted = false;

        try {
            foreach ($encodedVariants as $variant => $encoded) {
                $targetPath = $this->variantPath($originalPath, $variant);
                $temporaryPath = $this->transactionPath($targetPath, 'staged', $transactionId);

                if (! $disk->put($temporaryPath, $encoded)) {
                    throw new RuntimeException("Responsive image variant [{$variant}] could not be staged.");
                }

                $stagedPaths[$targetPath] = $temporaryPath;
            }

            foreach ($variantPaths as $targetPath) {
                if (! $disk->exists($targetPath)) {
                    continue;
                }

                $backupPath = $this->transactionPath($targetPath, 'backup', $transactionId);

                if (! $disk->copy($targetPath, $backupPath)) {
                    throw new RuntimeException("Responsive image variant [{$targetPath}] could not be backed up.");
                }

                $backupPaths[$targetPath] = $backupPath;
            }

            $promotionStarted = true;

            foreach ($stagedPaths as $targetPath => $temporaryPath) {
                if (! $disk->put($targetPath, $disk->get($temporaryPath), ['visibility' => 'public'])) {
                    throw new RuntimeException("Responsive image variant [{$targetPath}] could not be promoted.");
                }
            }

            $obsoletePaths = array_values(array_diff($variantPaths, array_keys($stagedPaths)));

            if ($obsoletePaths !== [] && ! $disk->delete($obsoletePaths)) {
                throw new RuntimeException('Obsolete responsive image variants could not be removed.');
            }

            $this->cleanupTransactionFiles($disk, [...array_values($stagedPaths), ...array_values($backupPaths)]);

            $generated = [];

            foreach (array_keys($encodedVariants) as $variant) {
                $generated[$variant] = $this->variantPath($originalPath, $variant);
            }

            return $generated;
        } catch (Throwable $exception) {
            if ($promotionStarted) {
                $this->restoreVariantSet($disk, $variantPaths, $backupPaths);
            }

            $this->cleanupTransactionFiles($disk, [...array_values($stagedPaths), ...array_values($backupPaths)]);

            throw $exception;
        }
    }

    /**
     * @param  list<string>  $variantPaths
     * @param  array<string, string>  $backupPaths
     */
    private function restoreVariantSet(
        FilesystemAdapter $disk,
        array $variantPaths,
        array $backupPaths,
    ): void {
        foreach ($variantPaths as $targetPath) {
            try {
                if (isset($backupPaths[$targetPath])) {
                    if (! $disk->put($targetPath, $disk->get($backupPaths[$targetPath]), ['visibility' => 'public'])) {
                        throw new RuntimeException("Responsive image variant [{$targetPath}] could not be restored.");
                    }

                    continue;
                }

                $disk->delete($targetPath);
            } catch (Throwable $exception) {
                Log::error('Responsive image rollback could not restore the previous derivative set.', [
                    'path' => $targetPath,
                    'exception' => $exception,
                ]);
            }
        }
    }

    /**
     * @param  list<string>  $paths
     */
    private function cleanupTransactionFiles(FilesystemAdapter $disk, array $paths): void
    {
        if ($paths === []) {
            return;
        }

        try {
            if (! $disk->delete($paths)) {
                Log::warning('Responsive image transaction files could not be fully removed.', [
                    'paths' => $paths,
                ]);
            }
        } catch (Throwable $exception) {
            Log::warning('Responsive image transaction file cleanup failed.', [
                'paths' => $paths,
                'exception' => $exception,
            ]);
        }
    }

    /**
     * @return list<string>
     */
    private function variantPaths(string $originalPath): array
    {
        return array_map(
            fn (string $variant): string => $this->variantPath($originalPath, $variant),
            array_keys(self::VARIANTS),
        );
    }

    private function transactionPath(string $variantPath, string $type, string $transactionId): string
    {
        $directory = pathinfo($variantPath, PATHINFO_DIRNAME);
        $filename = pathinfo($variantPath, PATHINFO_BASENAME);

        return sprintf('%s/.responsive-image-%s/%s-%s', $directory, $type, $transactionId, $filename);
    }

    private function hasSafeSourceDimensions(int $width, int $height): bool
    {
        return $width > 0
            && $height > 0
            && $width <= self::MAX_SOURCE_WIDTH
            && $height <= self::MAX_SOURCE_HEIGHT
            && ($width * $height) <= self::MAX_SOURCE_PIXELS;
    }

    private function assertKnownVariant(string $variant): void
    {
        if (! array_key_exists($variant, self::VARIANTS)) {
            throw new InvalidArgumentException("Unknown responsive image variant [{$variant}].");
        }
    }

    private function resizeToWidth(GdImage $source, int $sourceWidth, int $sourceHeight, int $targetWidth): GdImage
    {
        $targetHeight = max(1, (int) round($sourceHeight * ($targetWidth / $sourceWidth)));
        $target = $this->createCanvas($targetWidth, $targetHeight);

        imagecopyresampled(
            $target,
            $source,
            0,
            0,
            0,
            0,
            $targetWidth,
            $targetHeight,
            $sourceWidth,
            $sourceHeight,
        );

        return $target;
    }

    private function resizeToCover(
        GdImage $source,
        int $sourceWidth,
        int $sourceHeight,
        int $targetWidth,
        int $targetHeight,
    ): GdImage {
        $sourceRatio = $sourceWidth / $sourceHeight;
        $targetRatio = $targetWidth / $targetHeight;

        if ($sourceRatio > $targetRatio) {
            $cropHeight = $sourceHeight;
            $cropWidth = (int) round($sourceHeight * $targetRatio);
            $sourceX = (int) floor(($sourceWidth - $cropWidth) / 2);
            $sourceY = 0;
        } else {
            $cropWidth = $sourceWidth;
            $cropHeight = (int) round($sourceWidth / $targetRatio);
            $sourceX = 0;
            $sourceY = (int) floor(($sourceHeight - $cropHeight) / 2);
        }

        $target = $this->createCanvas($targetWidth, $targetHeight);

        imagecopyresampled(
            $target,
            $source,
            0,
            0,
            $sourceX,
            $sourceY,
            $targetWidth,
            $targetHeight,
            $cropWidth,
            $cropHeight,
        );

        return $target;
    }

    private function createCanvas(int $width, int $height): GdImage
    {
        if ($width < 1 || $height < 1) {
            throw new RuntimeException('Responsive image canvas dimensions must be positive.');
        }

        $canvas = imagecreatetruecolor($width, $height);

        if (! $canvas instanceof GdImage) {
            throw new RuntimeException('Responsive image canvas could not be allocated.');
        }

        $background = imagecolorallocate($canvas, 255, 255, 255);

        if ($background === false) {
            imagedestroy($canvas);

            throw new RuntimeException('Responsive image canvas could not be initialized.');
        }

        imagefill($canvas, 0, 0, $background);

        return $canvas;
    }

    private function encodeJpeg(GdImage $image, int $quality): ?string
    {
        imageinterlace($image, true);
        ob_start();
        $encoded = imagejpeg($image, null, $quality);
        $contents = ob_get_clean();

        return $encoded ? $contents : null;
    }
}
