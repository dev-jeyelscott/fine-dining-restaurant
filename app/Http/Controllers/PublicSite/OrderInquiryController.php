<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Contracts\View\View;

class OrderInquiryController extends Controller
{
    public function create(): View
    {
        return view('pages.order-inquiry', [
            'page' => Page::query()
                ->where('slug', 'order-inquiry')
                ->where('is_published', true)
                ->first(),
        ]);
    }
}
