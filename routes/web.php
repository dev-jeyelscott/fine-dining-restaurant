<?php

use App\Http\Controllers\PublicSite\BanquetHallController;
use App\Http\Controllers\PublicSite\ContactController;
use App\Http\Controllers\PublicSite\GalleryController;
use App\Http\Controllers\PublicSite\HomeController;
use App\Http\Controllers\PublicSite\MenuController;
use App\Http\Controllers\PublicSite\OrderInquiryController;
use App\Http\Controllers\PublicSite\ReservationRequestController;
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

Route::middleware('throttle:public-forms')->group(function (): void {
    Route::post('/reservation-requests', [ReservationRequestController::class, 'store'])
        ->name('reservation-requests.store');

    Route::post('/order-inquiries', [OrderInquiryController::class, 'store'])
        ->name('order-inquiries.store');

    Route::post('/contact-inquiries', [ContactController::class, 'store'])
        ->name('contact-inquiries.store');
});
