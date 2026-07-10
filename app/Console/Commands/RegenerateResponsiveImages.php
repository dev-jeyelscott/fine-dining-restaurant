<?php

namespace App\Console\Commands;

use App\Models\GalleryImage;
use App\Models\MenuItem;
use App\Services\ResponsiveImageManager;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

final class RegenerateResponsiveImages extends Command
{
    protected $signature = 'images:regenerate-responsive
        {--model=all : Process all, gallery, or menu images}';

    protected $description = 'Regenerate responsive image derivatives for existing gallery and menu uploads';

    public function handle(ResponsiveImageManager $manager): int
    {
        if (! $manager->isAvailable()) {
            $this->error('The GD extension with JPEG support is required to generate responsive images.');

            return self::FAILURE;
        }

        $modelOption = strtolower((string) $this->option('model'));
        $models = match ($modelOption) {
            'all' => [GalleryImage::class, MenuItem::class],
            'gallery' => [GalleryImage::class],
            'menu' => [MenuItem::class],
            default => [],
        };

        if ($models === []) {
            $this->error('The --model option must be one of: all, gallery, menu.');

            return self::INVALID;
        }

        $processed = 0;
        $generated = 0;

        foreach ($models as $modelClass) {
            /** @var Builder<Model> $query */
            $query = $modelClass::query()->whereNotNull('image_path');

            $query->chunkById(100, function ($records) use ($manager, &$processed, &$generated): void {
                foreach ($records as $record) {
                    $path = $record->getAttribute('image_path');

                    if (! is_string($path) || $path === '') {
                        continue;
                    }

                    $processed++;
                    $manager->deleteVariants($path);

                    if ($manager->generate($path) !== []) {
                        $generated++;
                    }
                }
            });
        }

        $this->info(sprintf(
            'Processed %d image records; generated derivatives for %d existing originals.',
            $processed,
            $generated,
        ));

        return self::SUCCESS;
    }
}
