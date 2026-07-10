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

        $totals = [
            'processed' => 0,
            'succeeded' => 0,
            'skipped' => 0,
            'failed' => 0,
        ];

        if (in_array($modelOption, ['all', 'gallery'], true)) {
            $totals = $this->mergeResults(
                $totals,
                $this->regenerateQuery(
                    GalleryImage::query()->whereNotNull('image_path'),
                    $manager,
                ),
            );
        }

        if (in_array($modelOption, ['all', 'menu'], true)) {
            $totals = $this->mergeResults(
                $totals,
                $this->regenerateQuery(
                    MenuItem::query()->whereNotNull('image_path'),
                    $manager,
                ),
            );
        }

        $summary = sprintf(
            'Processed %d image records; succeeded %d; skipped %d; failed %d.',
            $totals['processed'],
            $totals['succeeded'],
            $totals['skipped'],
            $totals['failed'],
        );

        if ($totals['failed'] > 0) {
            $this->error($summary);

            return self::FAILURE;
        }

        $this->info($summary);

        return self::SUCCESS;
    }

    /**
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     * @return array{processed: int, succeeded: int, skipped: int, failed: int}
     */
    private function regenerateQuery(Builder $query, ResponsiveImageManager $manager): array
    {
        $result = [
            'processed' => 0,
            'succeeded' => 0,
            'skipped' => 0,
            'failed' => 0,
        ];

        $query->eachById(function (Model $record) use ($manager, &$result): void {
            $result['processed']++;
            $path = $record->getAttribute('image_path');

            if (! is_string($path) || $path === '') {
                $result['skipped']++;

                return;
            }

            if ($manager->generate($path) === []) {
                $result['failed']++;
                $this->warn(sprintf(
                    'Responsive image regeneration failed for %s record [%s] at [%s].',
                    $record::class,
                    (string) $record->getKey(),
                    $path,
                ));

                return;
            }

            $result['succeeded']++;
        }, 100);

        return $result;
    }

    /**
     * @param  array{processed: int, succeeded: int, skipped: int, failed: int}  $left
     * @param  array{processed: int, succeeded: int, skipped: int, failed: int}  $right
     * @return array{processed: int, succeeded: int, skipped: int, failed: int}
     */
    private function mergeResults(array $left, array $right): array
    {
        return [
            'processed' => $left['processed'] + $right['processed'],
            'succeeded' => $left['succeeded'] + $right['succeeded'],
            'skipped' => $left['skipped'] + $right['skipped'],
            'failed' => $left['failed'] + $right['failed'],
        ];
    }
}
