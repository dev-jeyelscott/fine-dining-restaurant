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
        $galleryImages = GalleryImage::query()
            ->visible()
            ->ordered()
            ->limit(6)
            ->get();

        $heroImage = $galleryImages->firstWhere('category', 'interior')
            ?? $galleryImages->first();

        $storyImage = $galleryImages->firstWhere('category', 'dish')
            ?? $galleryImages->skip(1)->first()
            ?? $heroImage;

        $banquetImage = $galleryImages->firstWhere('category', 'banquet')
            ?? $galleryImages->skip(2)->first()
            ?? $heroImage;

        return view('pages.home', [
            'page' => Page::query()
                ->where('slug', 'home')
                ->where('is_published', true)
                ->first(),

            'settings' => SiteSetting::keyValueMap(),

            'featuredMenuItems' => MenuItem::query()
                ->with('menuCategory')
                ->visible()
                ->ordered()
                ->limit(3)
                ->get(),

            'galleryImages' => $galleryImages,
            'heroImage' => $heroImage,
            'storyImage' => $storyImage,
            'banquetImage' => $banquetImage,
        ]);
    }
}
