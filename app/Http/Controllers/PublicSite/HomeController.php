<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('pages.home', [
            'page' => Page::query()
                ->where('slug', 'home')
                ->where('is_published', true)
                ->first(),

            'settings' => SiteSetting::keyValueMap(),

            'featuredMenuItems' => MenuItem::query()
                ->with('menuCategory')
                ->where('is_visible', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->limit(6)
                ->get(),

            'galleryImages' => GalleryImage::query()
                ->where('is_visible', true)
                ->orderBy('sort_order', 'desc')
                ->orderByDesc('created_at')
                ->limit(6)
                ->get(),
        ]);
    }
}
