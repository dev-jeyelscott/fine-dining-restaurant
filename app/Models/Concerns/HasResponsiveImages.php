<?php

namespace App\Models\Concerns;

use App\Services\ResponsiveImageManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * @mixin Model
 */
trait HasResponsiveImages
{
    protected static function bootHasResponsiveImages(): void
    {
        static::saved(function (Model $model): void {
            if (! $model->wasRecentlyCreated && ! $model->wasChanged('image_path')) {
                return;
            }

            $manager = app(ResponsiveImageManager::class);
            $currentPath = $model->getAttribute('image_path');
            $previousPath = $model->getOriginal('image_path');

            try {
                if (
                    $model->wasChanged('image_path')
                    && is_string($previousPath)
                    && $previousPath !== ''
                    && $previousPath !== $currentPath
                ) {
                    $manager->deleteVariants($previousPath);
                }

                if (is_string($currentPath) && $currentPath !== '') {
                    $manager->generate($currentPath);
                }
            } catch (Throwable $exception) {
                Log::warning('Responsive image processing failed after the model was saved.', [
                    'model' => $model::class,
                    'model_id' => $model->getKey(),
                    'image_path' => $currentPath,
                    'exception' => $exception,
                ]);
            }
        });

        static::deleted(function (Model $model): void {
            $path = $model->getAttribute('image_path');

            if (! is_string($path) || $path === '') {
                return;
            }

            try {
                app(ResponsiveImageManager::class)->deleteVariants($path);
            } catch (Throwable $exception) {
                Log::warning('Responsive image cleanup failed after the model was deleted.', [
                    'model' => $model::class,
                    'model_id' => $model->getKey(),
                    'image_path' => $path,
                    'exception' => $exception,
                ]);
            }
        });
    }

    public function getImageUrlAttribute(): ?string
    {
        $path = $this->responsiveImageOriginalPath();

        return $path === null
            ? null
            : app(ResponsiveImageManager::class)->resolveUrl(
                $path,
                ResponsiveImageManager::VARIANT_LARGE,
            );
    }

    public function responsiveImagePath(string $variant = ResponsiveImageManager::VARIANT_CARD): ?string
    {
        $path = $this->responsiveImageOriginalPath();

        return $path === null
            ? null
            : app(ResponsiveImageManager::class)->resolvePath($path, $variant);
    }

    public function responsiveImageUrl(string $variant = ResponsiveImageManager::VARIANT_CARD): ?string
    {
        $path = $this->responsiveImageOriginalPath();

        return $path === null
            ? null
            : app(ResponsiveImageManager::class)->resolveUrl($path, $variant);
    }

    public function responsiveImageSrcset(): ?string
    {
        $path = $this->responsiveImageOriginalPath();

        return $path === null
            ? null
            : app(ResponsiveImageManager::class)->srcset($path);
    }

    private function responsiveImageOriginalPath(): ?string
    {
        $path = $this->getAttribute('image_path');

        return is_string($path) && $path !== '' ? $path : null;
    }
}
