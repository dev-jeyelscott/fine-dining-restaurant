<?php

namespace App\Http\Controllers\PublicSite;

use App\Actions\Inquiries\StoreReservationRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReservationRequestRequest;
use App\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

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

    public function store(
        StoreReservationRequestRequest $request,
        StoreReservationRequest $storeReservationRequest
    ): RedirectResponse {
        $storeReservationRequest->handle($request->validated());

        return back()->with(
            'status',
            'Your reservation request has been received. Our team will review your request and contact you to confirm availability. This is not yet a confirmed reservation.'
        );
    }
}
