<?php

use App\Actions\Inquiries\StoreContactInquiry;
use App\Actions\Inquiries\StoreOrderInquiry;
use App\Actions\Inquiries\StoreReservationRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use RuntimeException;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    config()->set('mail.inquiries_to', 'restaurant@example.test');

    Mail::shouldReceive('to')
        ->once()
        ->with('restaurant@example.test')
        ->andReturnSelf();

    Mail::shouldReceive('send')
        ->once()
        ->andThrow(new RuntimeException('Simulated SMTP failure.'));
});

test('reservation request remains stored when notification email fails', function (): void {
    $reservationRequest = app(StoreReservationRequest::class)->handle([
        'customer_name' => 'Maria Santos',
        'phone' => '09171234567',
        'email' => 'maria@example.test',
        'preferred_date' => now()->addDays(7)->toDateString(),
        'preferred_time' => '7:00 PM',
        'guest_count' => 4,
        'special_requests' => 'Window seat if available.',
        'is_banquet_or_event' => false,
    ]);

    expect($reservationRequest->exists)->toBeTrue();

    $this->assertDatabaseHas('reservation_requests', [
        'id' => $reservationRequest->getKey(),
    ]);

    expect($reservationRequest->fresh()->notification_sent_at)->toBeNull();
});

test('order inquiry remains stored when notification email fails', function (): void {
    $orderInquiry = app(StoreOrderInquiry::class)->handle([
        'customer_name' => 'Maria Santos',
        'phone' => '09171234567',
        'email' => 'maria@example.test',
        'fulfillment_type' => 'pickup',
        'preferred_time' => '6:30 PM',
        'order_details' => 'Two servings of grilled salmon.',
        'quantity' => 2,
        'special_instructions' => 'Please pack the sauce separately.',
        'delivery_address' => null,
    ]);

    expect($orderInquiry->exists)->toBeTrue();

    $this->assertDatabaseHas('order_inquiries', [
        'id' => $orderInquiry->getKey(),
    ]);

    expect($orderInquiry->fresh()->notification_sent_at)->toBeNull();
});

test('contact inquiry remains stored when notification email fails', function (): void {
    $contactInquiry = app(StoreContactInquiry::class)->handle([
        'customer_name' => 'Maria Santos',
        'email' => 'maria@example.test',
        'phone' => '09171234567',
        'subject' => 'Private dining inquiry',
        'message' => 'I would like to ask about your available dining packages.',
    ]);

    expect($contactInquiry->exists)->toBeTrue();

    $this->assertDatabaseHas('contact_inquiries', [
        'id' => $contactInquiry->getKey(),
    ]);

    expect($contactInquiry->fresh()->notification_sent_at)->toBeNull();
});
