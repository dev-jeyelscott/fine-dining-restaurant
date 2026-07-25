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
                ->visible()
                ->with([
                    'menuItems' => fn ($query) => $query
                        ->reorder()
                        ->visible()
                        ->ordered(),
                ])
                ->ordered()
                ->get(),
        ]);
    }
}
