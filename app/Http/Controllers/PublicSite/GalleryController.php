<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use App\Models\Page;
use Illuminate\Contracts\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        return view('pages.gallery', [
            'page' => Page::query()
                ->where('slug', 'gallery')
                ->where('is_published', true)
                ->first(),

            'galleryImages' => GalleryImage::query()
                ->where('is_visible', true)
                ->orderBy('sort_order', 'desc')
                ->orderByDesc('created_at')
                ->get(),
        ]);
    }
}
