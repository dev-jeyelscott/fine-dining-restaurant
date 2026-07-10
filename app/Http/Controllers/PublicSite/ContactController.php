<?php

namespace App\Http\Controllers\PublicSite;

use App\Actions\Inquiries\StoreContactInquiry;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactInquiryRequest;
use App\Models\GalleryImage;
use App\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    public function create(): View
    {
        $heroImage = GalleryImage::query()
            ->visible()
            ->where('category', 'interior')
            ->ordered()
            ->first()
            ?? GalleryImage::query()
                ->visible()
                ->ordered()
                ->first();

        return view('pages.contact', [
            'page' => Page::query()
                ->where('slug', 'contact')
                ->where('is_published', true)
                ->first(),
            'heroImage' => $heroImage,
        ]);
    }

    public function store(
        StoreContactInquiryRequest $request,
        StoreContactInquiry $storeContactInquiry,
    ): RedirectResponse {
        $storeContactInquiry->handle($request->validated());

        return redirect()
            ->to(route('contact.create').'#contact-inquiry')
            ->with(
                'success',
                'Your message has been received. Our team will review it and contact you if a response is needed.',
            );
    }
}
