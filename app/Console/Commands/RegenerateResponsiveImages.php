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

        if (! in_array($modelOption, ['all', 'gallery', 'menu'], true)) {
            $this->error('The --model option must be one of: all, gallery, menu.');

            return self::INVALID;
        }

        $processed = 0;
        $generated = 0;

        if (in_array($modelOption, ['all', 'gallery'], true)) {
            $result = $this->regenerateQuery(
                GalleryImage::query()->whereNotNull('image_path'),
                $manager,
            );

            $processed += $result['processed'];
            $generated += $result['generated'];
        }

        if (in_array($modelOption, ['all', 'menu'], true)) {
            $result = $this->regenerateQuery(
                MenuItem::query()->whereNotNull('image_path'),
                $manager,
            );

            $processed += $result['processed'];
            $generated += $result['generated'];
        }

        $this->info(sprintf(
            'Processed %d image records; generated derivatives for %d existing originals.',
            $processed,
            $generated,
        ));

        return self::SUCCESS;
    }

    /**
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     * @return array{processed: int, generated: int}
     */
    private function regenerateQuery(Builder $query, ResponsiveImageManager $manager): array
    {
        $processed = 0;
        $generated = 0;

        $query->eachById(function (Model $record) use ($manager, &$processed, &$generated): void {
            $path = $record->getAttribute('image_path');

            if (! is_string($path) || $path === '') {
                return;
            }

            $processed++;
            $manager->deleteVariants($path);

            if ($manager->generate($path) !== []) {
                $generated++;
            }
        }, 100);

        return [
            'processed' => $processed,
            'generated' => $generated,
        ];
    }
}
