<?php

namespace App\Http\Controllers\PublicSite;

use App\Actions\Inquiries\StoreOrderInquiry;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderInquiryRequest;
use App\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

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

    public function store(
        StoreOrderInquiryRequest $request,
        StoreOrderInquiry $storeOrderInquiry
    ): RedirectResponse {
        $storeOrderInquiry->handle($request->validated());

        return back()->with(
            'status',
            'Your order inquiry has been received. Our team will review your request, confirm availability, confirm the final total, and arrange payment or pickup/delivery details directly with you.'
        );
    }
}
