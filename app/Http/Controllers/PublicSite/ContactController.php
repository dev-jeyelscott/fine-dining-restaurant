<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;

class ContactController extends Controller
{
    public function create(): View
    {
        return view('pages.contact', [
            'page' => Page::query()
                ->where('slug', 'contact')
                ->where('is_published', true)
                ->first(),

            'settings' => SiteSetting::keyValueMap(),
        ]);
    }
}
