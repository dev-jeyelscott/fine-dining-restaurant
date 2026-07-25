<?php

namespace App\Http\Controllers\PublicSite;

use App\Actions\Inquiries\StoreOrderInquiry;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderInquiryRequest;
use App\Models\GalleryImage;
use App\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class OrderInquiryController extends Controller
{
    public function create(): View
    {
        $heroImages = GalleryImage::query()
            ->visible()
            ->ordered()
            ->limit(6)
            ->get();

        $heroImage = $heroImages->firstWhere('category', 'interior')
            ?? $heroImages->first();

        return view('pages.order-inquiry', [
            'page' => Page::query()
                ->where('slug', 'order-inquiry')
                ->where('is_published', true)
                ->first(),
            'heroImage' => $heroImage,
        ]);
    }

    public function store(
        StoreOrderInquiryRequest $request,
        StoreOrderInquiry $storeOrderInquiry
    ): RedirectResponse|JsonResponse {
        $storeOrderInquiry->handle($request->validated());

        $message = 'Your Order Inquiry has been received. Our team will review your inquiry and contact you to confirm availability, the final total, payment, and pickup or delivery arrangements.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return back()->with(
            'status',
            $message,
        );
    }
}
