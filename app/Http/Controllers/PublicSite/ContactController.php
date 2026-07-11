<?php

namespace App\Http\Controllers\PublicSite;

use App\Actions\Inquiries\StoreContactInquiry;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactInquiryRequest;
use App\Models\GalleryImage;
use App\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
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
    ): RedirectResponse|JsonResponse {
        $storeContactInquiry->handle($request->validated());

        $message = 'Your Contact Inquiry has been received. Our team will review your message and contact you if a response is needed.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()
            ->to(route('contact.create').'#contact-inquiry')
            ->with(
                'success',
                $message,
            );
    }
}
