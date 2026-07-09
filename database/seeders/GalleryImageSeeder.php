<?php

namespace Database\Seeders;

use App\Models\GalleryImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class GalleryImageSeeder extends Seeder
{
    public function run(): void
    {
        $this->ensurePlaceholderImageExists();

        $images = [
            [
                'title' => 'Elegant Dining Room',
                'alt_text' => 'Elegant fine-dining restaurant interior',
                'image_path' => 'gallery/placeholders/dining-room.png',
                'category' => 'interior',
                'sort_order' => 1,
                'is_visible' => true,
            ],
            [
                'title' => 'Signature Dish',
                'alt_text' => 'Chef-prepared signature dish presentation',
                'image_path' => 'gallery/placeholders/signature-dish.png',
                'category' => 'dish',
                'sort_order' => 2,
                'is_visible' => true,
            ],
            [
                'title' => 'Banquet Setup',
                'alt_text' => 'Banquet hall setup for a private event',
                'image_path' => 'gallery/placeholders/banquet-setup.png',
                'category' => 'banquet',
                'sort_order' => 3,
                'is_visible' => true,
            ],
            [
                'title' => 'Warm Restaurant Ambiance',
                'alt_text' => 'Warm restaurant lighting and ambiance',
                'image_path' => 'gallery/placeholders/ambiance.png',
                'category' => 'ambiance',
                'sort_order' => 4,
                'is_visible' => true,
            ],
        ];

        foreach ($images as $image) {
            GalleryImage::updateOrCreate(
                ['image_path' => $image['image_path']],
                $image,
            );
        }
    }

    private function ensurePlaceholderImageExists(): void
    {
        $placeholderPng = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p9sAAAAASUVORK5CYII='
        );

        foreach (
            [
                'gallery/placeholders/dining-room.png',
                'gallery/placeholders/signature-dish.png',
                'gallery/placeholders/banquet-setup.png',
                'gallery/placeholders/ambiance.png',
            ] as $path
        ) {
            if (! Storage::disk('public')->exists($path)) {
                Storage::disk('public')->put($path, $placeholderPng);
            }
        }
    }
}
