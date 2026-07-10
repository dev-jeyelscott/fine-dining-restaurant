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
            $dimensions = @getimagesizefromstring($contents);

            if ($dimensions === false) {
                Log::warning('Responsive image generation skipped because image dimensions could not be read.', [
                    'path' => $originalPath,
                ]);

                return [];
            }

            $sourceWidth = (int) $dimensions[0];
            $sourceHeight = (int) $dimensions[1];

            if (! $this->hasSafeSourceDimensions($sourceWidth, $sourceHeight)) {
                Log::warning('Responsive image generation skipped because source dimensions exceed the safety budget.', [
                    'path' => $originalPath,
                    'width' => $sourceWidth,
                    'height' => $sourceHeight,
                ]);

                return [];
            }

            $source = @imagecreatefromstring($contents);

            if (! $source instanceof GdImage) {
                Log::warning('Responsive image generation skipped because the source image could not be decoded.', [
                    'path' => $originalPath,
                ]);

                return [];
            }

            $generated = [];

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
                            $definition['height'] ?? $definition['width'],
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
                        continue;
                    }

                    $variantPath = $this->variantPath($originalPath, $name);

                    if ($disk->put($variantPath, $encoded, ['visibility' => 'public'])) {
                        $generated[$name] = $variantPath;
                    }
                }
            } finally {
                imagedestroy($source);
            }

            return $generated;
        } catch (Throwable $exception) {
            Log::warning('Responsive image generation failed; the original image remains available.', [
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

        $this->disk()->delete(
            array_map(
                fn (string $variant): string => $this->variantPath($originalPath, $variant),
                array_keys(self::VARIANTS),
            ),
        );
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

        return $originalPath;
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

        if (! imagecopyresampled(
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
        )) {
            imagedestroy($target);

            throw new RuntimeException('Responsive image resize failed.');
        }

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

        if (! imagecopyresampled(
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
        )) {
            imagedestroy($target);

            throw new RuntimeException('Responsive image crop failed.');
        }

        return $target;
    }

    private function createCanvas(int $width, int $height): GdImage
    {
        $canvas = imagecreatetruecolor($width, $height);

        if (! $canvas instanceof GdImage) {
            throw new RuntimeException('Responsive image canvas could not be allocated.');
        }

        $background = imagecolorallocate($canvas, 255, 255, 255);

        if ($background === false || ! imagefill($canvas, 0, 0, $background)) {
            imagedestroy($canvas);

            throw new RuntimeException('Responsive image canvas could not be initialized.');
        }

        return $canvas;
    }

    private function encodeJpeg(GdImage $image, int $quality): ?string
    {
        imageinterlace($image, true);
        ob_start();
        $encoded = imagejpeg($image, null, $quality);
        $contents = ob_get_clean();

        return $encoded && is_string($contents) ? $contents : null;
    }
}
