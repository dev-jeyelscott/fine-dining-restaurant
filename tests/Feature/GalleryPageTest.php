<?php

use App\Models\GalleryImage;
use App\Models\Page;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;

beforeEach(function (): void {
    Storage::fake('public');
});

function storeGalleryPageTestImage(string $filename): string
{
    $path = UploadedFile::fake()
        ->image($filename, 2400, 1600)
        ->storeAs('gallery', $filename, 'public');

    expect($path)->toBeString();

    return $path;
}

test('gallery page presents visible images in a premium editorial layout', function (): void {
    GalleryImage::query()->create([
        'title' => 'Grand Dining Room',
        'alt_text' => 'Warmly lit fine-dining room',
        'image_path' => storeGalleryPageTestImage('grand-dining-room.jpg'),
        'category' => 'Ambiance',
        'sort_order' => 30,
        'is_visible' => true,
    ]);

    GalleryImage::query()->create([
        'title' => 'Golden Truffle Tenderloin',
        'alt_text' => 'Golden truffle beef tenderloin',
        'image_path' => storeGalleryPageTestImage('golden-truffle-tenderloin.jpg'),
        'category' => 'Signature Dish',
        'sort_order' => 20,
        'is_visible' => true,
    ]);

    GalleryImage::query()->create([
        'title' => 'Private Celebration',
        'alt_text' => 'Private banquet celebration setup',
        'image_path' => storeGalleryPageTestImage('private-celebration.jpg'),
        'category' => 'Celebrations',
        'sort_order' => 10,
        'is_visible' => true,
    ]);

    GalleryImage::query()->create([
        'title' => 'Hidden Preparation Area',
        'alt_text' => 'Hidden preparation area',
        'image_path' => 'gallery/hidden-preparation-area.jpg',
        'category' => 'Operations',
        'sort_order' => 40,
        'is_visible' => false,
    ]);

    $this->get(route('gallery'))
        ->assertOk()
        ->assertSee('id="gallery-collection"', false)
        ->assertSee('data-home-motion', false)
        ->assertSee('data-gallery-motion', false)
        ->assertSee('data-gsap="hero-image"', false)
        ->assertSee('data-gsap="frame"', false)
        ->assertSee('data-gsap="gallery"', false)
        ->assertSee('data-gsap-counter', false)
        ->assertSee('data-gsap="panel"', false)
        ->assertSee('fetchpriority="high"', false)
        ->assertSeeText('A closer look at the experience')
        ->assertSeeText('The Collection')
        ->assertSeeText('Grand Dining Room')
        ->assertSeeText('Golden Truffle Tenderloin')
        ->assertSeeText('Private Celebration')
        ->assertSeeText('Ambiance')
        ->assertSeeText('Signature Dish')
        ->assertSeeText('Celebrations')
        ->assertDontSeeText('Hidden Preparation Area');
});

test('gallery motion hooks preserve accessible content and responsive image contracts', function (): void {
    GalleryImage::query()->create([
        'title' => 'Layered Dining Room',
        'alt_text' => 'Layered dining room view',
        'image_path' => storeGalleryPageTestImage('layered-dining-room.jpg'),
        'category' => 'Ambiance',
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    $this->get(route('gallery'))
        ->assertOk()
        ->assertSee('data-gsap="image"', false)
        ->assertSee('data-gsap="parallax"', false)
        ->assertSee('data-gsap="tile"', false)
        ->assertSee('data-gsap-reveal', false)
        ->assertSee('srcset=', false)
        ->assertSee('loading="lazy"', false)
        ->assertSee('alt="Layered dining room view"', false);
});

test('gallery page bounds visible image rendering with simple pagination', function (): void {
    foreach (range(1, 14) as $index) {
        GalleryImage::query()->create([
            'title' => sprintf('Gallery Moment %02d', $index),
            'alt_text' => "Gallery moment {$index}",
            'image_path' => "gallery/gallery-moment-{$index}.jpg",
            'category' => $index % 2 === 0 ? 'Cuisine' : 'Ambiance',
            'sort_order' => $index,
            'is_visible' => true,
        ]);
    }

    GalleryImage::query()->create([
        'title' => 'Hidden Gallery Moment',
        'alt_text' => 'Hidden gallery moment',
        'image_path' => 'gallery/hidden-gallery-moment.jpg',
        'category' => 'Operations',
        'sort_order' => 0,
        'is_visible' => false,
    ]);

    $expectedFirstPageTitles = collect(range(1, 12))
        ->map(fn (int $index): string => sprintf('Gallery Moment %02d', $index))
        ->all();

    $this->get(route('gallery', ['view' => 'editorial']))
        ->assertOk()
        ->assertViewHas('galleryImages', function (Paginator $galleryImages) use ($expectedFirstPageTitles): bool {
            return $galleryImages->perPage() === 12
                && $galleryImages->currentPage() === 1
                && $galleryImages->count() === 12
                && $galleryImages->getCollection()->pluck('title')->all() === $expectedFirstPageTitles;
        })
        ->assertSeeText('Gallery Moment 01')
        ->assertSeeText('Gallery Moment 12')
        ->assertDontSeeText('Gallery Moment 13')
        ->assertDontSeeText('Gallery Moment 14')
        ->assertDontSeeText('Hidden Gallery Moment')
        ->assertSee('aria-label="Pagination Navigation"', false)
        ->assertSee('rel="next"', false)
        ->assertSee('view=editorial&amp;page=2#gallery-collection', false);

    $expectedSecondPageTitles = ['Gallery Moment 13', 'Gallery Moment 14'];

    $this->get(route('gallery', ['page' => 2, 'view' => 'editorial']))
        ->assertOk()
        ->assertViewHas('galleryImages', function (Paginator $galleryImages) use ($expectedSecondPageTitles): bool {
            return $galleryImages->perPage() === 12
                && $galleryImages->currentPage() === 2
                && $galleryImages->count() === 2
                && $galleryImages->getCollection()->pluck('title')->all() === $expectedSecondPageTitles;
        })
        ->assertSeeText('Gallery Moment 13')
        ->assertSeeText('Gallery Moment 14')
        ->assertDontSeeText('Gallery Moment 12')
        ->assertDontSeeText('Hidden Gallery Moment')
        ->assertSee('rel="prev"', false)
        ->assertDontSee('rel="next"', false);
});

test('gallery page keeps editorial grid tracks aligned with card minimum heights', function (int $imageCount): void {
    foreach (range(1, $imageCount) as $index) {
        GalleryImage::query()->create([
            'title' => "Editorial Gallery Moment {$index} With A Long Descriptive Caption",
            'alt_text' => "Editorial gallery moment {$index}",
            'image_path' => "gallery/editorial-gallery-moment-{$index}.jpg",
            'category' => $index % 2 === 0 ? 'Celebrations' : 'Ambiance',
            'sort_order' => $imageCount - $index,
            'is_visible' => true,
        ]);
    }

    $response = $this->get(route('gallery'))
        ->assertOk()
        ->assertSee('class="mt-14 grid gap-4 md:auto-rows-[18rem] md:grid-cols-2 lg:grid-cols-3"', false)
        ->assertSee('min-h-72', false)
        ->assertSee('md:row-span-2', false)
        ->assertDontSee('auto-rows-[14rem]', false)
        ->assertDontSee('sm:auto-rows-[16rem]', false)
        ->assertSeeText('Editorial Gallery Moment 1 With A Long Descriptive Caption')
        ->assertSeeText("Editorial Gallery Moment {$imageCount} With A Long Descriptive Caption");

    if ($imageCount >= 4) {
        $response->assertSee('lg:row-span-2', false);
    }
})->with([2, 4, 8, 9]);

test('gallery page renders sanitized rich editor content without escaped tags', function (): void {
    Page::query()->create([
        'slug' => 'gallery',
        'title' => 'Gallery',
        'content' => '<p>Every image reflects <strong>refined hospitality</strong>.</p><p onclick="alert(1)">Managed through the CMS.</p>',
        'is_published' => true,
    ]);

    $this->get(route('gallery'))
        ->assertOk()
        ->assertSee('<strong>refined hospitality</strong>', false)
        ->assertSeeText('Managed through the CMS.')
        ->assertDontSee('&lt;p&gt;', false)
        ->assertDontSee('onclick=', false);
});

test('gallery page keeps plain seeded content readable', function (): void {
    Page::query()->create([
        'slug' => 'gallery',
        'title' => 'Gallery',
        'content' => 'A plain seeded gallery introduction remains readable.',
        'is_published' => true,
    ]);

    $this->get(route('gallery'))
        ->assertOk()
        ->assertSeeText('A plain seeded gallery introduction remains readable.');
});

test('gallery page uses the approved introduction when page content is empty', function (): void {
    Page::query()->create([
        'slug' => 'gallery',
        'title' => 'Gallery',
        'content' => '',
        'is_published' => true,
    ]);

    $this->get(route('gallery'))
        ->assertOk()
        ->assertSeeText('From softly lit dining rooms to carefully finished plates and thoughtfully prepared celebrations, every image reflects the warmth, refinement, and attention to detail guests can expect.');
});

test('gallery page handles a single visible image', function (): void {
    GalleryImage::query()->create([
        'title' => 'Intimate Dining Room',
        'alt_text' => 'Intimate dining room with warm lighting',
        'image_path' => 'gallery/intimate-dining-room.jpg',
        'category' => 'Ambiance',
        'sort_order' => 10,
        'is_visible' => true,
    ]);

    $this->get(route('gallery'))
        ->assertOk()
        ->assertSeeText('Intimate Dining Room')
        ->assertSeeText('Moments on this page')
        ->assertSeeText('Categories on this page');
});

test('gallery page keeps its empty state and scope safe continuation links', function (): void {
    $this->get(route('gallery'))
        ->assertOk()
        ->assertSeeText('No visible gallery images yet. Add images from the Filament admin panel.')
        ->assertSeeText('Explore Banquet Hall')
        ->assertSeeText('Contact Us')
        ->assertDontSeeText('Book Now')
        ->assertDontSeeText('Order Now');
});
