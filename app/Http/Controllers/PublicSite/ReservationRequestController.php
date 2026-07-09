<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Contracts\View\View;

class ReservationRequestController extends Controller
{
    public function create(): View
    {
        return view('pages.reservation-request', [
            'page' => Page::query()
                ->where('slug', 'reservation-request')
                ->where('is_published', true)
                ->first(),
        ]);
    }
}
