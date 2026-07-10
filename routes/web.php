<?php

use App\Http\Controllers\PublicSite\BanquetHallController;
use App\Http\Controllers\PublicSite\ContactController;
use App\Http\Controllers\PublicSite\GalleryController;
use App\Http\Controllers\PublicSite\HomeController;
use App\Http\Controllers\PublicSite\MenuController;
use App\Http\Controllers\PublicSite\OrderInquiryController;
use App\Http\Controllers\PublicSite\ReservationRequestController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/menu', [MenuController::class, 'index'])->name('menu');

Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');

Route::get('/banquet-hall', [BanquetHallController::class, 'index'])->name('banquet-hall');

Route::get('/reservation-request', [ReservationRequestController::class, 'create'])
    ->name('reservation-request.create');

Route::get('/order-inquiry', [OrderInquiryController::class, 'create'])
    ->name('order-inquiry.create');

Route::get('/contact', [ContactController::class, 'create'])
    ->name('contact.create');

Route::get('/robots.txt', function (): Response {
    $content = implode("\n", [
        'User-agent: *',
        'Allow: /',
        'Disallow: /admin',
        'Sitemap: '.route('sitemap'),
        '',
    ]);

    return new Response($content, 200, [
        'Content-Type' => 'text/plain; charset=UTF-8',
    ]);
})->name('robots');

Route::get('/sitemap.xml', function (): Response {
    $urls = [
        route('home'),
        route('menu'),
        route('reservation-request.create'),
        route('order-inquiry.create'),
        route('gallery'),
        route('banquet-hall'),
        route('contact.create'),
    ];

    $entries = implode("\n", array_map(
        static fn (string $url): string => '    <url><loc>'.e($url).'</loc></url>',
        $urls,
    ));

    $content = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
{$entries}
</urlset>
XML;

    return new Response($content, 200, [
        'Content-Type' => 'application/xml; charset=UTF-8',
    ]);
})->name('sitemap');

Route::middleware('throttle:public-forms')->group(function (): void {
    Route::post('/reservation-requests', [ReservationRequestController::class, 'store'])
        ->name('reservation-requests.store');

    Route::post('/order-inquiries', [OrderInquiryController::class, 'store'])
        ->name('order-inquiries.store');

    Route::post('/contact-inquiries', [ContactController::class, 'store'])
        ->name('contact-inquiries.store');
});
