<?php

use App\Http\Controllers\Public\BanquetHallController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\GalleryController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\MenuController;
use App\Http\Controllers\Public\OrderInquiryController;
use App\Http\Controllers\Public\ReservationRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/menu', MenuController::class)->name('menu');
Route::get('/gallery', GalleryController::class)->name('gallery');
Route::get('/banquet-hall', BanquetHallController::class)->name('banquet-hall');

Route::get('/reservation-request', [ReservationRequestController::class, 'create'])
    ->name('reservation-request.create');

Route::get('/order-inquiry', [OrderInquiryController::class, 'create'])
    ->name('order-inquiry.create');

Route::get('/contact', [ContactController::class, 'create'])
    ->name('contact.create');
