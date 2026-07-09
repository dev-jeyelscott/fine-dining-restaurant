<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use App\Models\Page;
use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;

class BanquetHallController extends Controller
{
    public function index(): View
    {
        return view('pages.banquet-hall', [
            'page' => Page::query()
                ->where('slug', 'banquet-hall')
                ->where('is_published', true)
                ->first(),

            'settings' => SiteSetting::keyValueMap(),

            'banquetImages' => GalleryImage::query()
                ->where('is_visible', true)
                ->where('category', 'banquet')
                ->orderBy('sort_order', 'desc')
                ->orderByDesc('created_at')
                ->limit(6)
                ->get(),
        ]);
    }
}
