<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Models\Page;
use Illuminate\Contracts\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        return view('pages.menu', [
            'page' => Page::query()
                ->where('slug', 'menu')
                ->where('is_published', true)
                ->first(),

            'categories' => MenuCategory::query()
                ->where('is_visible', true)
                ->with([
                    'menuItems' => fn ($query) => $query
                        ->where('is_visible', true)
                        ->orderBy('sort_order', 'desc')
                        ->orderBy('name'),
                ])
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
        ]);
    }
}
