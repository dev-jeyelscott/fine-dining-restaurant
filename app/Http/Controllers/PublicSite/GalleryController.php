<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use App\Models\Page;
use Illuminate\Contracts\View\View;

class GalleryController extends Controller
{
    private const IMAGES_PER_PAGE = 12;

    public function index(): View
    {
        return view('pages.gallery', [
            'page' => Page::query()
                ->where('slug', 'gallery')
                ->where('is_published', true)
                ->first(),

            'galleryImages' => GalleryImage::query()
                ->visible()
                ->ordered()
                ->simplePaginate(self::IMAGES_PER_PAGE)
                ->withQueryString()
                ->fragment('gallery-collection'),
        ]);
    }
}
