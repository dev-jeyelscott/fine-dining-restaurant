<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

final class SafeImageDimensions implements ValidationRule
{
    public function __construct(
        private readonly int $maxWidth = 6000,
        private readonly int $maxHeight = 6000,
        private readonly int $maxPixels = 30_000_000,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof UploadedFile || ! $value->isValid()) {
            return;
        }

        $path = $value->getRealPath();
        $dimensions = $path === false ? false : @getimagesize($path);

        if ($dimensions === false) {
            $fail('The :attribute must be a valid image.');

            return;
        }

        $width = (int) $dimensions[0];
        $height = (int) $dimensions[1];

        if (
            $width > $this->maxWidth
            || $height > $this->maxHeight
            || ($width * $height) > $this->maxPixels
        ) {
            $fail(sprintf(
                'The :attribute dimensions are too large. Use an image no larger than %d × %d pixels and %d megapixels.',
                $this->maxWidth,
                $this->maxHeight,
                (int) floor($this->maxPixels / 1_000_000),
            ));
        }
    }
}
