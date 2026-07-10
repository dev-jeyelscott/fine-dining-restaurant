<?php

namespace App\Http\Controllers\PublicSite;

use App\Actions\Inquiries\StoreContactInquiry;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactInquiryRequest;
use App\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    public function create(): View
    {
        return view('pages.contact', [
            'page' => Page::query()
                ->where('slug', 'contact')
                ->where('is_published', true)
                ->first(),
        ]);
    }

    public function store(
        StoreContactInquiryRequest $request,
        StoreContactInquiry $storeContactInquiry,
    ): RedirectResponse {
        $storeContactInquiry->handle($request->validated());

        return back()->with(
            'success',
            'Your message has been received. Our team will review it and contact you if a response is needed.',
        );
    }
}
